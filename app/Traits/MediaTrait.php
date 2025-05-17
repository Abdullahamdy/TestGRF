<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait MediaTrait
{
    public static function getUploadPath($dir = null, $dateFolder = true)
    {
        $path = 'uploads';
        if ($dir) {
            $path .= '/' . trim($dir, '/');
        }
        if ($dateFolder) {
            $path .= '/' . Carbon::today()->format('Y-m');
        }

        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    public function uploadFile($fileData, $dir = null, $customFilename = null)
    {
        if ($fileData) {
            $file = $fileData;
            $fileName = $customFilename ?: hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $path = self::getUploadPath($dir);
            $filePath = $file->storeAs($path, $fileName, 'public');

            return Storage::url($filePath);
        }
        return null;
    }


    public function removeImage($imagePath)
    {
            $relativePath = str_replace('/storage/', '', $imagePath);
            $storagePath = "public/{$relativePath}";
            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
            }

    }



    public function uploadFileWithOriginalName($fileData, $dir = null)
{
    if ($fileData) {
        $file = $fileData;
        $fileName = $file->getClientOriginalName();
        $path = self::getUploadPath($dir);
        $filePath = $file->storeAs($path, $fileName, 'public');

        return Storage::url($filePath);
    }
    return null;
}

}
