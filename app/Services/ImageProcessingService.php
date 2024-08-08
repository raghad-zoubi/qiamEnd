<?php

namespace App\Services;


class ImageProcessingService
{
    public function addTextToImage($imagePath, $texts)
    {
        // Load the image
        $image = imagecreatefromjpeg($imagePath);

        // Check if image loaded successfully
        if (!$image) {
            throw new \Exception('Failed to load image');
        }

        foreach ($texts as $textSpec) {
            // Extract text properties
            $text = $textSpec['text'];
            $x = $textSpec['x'];
            $y = $textSpec['y'];
            $size = $textSpec['size'];
            $color = $textSpec['color'];
            $font = $textSpec['font'];

            // Define the text color
            $rgbColor = sscanf($color, "#%02x%02x%02x");
            $allocatedColor = imagecolorallocate($image, $rgbColor[0], $rgbColor[1], $rgbColor[2]);

            // Define the font file path
            $fontPath = public_path('fonts/' . $font); // Ensure font files are correctly placed in the public/fonts directory

            // Check if font file exists
            if (!file_exists($fontPath)) {
                throw new \Exception('Font file not found at ' . $fontPath);
            }

            // Calculate the bounding box of the text
            $bbox = imagettfbbox($size, 0, $fontPath, $this->correctArabicText($text));

            // Adjust the position to start from the right bottom corner
            $textWidth = $bbox[2] - $bbox[0];
            $textHeight = $bbox[1] - $bbox[7];
            $x -= $textWidth;
            $y -= $textHeight;

            // Add the text to the image, ensuring to set the correct encoding for Arabic
            imagettftext($image, $size, 0, $x, $y, $allocatedColor, $fontPath, $this->correctArabicText($text));
        }

        // Capture the output
        ob_start();
        imagejpeg($image);
        $imageData = ob_get_clean();

        // Free memory
        imagedestroy($image);

        return $imageData;
    }

    private function correctArabicText($text)
    {
        // Convert the text encoding to UTF-8 if necessary
        if (mb_detect_encoding($text, 'UTF-8', true) === false) {
            $text = mb_convert_encoding($text, 'UTF-8');
        }

        // Reverse the text direction if necessary
        return $this->reverseText($text);
    }

    private function reverseText($text)
    {
        $reversedText = '';
        $length = mb_strlen($text, 'UTF-8');
        while ($length-- > 0) {
            $reversedText .= mb_substr($text, $length, 1, 'UTF-8');
        }
        return $reversedText;
    }
}
