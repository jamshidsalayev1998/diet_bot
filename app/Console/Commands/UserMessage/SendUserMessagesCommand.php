<?php

namespace App\Console\Commands\UserMessage;

use App\Models\V1\UserMessage;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Console\Command;

class SendUserMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userMessage:sendUserMessages';

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
        $messages = UserMessage::where('status', 0)->get();
        foreach ($messages as $message) {
            $chat = TelegraphChat::where('chat_id', $message->chat_id)->first();
            if ($chat) {
                $chat->html($message->message)->send();
                $message->status = 1;
                $message->update();
            }
        }
    }
}
