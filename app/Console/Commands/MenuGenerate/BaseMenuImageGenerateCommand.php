<?php

namespace App\Console\Commands\MenuGenerate;

use App\Http\Resources\V1\MenuPartProductsUserShowResource;
use App\Http\Resources\V1\MenuPartUserShowResource;
use App\Models\V1\MenuPart;
use App\Models\V1\MenuSize;
use App\Models\V1\MenuType;
use App\Services\FileSave;
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
        foreach ($menuSizes as $menuSize) {
            $menuParts = MenuPart::where('menu_size_id', $menuSize->id)->get();
            $resultMenuParts = MenuPartUserShowResource::collection($menuParts);
            $grouped = $resultMenuParts->groupBy('menu_type_id');
            $ready = [];
            if (count($menuParts)) {
                foreach($menuTypes as $menuType){
                    $ready[$menuType->id]['type_title'] = $menuType->title;
                    $ready[$menuType->id]['type_id'] = $menuType->id;
                    if (isset($grouped[$menuType->id])) {
                        $ready[$menuType->id]['records'] = $grouped[$menuType->id];
                    } else {
                        $ready[$menuType->id]['records'] = []; // or handle the missing key scenario appropriately
                    }
                }
                // $this->info(json_encode($ready[1]['records'][0]));
                foreach ($langs as $lang) {
                    $imagePath = storage_path('app/public/menu_images/' . $menuSize->id . '_' . $lang . '/base_menu.png');
                    $htmlContent = view('menu_images.base_menu_template', ['data' => $ready, 'lang' => $lang])->render();
                    $htmlFilePath = storage_path('app/public/image_html/base_menu.html');
                    file_put_contents($htmlFilePath, $htmlContent);
                    $command = "wkhtmltoimage {$htmlFilePath} {$imagePath}";
                    shell_exec($command);
                }
            }
        }
    }
}
