<?php

namespace App\Console\Commands\MenuGenerate;

use Illuminate\Console\Command;

class TestMenuGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menuGenerateTest:baseMenuGenerate';

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
        $imageUrl = '/menu_images/test.png';
        $url = 'app/public' . $imageUrl;
        $imagePath = storage_path($url);
        $htmlFilePath = storage_path('app/public/image_html/base_menu.html');
        $command = "wkhtmltoimage --width 800  --quality 50 {$htmlFilePath} {$imagePath}";
        shell_exec($command);
    }
}
