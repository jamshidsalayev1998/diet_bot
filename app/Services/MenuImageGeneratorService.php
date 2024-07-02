<?php

namespace App\Services;

use App\Http\Resources\MenuRuleIndexResource;
use App\Http\Resources\V1\MenuPartUserShowResource;
use App\Models\V1\MenuPart;
use App\Models\V1\MenuRule;
use App\Models\V1\MenuType;
use Exception;
use Illuminate\Support\Facades\File;

class MenuImageGeneratorService
{
    public static function generateMenuImageForOneUser($userInfo)
    {
        $status = 0;
        $imageUrl = '';
        $error = [];
        $message = '';
        try {
            $menuTypes = MenuType::query()->orderBy('id', 'ASC')->get();
            $menuParts = MenuPart::where('menu_size_id', $userInfo->menu_size_id)->get();
            $resultMenuParts = MenuPartUserShowResource::collection($menuParts);
            $grouped = $resultMenuParts->groupBy('menu_type_id');
            $menuRules = MenuRuleIndexResource::collection(MenuRule::all());
            $menuSize = $userInfo->menu_size;
            $ready = [];
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
                $imageUrl = '/menu_images/' . date('Y-m-d') . '/' . $menuSize->calories . '/menu/' . RandomStringService::randomAlphaAndNumberHelper(15) . '/' . $userInfo->language . '.jpg';
                $url = 'app/public' . $imageUrl;
                $imagePath = storage_path($url);
                $directoryPath = dirname($imagePath);
                if(File::exists($imagePath)){
                    $message = 'file_exists';
                    File::delete($imagePath);
                }

                // Check if the directory exists, and create it if it doesn't
                if (!File::isDirectory($directoryPath)) {
                    File::makeDirectory($directoryPath, 0777, true, true);
                }
                $htmlContent = view('menu_images.base_menu_template', ['data' => $ready, 'lang' => $userInfo->language, 'user_info' => $userInfo, 'menu_rules' => $menuRules])->render();
                $htmlFilePath = storage_path('app/public/image_html/base_menu.html');
                file_put_contents($htmlFilePath, $htmlContent);
                $command = "wkhtmltoimage --width 1400  {$htmlFilePath} {$imagePath}";
                shell_exec($command);
                $userInfo->menu_image = $imageUrl;
                $userInfo->update();
            }
            $status = 1;
            // $message = 'success';
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
            'url' => $imageUrl
        ];
    }
    public static function generateMenuPartsImageForOneUser($userInfo)
    {
        $status = 0;
        $imageUrl = '';
        $error = [];
        $message = '';
        $menu_part_images = $userInfo->menu_part_images ? json_decode($userInfo->menu_part_images, true) : [];
        try {
            $menuTypes = MenuType::query()->orderBy('id', 'ASC')->get();
            foreach ($menuTypes as $menuType) {
                $menuParts = MenuPart::where('menu_size_id', $userInfo->menu_size_id)->where('menu_type_id', $menuType->id)->get();
                $resultMenuParts = MenuPartUserShowResource::collection($menuParts);
                $grouped = $resultMenuParts->groupBy('menu_type_id');
                $menuSize = $userInfo->menu_size;
                $ready = [];
                if (count($menuParts)) {
                    $ready[$menuType->id]['type_title'] = $menuType->title;
                    $ready[$menuType->id]['type_id'] = $menuType->id;
                    if (isset($grouped[$menuType->id])) {
                        $ready[$menuType->id]['records'] = $grouped[$menuType->id];
                    } else {
                        $ready[$menuType->id]['records'] = []; // or handle the missing key scenario appropriately
                    }
                    // $this->info(json_encode($ready[1]['records'][0]));
                    $imageUrl = '/menu_images/' . date('Y-m-d') . '/' . $menuSize->calories . '/menu_parts/'. RandomStringService::randomAlphaAndNumberHelper(15)  . $menuType->id . '/' . $userInfo->id . '/' . $userInfo->language . '.jpg';
                    $url = 'app/public' . $imageUrl;
                    $imagePath = storage_path($url);
                    $directoryPath = dirname($imagePath);

                    // Check if the directory exists, and create it if it doesn't
                    if (!File::isDirectory($directoryPath)) {
                        File::makeDirectory($directoryPath, 0777, true, true);
                    }
                    $htmlContent = view('menu_images.base_menu_parts_template', ['data' => $ready, 'lang' => $userInfo->language, 'user_info' => $userInfo])->render();
                    $htmlFilePath = storage_path('app/public/image_html/base_menu.html');
                    file_put_contents($htmlFilePath, $htmlContent);
                    $command = "wkhtmltoimage --width 800  {$htmlFilePath} {$imagePath}";
                    shell_exec($command);
                    $menu_part_images[$menuType->id] = $imageUrl;
                }
            }
            $userInfo->menu_part_images = json_encode($menu_part_images);
            $userInfo->update();
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
            'url' => $imageUrl
        ];
    }
}
