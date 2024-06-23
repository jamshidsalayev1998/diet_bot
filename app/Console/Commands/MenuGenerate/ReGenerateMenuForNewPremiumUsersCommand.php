<?php

namespace App\Console\Commands\MenuGenerate;

use App\Models\V1\UserInfo;
use App\Models\V1\UserMessage;
use App\Services\MenuImageGeneratorService;
use App\Services\TelegramButtonService;
use Illuminate\Console\Command;

class ReGenerateMenuForNewPremiumUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menuGenerate:reGenerateMenuForNewPremiumUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userInfos = UserInfo::where('is_premium', 1)->where('new_on_premium', 1)->get();
        foreach ($userInfos as $userInfo) {
            $message_langs = config('message_langs');
            TelegramButtonService::home($userInfo->chat, $message_langs[$userInfo->language]['conguratulation_message_for_premium']);
            MenuImageGeneratorService::generateMenuImageForOneUser($userInfo);
            MenuImageGeneratorService::generateMenuPartsImageForOneUser($userInfo);
            $userInfo->new_on_premium = false;
            $userInfo->update();
            // UserMessage::create([
            //     'chat_id' => $userInfo->chat_id,
            //     'message' => $message_langs[$userInfo->language]['conguratulation_message_for_premium']
            // ]);
        }
    }
}
