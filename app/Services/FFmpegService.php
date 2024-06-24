<?php

namespace App\Services;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Exception\RuntimeException;

class FFmpegService
{
    protected $ffmpeg;
    protected $ffprobe;

    public function __construct()
    {
        $this->ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => env('FFMPEG_PATH', 'C:\ffmpeg\bin\ffmpeg.exe'),
            'ffprobe.binaries' => env('FFPROBE_PATH', 'C:\ffmpeg\bin\ffprobe.exe'),
        ]);

        $this->ffprobe = FFProbe::create([
            'ffprobe.binaries' => env('FFPROBE_PATH', 'C:\ffmpeg\bin\ffprobe.exe'),
        ]);
    }

    public function convertVideo($inputPath, $outputPath)
    {
        try {
            $video = $this->ffmpeg->open($inputPath);
            $video->save(new \FFMpeg\Format\Video\X264(), $outputPath);

            return "Video converted successfully!";
        } catch (RuntimeException $e) {
            return "An error occurred: " . $e->getMessage();
        }
    }

    public function getVideoInfo($path)
    {
        return $this->ffprobe->streams($path)->videos()->first();
    }

    public function extractFrame($videoPath, $frameTime, $outputImagePath)
    {
        try {
            $video = $this->ffmpeg->open($videoPath);
            $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($frameTime));
            $frame->save($outputImagePath);

            return "Frame extracted successfully!";
        } catch (RuntimeException $e) {
            return "An error occurred: " . $e->getMessage();
        }
    }
}
