<?php

namespace App\Console\Commands\CalcAi;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\V1\CalcAiConversation;
use App\Services\CalcAi\CalcAiService;
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
        $conversations = CalcAiConversation::where('updated_at', '<', $fiveMinuteAgo)->get();
        foreach($conversations as $conversation){
            $telegraph_chat = $conversation->telegraph_chat;
            if($conversation->product_id && $conversation->status == 1){
                CalcAiService::delete_product_ai($conversation->product_id , '6502974186:AAHY3T5E9jkXNre7aqZ0ShvNt25x23mC0DUzafar0000A');
            }
            TelegramButtonService::stop_calc_ai_conversation($telegraph_chat);
            $conversation->delete();
        }
    }
}
