<?php

namespace App\Console\Commands\MenuGenerate;

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
        $manager = new ImageManager(Driver::class);

        // create new image 640x480
        $image = $manager->create(640, 480);

        // create new image 512x512 with grey background
        $image = $manager->create(512, 512)->fill('ccc');
        $image->toPng()->save(public_path().'/images/foo.png');
        // $fileUrl = FileSave::storeFile('/menu/base/'.date('Y-m-d') , $image);
        // $this->info($fileUrl);
    }
}
