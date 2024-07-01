<?php

namespace App\Console\Commands\Track;

use App\Models\V1\UserInfo;
use App\Services\TelegramUserInfoService;
use App\Traits\TelegramMessageLangsTrait;
use Exception;
use Illuminate\Console\Command;

class SendDailyTrackMessageCommand extends Command
{
    use TelegramMessageLangsTrait;
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
            try{

                $text = date('Y-m-d').' '.self::lang('for_this_day_how_did_you_follow').PHP_EOL.self::lang('you_must_not_eat_until_tomorrow');
                $dataTrack = TelegramUserInfoService::track_message($text);
                // $chat->message($dataTrack['text'])->keyboard($dataTrack['keyboard'])->send();
                $chat->message($dataTrack['text'])->keyboard($dataTrack['keyboard'])->send();
                $this->info('ishladi');
            }catch(Exception $e){
                $this->info($e->getMessage());

            }
        }
    }
}
