<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuRuleIndexResource;
use App\Http\Resources\V1\MenuPartUserShowResource;
use App\Models\V1\MenuPart;
use App\Models\V1\MenuRule;
use App\Models\V1\MenuType;
use App\Models\V1\UserInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestMenuGenerateViewController extends Controller
{
    public function test()
    {
        $userInfo = UserInfo::first();
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
                    return  view('menu_images.base_menu_parts_template', ['data' => $ready, 'lang' => $userInfo->language, 'user_info' => $userInfo])->render();
                }
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}
