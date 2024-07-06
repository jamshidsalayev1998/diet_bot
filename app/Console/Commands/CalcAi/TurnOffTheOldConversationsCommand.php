<?php

namespace App\Console\Commands\CalcAi;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\V1\CalcAiConversation;
use App\Models\V1\UserInfo;
use App\Services\CalcAi\CalcAiService;
use App\Services\TelegramButtonService;
use App\Traits\TelegramMessageLangsTrait;

class TurnOffTheOldConversationsCommand extends Command
{
    use TelegramMessageLangsTrait;
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
        $userInfos = UserInfo::all();
        foreach ($userInfos as $userInfo) {
            $fiveMinuteAgo = Carbon::now()->subMinutes(5);
            $conversations = CalcAiConversation::where('updated_at', '<', $fiveMinuteAgo)->where('chat_id', $userInfo->chat_id)->where('status', 1)->get();
            $countConversations = count($conversations);
            foreach ($conversations as $conversation) {
                if ($conversation->product_id && $conversation->status == 1) {
                    CalcAiService::delete_product_ai($conversation->product_id, '6502974186:AAHY3T5E9jkXNre7aqZ0ShvNt25x23mC0DUzafar0000A');
                }
                // TelegramButtonService::stop_calc_ai_conversation($telegraph_chat);
                $conversation->delete();
            }
            if ($countConversations) {
                $telegraph_chat = $userInfo->chat;
                TelegramButtonService::home($telegraph_chat, $this->lang('calc_ai_conversation_closed_by_system'));
            }
        }
    }
}
