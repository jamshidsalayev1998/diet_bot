<?php

namespace App\Services\CalcAi;

use App\Services\RandomStringService;
use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Support\Facades\Storage;

class CalcAiService
{
    use TelegramMessageLangsTrait;
    public static function message_to_ai($chat, $message, $photos, $calc_ai_conversation,$token)
    {
        if (count($photos)) {
            if ($calc_ai_conversation->image) {

                $chat->message(self::lang('only_one_image_for_one_conversation'))->send();
            } else {
                $chat->message('token - ' . $token)->send();
                $resultStore = Telegraph::store($photos[3], Storage::path('/calc_ai_images/' . $chat->chat_id), RandomStringService::randomAlphaAndNumberHelper(15) . '.jpg');
                $calc_ai_conversation->image = $resultStore;
                $calc_ai_conversation->update();
                $filePath = $resultStore;
                $fileName = basename($filePath);
                $userInfo = $chat->user_info;
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
                $responseAi = CalcAiApiService::request('/api/product','post',$body,$token);
                $chat->message('stored' . json_encode($responseAi))->send();
            }
        } else {
            if ($calc_ai_conversation->image) {
            } else {
                $chat->message(self::lang('before_need_image_to_conversation'))->send();
            }
        }
    }
}
