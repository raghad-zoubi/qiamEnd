<?php

namespace App\Services;

class ImageProcessingService
{
//    public function addTextToImage($imagePath, $texts)
//    {
//        // Load the image
//        $image = imagecreatefromjpeg($imagePath);
//
//        // Check if the image loaded successfully
//        if (!$image) {
//            throw new \Exception('Failed to load image');
//        }
//
//        foreach ($texts as $index => $textSpec) {
//            $text = $this->correctArabicText($textSpec['text']);
//            $x = $textSpec['x'];
//            $y = $textSpec['y'] + ($index * $this->cmToPx(1)); // Add 1 cm spacing between lines
//            $size = $textSpec['size'];
//            $color = $textSpec['color'];
//            $font = $textSpec['font'];
//            $maxWidth = $textSpec['maxWidth']; // العرض الأقصى الممرر للنص
//
//            // Define the text color
//            $rgbColor = sscanf($color, "#%02x%02x%02x");
//            $allocatedColor = imagecolorallocate($image, $rgbColor[0], $rgbColor[1], $rgbColor[2]);
//
//            // Define the font file path
//            $fontPath = public_path('fonts/' . $font);
//
//            // Split text into lines that fit within maxWidth
//            $lines = $this->wrapText($text, $fontPath, $size, $maxWidth);
//
//            // Draw each line of the text
//            foreach ($lines as $line) {
//                // Calculate text bounding box to determine the width and height
//                $bbox = imagettfbbox($size, 0, $fontPath, $line);
//                $textHeight = abs($bbox[5] - $bbox[1]);
//
//                // Render the text on the image
//                imagettftext($image, $size, 0, $x, $y, $allocatedColor, $fontPath, $line);
//
//                // Move to the next line
//                $y += $textHeight + 10; // Adjust line height
//            }
//        }
//
//        // Capture the output
//        ob_start();
//        imagejpeg($image);
//        $imageData = ob_get_clean();
//
//        // Free memory
//        imagedestroy($image);
//
//        return $imageData;
//    }


    public function addTextToImage($imagePath, $texts)
    {
        // Load the image
        $image = imagecreatefromjpeg($imagePath);

        // Check if the image loaded successfully
        if (!$image) {
            throw new \Exception('Failed to load image');
        }

        foreach ($texts as $index => $textSpec) {
            $text = $this->correctArabicText($textSpec['text']);
            $size = $textSpec['size'];
            $color = $textSpec['color'];
            $font = $textSpec['font'];
            $maxWidth = $textSpec['maxWidth']; // العرض الأقصى الممرر للنص
            $x = $textSpec['x'];
            $y = $textSpec['y'] + ($index * $this->cmToPx(1)); // Add 1 cm spacing between lines

            // Define the text color
            $rgbColor = sscanf($color, "#%02x%02x%02x");
            $allocatedColor = imagecolorallocate($image, $rgbColor[0], $rgbColor[1], $rgbColor[2]);

            // Define the font file path
            $fontPath = public_path('fonts/' . $font);

            // Split text into lines that fit within maxWidth
            $lines = $this->wrapText($text, $fontPath, $size, $maxWidth);

            // Draw each line of the text
            foreach ($lines as $line) {
                // Calculate text bounding box to determine the width
                $bbox = imagettfbbox($size, 0, $fontPath, $line);
                $textWidth = abs($bbox[4] - $bbox[0]);
                $textHeight = abs($bbox[5] - $bbox[1]);

                // Adjust X position to start from the right and move to the left
                $xAdjusted = $x - $textWidth;

                // Render the text on the image
                imagettftext($image, $size, 0, $xAdjusted, $y, $allocatedColor, $fontPath, $line);

                // Move to the next line
                $y += $textHeight + 10; // Adjust line height
            }
        }

        // Capture the output
        ob_start();
        imagejpeg($image);
        $imageData = ob_get_clean();

        // Free memory
        imagedestroy($image);

        return $imageData;
    }
    private function wrapText($text, $fontPath, $fontSize, $maxWidth)
    {
        $wrappedText = [];
        $words = explode(' ', $text);
        $line = '';

        foreach ($words as $word) {
            $testLine = $line . $word . ' ';
            $bbox = imagettfbbox($fontSize, 0, $fontPath, $testLine);
            $lineWidth = abs($bbox[4] - $bbox[0]);

            if ($lineWidth > $maxWidth) {
                $wrappedText[] = trim($line);
                $line = $word . ' ';
            } else {
                $line = $testLine;
            }
        }

        $wrappedText[] = trim($line);

        return $wrappedText;
    }

    // Helper function to convert cm to pixels
    private function cmToPx($cm)
    {
        $dpi = 96; // Assuming 96 DPI (dots per inch)
        $inches = $cm * 0.393701; // Convert cm to inches
        return $inches * $dpi; // Convert inches to pixels
    }

    private function correctArabicText($text)
    {
        // Ensure the text is UTF-8 encoded
        if (mb_detect_encoding($text, 'UTF-8', true) === false) {
            $text = mb_convert_encoding($text, 'UTF-8');
        }

        // Reverse the text if necessary
        return $this->reverseText($text);
    }

    private function reverseText($text)
    {
        // Reverse the text for RTL languages
        return mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8');
    }
}
