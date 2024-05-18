<?php

namespace App\Services;

class FileSave
{
    public static function storeFile($folder, $file)
    {
        $nameFile = date('Y-m-d').'_'.date('H-i-s').'_'.RandomStringService::randomAlphaAndNumberHelper(10);
        $extension = $file->getClientOriginalExtension();
        $nameFile.='.'.$extension;
        $file->move('files/'.$folder,$nameFile);
        return '/files/'.$folder.'/'.$nameFile;
    }
}
