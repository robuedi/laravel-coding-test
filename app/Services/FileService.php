<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Log;


class FileService 
{
    public function upload(UploadedFile $file, string $folder, string $disk = 'public')
    { 
        //make the upload
        $path = $file->store($folder, $disk);

        //save it to DB
        $fileRecord = File::create([
            'name'=> $file->getClientOriginalName(),
            'path'=> $path,
            'disk' => $disk
        ]);

        return $fileRecord;
    }

    public function downloadFile(File $file)
    {
        //check if file exists
        if (Storage::disk($file->disk)->exists($file->path)) {

            //return download response
            return Storage::disk($file->disk)->download($file->path);
        }

        //return 404 if not found
        abort('File not found.', 404);
    }

    public function removeFile(File $file)
    {
        //delete the physical file
        Storage::disk($file->disk)->delete($file->path);

        //delete the file record from the DB
        $file->delete();
    }
}
