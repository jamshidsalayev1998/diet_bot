<?php

namespace App\Console\Commands\UserMenu;

use App\Models\V1\UserInfo;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailySendUserMenuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userMenu:dailySendMenu';

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
        $this->sendMealNotification('breakfast');
        $this->sendMealNotification('lunch');
        $this->sendMealNotification('dinner');
    }

    private function sendMealNotification($meal)
    {
        $time = Carbon::now()->format('H:i');
        $mealTimes = [
            'breakfast' => [
                'time' => '07:00',
                'id' => 1
            ],
            'lunch' => [
                'time' => '11:00',
                'id' => 2
            ],
            'dinner' => [
                'time' => '17:00',
                'id' => 3
            ],
        ];

        if ($time == $mealTimes[$meal]['time']) {
            $userInfos = UserInfo::where('is_premium', 1)->whereNotNull('menu_part_images')->get();
            foreach($userInfos as $userInfo){
                $chat = $userInfo->chat;
                $partImages = json_decode($userInfo->menu_part_images,true);
                $photoUrl = config('app.url').'/'.asset('storage' . $partImages[$mealTimes[$meal]['id']]);
                $chat->photo($photoUrl)->send();
                // $chat->message($photoUrl)->send();
            }
        }
    }
}
