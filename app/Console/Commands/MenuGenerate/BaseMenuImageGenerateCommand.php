<?php

namespace App\Console\Commands\MenuGenerate;

use App\Http\Resources\V1\MenuPartProductsUserShowResource;
use App\Http\Resources\V1\MenuPartUserShowResource;
use App\Models\V1\MenuPart;
use App\Models\V1\MenuSize;
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
        foreach ($menuSizes as $menuSize) {
            $menuParts = MenuPart::where('menu_size_id', $menuSize->id)->get();
            $resultMenuParts = MenuPartUserShowResource::collection($menuParts);
            if (count($menuParts)) {
                foreach ($langs as $lang) {
                    $this->info(json_encode($resultMenuParts[0]->menu_type->title[$lang]));

                    // $imagePath = storage_path('app/public/menu_images/' . $menuSize->id.'_'.$lang. '/base_menu.png');
                    // $htmlContent = view('menu_images.base_menu_template', ['menuParts' => $resultMenuParts , 'lang' => $lang])->render();
                    // $htmlFilePath = storage_path('app/public/image_html/base_menu.html');
                    // file_put_contents($htmlFilePath, $htmlContent);
                    // $command = "wkhtmltoimage {$htmlFilePath} {$imagePath}";
                    // shell_exec($command);
                }
            }
        }
    }
}
