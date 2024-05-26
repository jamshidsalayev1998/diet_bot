<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HtmlToImageController extends Controller
{
    public function generateImageFromHtml(Request $request)
    {
        $htmlContent = '<html><body><h1>Hello, World!</h1><p>This is an example HTML content.</p></body></html>';

        // Save the HTML content to a temporary file
        $htmlFilePath = storage_path('app/public/temp.html');
        file_put_contents($htmlFilePath, $htmlContent);

        // Define the path for the output image
        $imagePath = storage_path('app/public/output.png');

        // Command to generate the image from HTML
        $command = "wkhtmltoimage {$htmlFilePath} {$imagePath}";

        // Execute the command
        shell_exec($command);

        // Return the path to the generated image
        return response()->download($imagePath);
    }
}
