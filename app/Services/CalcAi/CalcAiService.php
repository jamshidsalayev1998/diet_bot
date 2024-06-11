<?php

namespace App\Services\CalcAi;

use App\Services\RandomStringService;
use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Support\Facades\Storage;

class CalcAiService
{
    use TelegramMessageLangsTrait;
    public static function message_to_ai($chat, $message, $photos, $calc_ai_conversation, $token, $userInfo)
    {
        if (count($photos)) {
            if ($calc_ai_conversation->image) {

                $chat->message(self::lang('only_one_image_for_one_conversation'))->send();
            } else {
                // $chat->message('token - ' . $token)->send();
                $resultStore = Telegraph::store($photos[3], Storage::path('/calc_ai_images/' . $chat->chat_id), RandomStringService::randomAlphaAndNumberHelper(15) . '.jpg');
                $calc_ai_conversation->image = $resultStore;
                $calc_ai_conversation->update();
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
                $responseAi = CalcAiApiService::request('/api/product', 'post', $body, $token);
                // $responseAi = json_decode($calc_ai_conversation->response,true);
                if ($responseAi['status']) {
                    $parsedText = self::parse_response($responseAi,$userInfo->language);
                    $calc_ai_conversation->response = $responseAi;
                    $calc_ai_conversation->product_id = $responseAi['data']['data']['product_id'];
                    $calc_ai_conversation->update();
                    $chat->html($parsedText)->send();
                }
                else{
                    $chat->message(self::lang('calc_ai_error_please_try_again_later'));
                }
            }
        } else {
            if ($calc_ai_conversation->image) {
            } else {
                $chat->message(self::lang('before_need_image_to_conversation'))->send();
            }
        }
    }

    public static function parse_response($response, $language)
    {
        $dataResponse = $response['data']['data']['result'];
        $text = self::lang('ai_calculated')."\n";
        $text .=self::lang('i_think_its').': <strong>'. $dataResponse['title']."</strong> \n\n";
        $text .="<strong>" .self::lang('total_calories')."</strong> \n\n";
        $text .= $dataResponse['total_calories']." kkal \n\n";
        $text .= "<strong>" .self::lang('macros')."</strong> \n\n";
        $text .= "•".self::lang('proteins').": ".$dataResponse['macros']['proteins']."\n";
        $text .= "•".self::lang('carbs').": ".$dataResponse['macros']['carbs']."\n";
        $text .= "•".self::lang('fats').": ".$dataResponse['macros']['fats']."\n\n";
        $text .= "<strong>" .self::lang('ingredients')."</strong> \n\n";
        foreach($dataResponse['ingredients'] as $itemIngredient){
            $text .= "•".$itemIngredient['title']." (${itemIngredient['grams']}g ${itemIngredient['calories']}kkal) \n";
        }
        return $text;

    }
}
