<?php

namespace App\Services\CalcAi;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class CalcAiApiService
{
    public static function request($url, $method, $body, $token)
    {
        $base_url = config('calc_ai_variables.base_url');
        $headers = [
            // 'token' => $token
            'token' => '6502974186:AAHY3T5E9jkXNre7aqZ0ShvNt25x23mC0DUzafar0000A'
        ];
        $client = new Client(array(
            'headers' => $headers
        ));
        $status = 1;
        $message = '';
        $data = [];
        try {
            if ($method == 'post') {
                $response = $client->post($base_url . $url, [
                    RequestOptions::MULTIPART => $body
                ]);
                $data = $response->getBody()->getContents();
            }
            $message = 'success';
        } catch (Exception $e) {
            $status = 0;
            $message = $e->getMessage();
            $data = [
                'file' => $e->getFile(),
                'code' => $e->getCode(),
                'line' => $e->getLine()
            ];
        }
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }
}
