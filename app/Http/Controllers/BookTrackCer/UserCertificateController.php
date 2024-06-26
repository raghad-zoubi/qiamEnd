<?php

namespace App\Http\Controllers\BookTrackCer;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Services\ImageProcessingService;
use Illuminate\Support\Facades\Response;

class UserCertificateController extends Controller
{
    protected $imageProcessingService;

    public function __construct(ImageProcessingService $imageProcessingService)
    {        $this->middleware(["auth:sanctum"]);

        $this->imageProcessingService = $imageProcessingService;
    }

//
//    public function addTextToImage(Request $request)
//    {
//        // Validate the incoming request data
//        $request->validate([
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'text' => 'required|string|max:255',
//        ]);
//
//        // Get the uploaded image file and the text
//        $imageFile = $request->file('image');
//        $text = $request->input('text');
//
//        // Create an image resource from the uploaded file
//        $image = imagecreatefromstring(file_get_contents($imageFile));
//
//        // Define the text settings (e.g., font size, color, position)
//        $fontPath = public_path('fonts/Arabic.ttf'); // Path to the font file
//        $fontSize = 20; // Font size
//        $fontColor = imagecolorallocate($image, 255, 255, 255); // Font color (white)
//        $x = 10; // X position of the text
//        $y = 50; // Y position of the text
//
//        // Add the text to the image
//        imagettftext($image, $fontSize, 0, $x, $y, $fontColor, $fontPath, $text);
//
//        // Generate a unique filename for the output image
//        $filename = 'images/' . Str::random(10) . '.png';
//
//        // Save the image with the text to the public storage
//        imagepng($image, public_path($filename));
//
//        // Free up memory
//        imagedestroy($image);
//
//        return response()->json(['message' => 'Image saved successfully!', 'path' => $filename]);
//    }
//






    public function show($id)
    {
        // Retrieve image from database
        $image = Image::findOrFail($id);

        // Return the image as a response
        return response($image->image_data)->header('Content-Type', 'image/jpeg');
    }

    public function addText2(Request $request, $id)
    {
        // Retrieve image from database
        $image = Certificate::findOrFail($id);
        $imagePath = storage_path('app/' . $image->photo); // Adjust the path if necessary

        // Get text and position from request
        $text = $request->input('text');
        $x = $request->input('x', 0);
        $y = $request->input('y', 0);

        // Process image to add text
        $processedImage = $this->imageProcessingService->addTextToImage($imagePath, $text, $x, $y);

        // Return the processed image as a response
        return Response::make($processedImage, 200, ['Content-Type' => 'image/jpeg']);
    }






    public function addText(Request $request)
    {   //storage\app\public\photos
        // Get image path from storage
        $imagePath = storage_path('app/public/photos/example.jpg'); // Adjust the path as needed

        // Get text and position from request
        $text = $request->input('text');
        $x = $request->input('x', 0);
        $y = $request->input('y', 0);

        // Process image to add text
        $processedImage = $this->imageProcessingService->addTextToImage($imagePath, $text, $x, $y);

        // Return the processed image as a response
        return Response::make($processedImage, 200, ['Content-Type' => 'image/jpeg']);
    }


}
//
//composer dump-autoload
//php artisan clear-compiled
//php artisan optimize
//
//composer require fideloper/proxy:^4.4 intervention/image:^2.5
//composer update


//composer remove intervention/image
//composer require intervention/image

//   Route::get('/upload', function () {
//       return view('upload');
//   });
