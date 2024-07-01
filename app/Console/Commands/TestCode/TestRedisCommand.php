<?php

namespace App\Console\Commands\TestCode;

use App\Jobs\UserMenuNotificationJob;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Console\Command;

class TestRedisCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testCode:testRedis';

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
        $chat = TelegraphChat::find(1);
        $this->info('indo'.$chat->user_info);
    }
}
