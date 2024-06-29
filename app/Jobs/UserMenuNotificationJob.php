<?php

namespace App\Jobs;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserMenuNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $chats = TelegraphChat::all();
        foreach($chats as $chat){
            $chat->message('redis is working')->send();
            sleep(10);
        }
    }
}
