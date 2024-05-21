<?php

namespace App\Console\Commands\MenuGenerate;

use App\Models\V1\MenuSize;
use App\Models\V1\UserInfo;
use App\Services\MenuImageGeneratorService;
use Illuminate\Console\Command;

class BaseMenuPartsImageGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menuGenerate:baseMenuPartsGenerate';

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
        $userInfos = UserInfo::where('status' , 11)->get();
        foreach($userInfos  as $userInfo){
            $resultGenerateImage = MenuImageGeneratorService::generateMenuPartsImageForOneUser($userInfo);

            $this->info(json_encode($resultGenerateImage));
        }
    }
}
