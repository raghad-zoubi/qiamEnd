<?php

namespace App\Http\Controllers\BookTrackCer;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Intervention\Image\Facades\Image;

class UserCertificateController extends Controller
{
//
//    public function destroy(UserCertificate $cer_Pat)
//    {
//
//// create image manager with desired driver
//        $manager = new ImageManager(new Driver());
//
//// read image from file system
//        $image = $manager->read('images/example.jpg');
//
//// resize image proportionally to 300px width
//        $image->scale( 300,0);
//
//// insert watermark
//$image->place('images/watermark.png');
//
//// save modified image in new format
//$image->toPng()->save('images/foo.png');
//    }

    public function generateCertificate()
    {
         // Retrieve data from the database
        $user = 'u';
        //User::find($userId);
        $photoPath =  Certificate::query()->where("id","=",6)->get("photo");
        $name ="user->name";
        $course = "user->course";
        $image = Image::make($photoPath);
        dd("g");

        $image->text($name, 100, 100);
        $image->text($course, 100, 120);

        //$user->photo =;
        dd( $image->encode('jpg'));// assuming the photo field is binary in the database
        //$user->save();
    }
// public function generateCertificate()
//    {
//        // Load the certificate template image
//        $certificateImage =
//            //Certificate::query()->where("id","=",6)->get('photo');
//
//        Image::make(public_path('path/to/blank_certificate.jpg'));
//
//        // Set path path
//        $fontPath = public_path('path/to/Arabic.ttf');
//// Debugging code to verify the path path
//
//        // Add course name
//        $certificateImage->text('Course: ' . "course->name", 100, 100, function($font) use ($fontPath) {
//        //    $font->file($fontPath);
//            $font->size(24);
//            $font->color('#000000');
//            $font->align('left');
//            $font->valign('top');
//        });
//     dd("ww");
//
//        // Add course logo
//        if (file_exists(public_path($course->logo_path))) {
//            $courseLogo = Image::make(public_path($course->logo_path));
//            $certificateImage->insert($courseLogo, 'top-left', 100, 150);
//        }
//
//        // Add supervisor name
//        $certificateImage->text('Supervisor: ' . "course->supervisor_name", 100, 300, function($font) use ($fontPath) {
//            $font->file($fontPath);
//            $font->size(24);
//            $font->color('#000000');
//            $font->align('left');
//            $font->valign('top');
//        });
//
//        // Add student name
//        $certificateImage->text('Student: ' ." student->name", 100, 350, function($font) use ($fontPath) {
//            $font->file($fontPath);
//            $font->size(24);
//            $font->color('#000000');
//            $font->align('left');
//            $font->valign('top');
//        });
//
//        // Add student grade
//        $certificateImage->text('Grade: ' . "student->pivot->grade", 100, 400, function($font) use ($fontPath) {
//            $font->file($fontPath);
//            $font->size(24);
//            $font->color('#000000');
//            $font->align('left');
//            $font->valign('top');
//        });
//
//        // Save the generated certificate
//        $outputPath = public_path('certificates/' . "student->id" . '_certificate.jpg');
//        $certificateImage->save($outputPath);
//
//        return $outputPath;
//    }

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
