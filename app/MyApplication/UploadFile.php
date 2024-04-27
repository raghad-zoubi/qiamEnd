<?php

namespace App\MyApplication;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class UploadFile
{
    private array $disks;
    private ?string $fileName;
    private const DEFAULT_DISK = "files";

    public function __construct()
    {
        $this->disks = [
            "files" => "app/files",
            "images" => "app/images"
        ];
        $this->fileName = null;
    }

    /**
     * @param $file
     * @param string|null $diskType
     * @param string $dir
     * @return string
     */
    public function upload($file, string $diskType = null, string $dir = ""):string
    {
        $TempName = time().$file->getClientOriginalName();
        if(!is_null($diskType) && array_key_exists($diskType,$this->disks)){
            $TempName =  $file->storeAs($dir,$TempName,[
                "disk" => $diskType
            ]);
            $TempName = $this->disks[$diskType] ."/".$TempName;
        }else{
            $TempName = $file->storeAs($dir,$TempName,[
                "disk" => self::DEFAULT_DISK
            ]);
            $TempName = $this->disks[self::DEFAULT_DISK] ."/".$TempName;
        }
        $this->fileName = $TempName;
        return $TempName;
    }

    public function clearFile(){
        $this->fileName = null;
    }

    public function deleteFile(string $strdelete,string $photo,string $path): bool
    {
        $photo =str_replace($strdelete, '', $photo);
        if ($photo && Storage::disk('public')->exists($path . $photo)) {
            // Delete the file from the public directory
            Storage::disk('public')->delete($path . $photo);

            return true;
        }
        return false;
    }
//    public function rollBackUpload(){
//        if (!is_null($this->fileName)){
//            $this->DeleteFile($this->fileName);
//        }
//        $this->fileName = null;
//    }

    public function DownloadFile($path)
    {
        // dd(response()->file(storage_path($path)));
        // $headers = array('Content-Type'=>'application/octet-stream',);
        // dd(Response::download(storage_path($path,$headers)));
        $file = response()->download(storage_path($path));
        ob_end_clean();
        return $file;
    }
}
