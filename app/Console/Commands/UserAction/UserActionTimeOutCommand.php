<?php

namespace App\Console\Commands\UserAction;

use App\Models\V1\UserAction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UserActionTimeOutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userAction:timeOutDelete';

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
        $oneMinuteAgo = Carbon::now()->subMinute();

        // Retrieve actions created more than one minute ago
        UserAction::where('created_at', '<', $oneMinuteAgo)->delete();
    }
}
