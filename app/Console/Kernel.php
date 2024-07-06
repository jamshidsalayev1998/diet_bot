<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('track:sendDailyTrackMessage')->dailyAt('19:01');
        $schedule->command('userAction:timeOutDelete')->everyMinute();
        $schedule->command('calcAi:turnOffTheOldConversations')->everyMinute();
        $schedule->command('calcAi:deleteTheOldConversations')->everyMinute();
        $schedule->command('userMessage:sendUserMessages')->everyMinute();
        $schedule->command('menuGenerate:reGenerateMenuForNewPremiumUsers')->everyMinute();
        $schedule->command('userMenu:dailySendMenu')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
