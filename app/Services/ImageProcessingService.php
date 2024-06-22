<?php


namespace App\Services;


class ImageProcessingService
{
    public function addTextToImage($imagePath, $text, $x, $y)
    {
        // Load the image
        $image = imagecreatefromjpeg($imagePath);

        // Define the text color (white)
        $color = imagecolorallocate($image, 255, 255, 255);

        // Define the font file path
        $fontPath = storage_path('fonts/Arabica.ttf'); // Make sure you have the font file

        // Add the text to the image
        imagettftext($image, 24, 0, $x, $y, $color, $fontPath, $text);

        // Capture the output
        ob_start();
        imagejpeg($image);
        $imageData = ob_get_clean();

        // Free memory
        imagedestroy($image);

        return $imageData;
    }
}
