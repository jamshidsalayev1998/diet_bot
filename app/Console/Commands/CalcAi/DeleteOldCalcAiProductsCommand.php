<?php

namespace App\Console\Commands\CalcAi;

use App\Models\V1\CalcAiConversation;
use App\Services\CalcAi\CalcAiService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldCalcAiProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calcAi:deleteTheOldConversations';

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
        $conversations = CalcAiConversation::where('status', '=', 0)->get();
        foreach ($conversations as $conversation) {
            $token = config('calc_ai_variables.token');
            if ($conversation->product_id){
                CalcAiService::delete_product_ai($conversation->product_id, $token);
            }
            $conversation->delete();
        }
    }
}
