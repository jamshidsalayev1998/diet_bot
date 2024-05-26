<?php

namespace App\Console\Commands\Track;

use App\Models\V1\UserInfo;
use App\Services\TelegramUserInfoService;
use Illuminate\Console\Command;

class SendDailyTrackMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track:sendDailyTrackMessage';

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
        $users = UserInfo::where('status' , '>=' , 11)->where('is_premium' , 1)->get();
        foreach($users as $user){
            $chat = $user->chat;
            $text = 'something';
            $dataTrack = TelegramUserInfoService::track_message();
            $chat->message($dataTrack['text'])->keyboard($dataTrack['keyboard'])->send();
        }
    }
}
