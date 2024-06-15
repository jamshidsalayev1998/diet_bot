<?php

namespace App\Console\Commands\CalcAi;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\V1\CalcAiConversation;
use App\Services\TelegramButtonService;

class TurnOffTheOldConversationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calcAi:turnOffTheOldConversations';

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
        $fiveMinuteAgo = Carbon::now()->subMinutes(5);
        $conversations = CalcAiConversation::where('created_at', '<', $fiveMinuteAgo)->delete();
        foreach($conversations as $conversation){
            $telegraph_chat = $conversation->telegraph_chat;
            TelegramButtonService::stop_calc_ai_conversation($telegraph_chat);
        }
    }
}
