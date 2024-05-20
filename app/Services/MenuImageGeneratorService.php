<?php

namespace App\Services;

use App\Http\Resources\V1\MenuPartUserShowResource;
use App\Models\V1\MenuPart;
use App\Models\V1\MenuType;
use Exception;
use Illuminate\Support\Facades\File;

class MenuImageGeneratorService
{
    public static function generateMenuImageForOneUser($userInfo)
    {
        $status = 0;
        $url = '';
        $error = [];
        $message = '';
        try {
            $menuTypes = MenuType::query()->orderBy('id', 'ASC')->get();
            $menuParts = MenuPart::where('menu_size_id', $userInfo->menu_size_id)->get();
            $resultMenuParts = MenuPartUserShowResource::collection($menuParts);
            $grouped = $resultMenuParts->groupBy('menu_type_id');
            $ready = [];
            if (count($menuParts)) {
                foreach ($menuTypes as $menuType) {
                    $ready[$menuType->id]['type_title'] = $menuType->title;
                    $ready[$menuType->id]['type_id'] = $menuType->id;
                    if (isset($grouped[$menuType->id])) {
                        $ready[$menuType->id]['records'] = $grouped[$menuType->id];
                    } else {
                        $ready[$menuType->id]['records'] = []; // or handle the missing key scenario appropriately
                    }
                }
                // $this->info(json_encode($ready[1]['records'][0]));
                $url = 'app/public/menu_images/'.date('Y-m-d').'/'. $userInfo->menu_size_id . '/' . $userInfo->id . '/'.$userInfo->language.'.png';
                $imagePath = storage_path($url);
                $directoryPath = dirname($imagePath);

                // Check if the directory exists, and create it if it doesn't
                if (!File::isDirectory($directoryPath)) {
                    File::makeDirectory($directoryPath, 0777, true, true);
                }
                $htmlContent = view('menu_images.base_menu_template', ['data' => $ready, 'lang' => $userInfo->language, 'user_info' => $userInfo])->render();
                $htmlFilePath = storage_path('app/public/image_html/base_menu.html');
                file_put_contents($htmlFilePath, $htmlContent);
                $command = "wkhtmltoimage {$htmlFilePath} {$imagePath}";
                shell_exec($command);
            }
            $status = 1;
            $message = 'success';

        } catch (Exception $e) {
            $message = 'error';
            $error = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ];
        }
        return [
            'status' => $status,
            'message' => $message,
            'error' => $error,
            'url' => $url
        ];
    }
}
