<?php

namespace App\Console\Commands\MenuGenerate;

use App\Http\Resources\V1\MenuPartProductsUserShowResource;
use App\Http\Resources\V1\MenuPartUserShowResource;
use App\Models\V1\MenuPart;
use App\Models\V1\MenuSize;
use App\Models\V1\MenuType;
use App\Models\V1\UserInfo;
use App\Services\FileSave;
use App\Services\MenuImageGeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

// use Intervention\Image\Facades\Image;

class BaseMenuImageGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menuGenerate:baseMenuGenerate';

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
        $menuSizes = MenuSize::all();
        $langs = ['uz', 'ru'];
        $menuTypes = MenuType::query()->orderBy('id', 'ASC')->get();
        $userInfos = UserInfo::where('status' , 11)->get();
        foreach($userInfos  as $userInfo){
            $resultGenerateImage = MenuImageGeneratorService::generateMenuImageForOneUser($userInfo);
            $this->info(json_encode($resultGenerateImage));
        }
    }
}
