<?php

namespace App\Services\CalcAi;

use App\Models\V1\CalcAiConversation;
use App\Services\RandomStringService;
use App\Services\TelegramButtonService;
use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Enums\ChatActions;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Storage;

class CalcAiService
{
    use TelegramMessageLangsTrait;
    public static function connect_with_ai($chat, $message, $photos, $calc_ai_conversation, $token, $userInfo)
    {
        $status = '';
        if (count($photos)) {
            $status = 'create_with_photo';
        } else {
            if (!$calc_ai_conversation->product_id) {
                $status = 'create_with_text';
            } else {
                if ($calc_ai_conversation->commenting) {
                    $status = 'commenting';
                }
            }
        }
        if ($status == 'create_with_photo' || $status == 'create_with_text') {
            // $chat->message(self::lang('creating'))->send();
            self::create_product_ai($status, $message, $photos, $chat, $userInfo, $token, $calc_ai_conversation);
        } elseif ($status == 'commenting') {
            self::comment_to_product($message, $chat, $userInfo, $token, $calc_ai_conversation);
        }
    }
    public static function create_product_ai($type_createing, $text, $photos, $chat, $userInfo, $token, $calc_ai_conversation)
    {
        if ($userInfo->calc_ai_attempts_count <= 0 && !$userInfo->is_premium) {
            $chat->html(self::lang('your_calc_ai_attempts_is_over'))->send();
            TelegramButtonService::home($chat);
        } else {
            $chat->message(self::lang('ai_calculating'))->send();
            Telegraph::chat($chat)
                ->chatAction(ChatActions::TYPING)
                ->send();
            switch ($type_createing) {
                case 'create_with_photo':
                    $lastIndex = count($photos)-1;
                    $resultStore = Telegraph::store($photos[$lastIndex], Storage::path('/calc_ai_images/' . $chat->chat_id), RandomStringService::randomAlphaAndNumberHelper(15) . '.jpg');
                    $calc_ai_conversation->image = $resultStore;
                    $filePath = $resultStore;
                    $fileName = basename($filePath);
                    $body = [
                        [
                            'name'     => 'image',
                            'contents' => fopen($filePath, 'r'),
                            'filename' => $fileName
                        ],
                        [
                            'name'     => 'lang',
                            'contents' => $userInfo->language
                        ],
                        [
                            'name' => 'type',
                            'contents' => 'image'
                        ]
                    ];
                    break;
                case 'create_with_text':
                    $body = [
                        [
                            'name'     => 'title',
                            'contents' => $text
                        ],
                        [
                            'name'     => 'lang',
                            'contents' => $userInfo->language
                        ],
                        [
                            'name' => 'type',
                            'contents' => 'text'
                        ]
                    ];
                    break;
            }
            // $chat->message(json_encode($body))->send();
            $responseAi = CalcAiApiService::request('/api/product', 'post', $body, $token, 'multipart');
            // $responseAi = json_decode('{"status":1,"message":"success","data":{"success":true,"data":{"product_id":"667806a085d42a1c792085f7","result":{"for_eat_or_drink":true,"title":"Palov","total_calories":900,"macros":{"proteins":25,"carbs":110,"fats":35},"ingredients":[{"title":"Guruch","grams":200,"calories":260},{"title":"Go\'sht","grams":150,"calories":300},{"title":"Sabzi","grams":100,"calories":40},{"title":"Piyoz","grams":100,"calories":40},{"title":"Yog\'","grams":50,"calories":300}],"is_food":true}}}}', true);
            // $chat->message(json_encode($responseAi))->send();
            if (!$responseAi['status']) {
                $chat->message(self::lang('error_during_request_to_ai'))->send();
            } else {
                if ($responseAi['data']['data']['result']['is_food'] == false) {
                    // $chat->message('fallll')->send();
                    $chat->message(self::lang('send_product_image_or_name'))->send();
                } else {
                    self::send_result_of_ai($responseAi, $chat, $userInfo, $calc_ai_conversation);
                    $calc_ai_conversation->response = $responseAi;
                    $calc_ai_conversation->product_id = $responseAi['data']['data']['product_id'];
                    $calc_ai_conversation->update();
                }
                $userInfo->calc_ai_attempts_count -= 1;
                $userInfo->update();
            }
        }
    }
    public static function comment_to_product($text, $chat, $userInfo, $token, $calc_ai_conversation)
    {
        $chat->message(self::lang('ai_calculating'))->send();
        Telegraph::chat($chat)
            ->chatAction(ChatActions::TYPING)
            ->send();
        // $chat->message('token - ' . $token)->send();
        $body = [
            'product_id' => $calc_ai_conversation->product_id,
            'comment' => $text
        ];
        // $chat->message(json_encode($body))->send();
        $responseAi = CalcAiApiService::request('/api/product/add/comment', 'post', $body, $token, 'json');
        // $responseAi = json_decode('{"status":1,"message":"success","data":{"success":true,"data":{"product_id":"667806a085d42a1c792085f7","result":{"for_eat_or_drink":true,"title":"Palov","total_calories":900,"macros":{"proteins":25,"carbs":110,"fats":35},"ingredients":[{"title":"Guruch","grams":200,"calories":260},{"title":"Go\'sht","grams":150,"calories":300},{"title":"Sabzi","grams":100,"calories":40},{"title":"Piyoz","grams":100,"calories":40},{"title":"Yog\'","grams":50,"calories":300}],"is_food":true}}}}', true);
        // $chat->message(json_encode($responseAi))->send();
        if ($responseAi['status']) {
            if ($responseAi['data']['data']['result']['is_food'] == false) {
                $chat->message(self::lang('send_product_image_or_name'))->send();
            } else {
                self::send_result_of_ai($responseAi, $chat, $userInfo, $calc_ai_conversation);
                $calc_ai_conversation->response = $responseAi;
                $calc_ai_conversation->commenting = 0;
                $calc_ai_conversation->update();
            }
        } else {
            $chat->message(self::lang('calc_ai_error_please_try_again_later'));
        }
    }
    public static function delete_product_ai($product_id, $token)
    {
        $responseAi = CalcAiApiService::request('/api/product/' . $product_id, 'delete', [], $token);
        CalcAiConversation::where('product_id', $product_id)->update(['status' => 2]);
    }
    public static function send_result_of_ai($responseAi, $chat, $userInfo, $calc_ai_conversation)
    {
        $commentKeyboard = Keyboard::make()
            ->row([
                Button::make(self::lang('ai_comment'))->action('ai_commenting')->param('calc_ai_conversation_id', $calc_ai_conversation->id),
                Button::make(self::lang('ai_delete'))->action('ai_deleting')->param('calc_ai_conversation_id', $calc_ai_conversation->id),
            ]);
        $parsedText = self::parse_response($responseAi, $userInfo->language);
        $parsedText .= PHP_EOL . self::lang('ai_result_comment_desc');
        $chat->html($parsedText)->keyboard($commentKeyboard)->send();
    }
    public static function edit_deleted_result_of_ai($responseAi, $chat, $userInfo,$messageId)
    {
        $parsedText = self::parse_response($responseAi, $userInfo->language);
        $parsedText .= PHP_EOL . self::lang('this_result_of_ai_deleted');
        $chat->edit($messageId)->html($parsedText)->send();
        // $chat->html($parsedText)->send();
    }
    public static function message_to_ai($chat, $message, $photos, $calc_ai_conversation, $token, $userInfo)
    {
    }
    public static function comment_to_ai($chat, $message, $calc_ai_conversation, $token, $userInfo)
    {
        if (!$calc_ai_conversation->image && !$calc_ai_conversation->product_id) {
            $chat->message(self::lang('first_send_image'))->send();
        } else {
            Telegraph::chat($chat)
                ->chatAction(ChatActions::TYPING)
                ->send();
            // $chat->message('token - ' . $token)->send();
            $body = [
                'product_id' => $calc_ai_conversation->product_id,
                'comment' => $message
            ];
            $responseAi = CalcAiApiService::request('/api/product/add/comment', 'post', $body, $token, 'json');
            // $responseAi = json_decode($calc_ai_conversation->response, true);
            // $chat->html(json_encode($responseAi))->send();
            if ($responseAi['status']) {
                $parsedText = self::parse_response($responseAi, $userInfo->language);
                $calc_ai_conversation->response = $responseAi;
                $calc_ai_conversation->update();
                $chat->html($parsedText)->send();
            } else {
                $chat->message(self::lang('calc_ai_error_please_try_again_later'));
            }
        }
    }

    public static function parse_response($response, $language)
    {
        $dataResponse = $response['data']['data']['result'];
        $text = self::lang('ai_calculated') . "\n";
        $text .= self::lang('i_think_its') . ': <strong>' . $dataResponse['title'] . "</strong> \n\n";
        $text .= "<strong>" . self::lang('total_calories') . "</strong> \n\n";
        $text .= $dataResponse['total_calories'] . " kkal \n\n";
        $text .= "<strong>" . self::lang('macros') . "</strong> \n\n";
        $text .= "•" . self::lang('proteins') . ": " . $dataResponse['macros']['proteins'] . "g \n";
        $text .= "•" . self::lang('carbs') . ": " . $dataResponse['macros']['carbs'] . "g \n";
        $text .= "•" . self::lang('fats') . ": " . $dataResponse['macros']['fats'] . "g \n\n";
        $text .= "<strong>" . self::lang('ingredients') . "</strong> \n\n";
        if (key_exists('ingredients', $dataResponse)) {
            // $text .= count($dataResponse['ingredients']);
            foreach ($dataResponse['ingredients'] as $itemIngredient) {
                $text .= "•" . $itemIngredient['title'] . " (${itemIngredient['grams']}g ${itemIngredient['calories']}kkal) \n";
            }
        }
        return $text;
    }
}
