<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Models\File;
use App\Http\Resources\FileResource;
use App\Services\FileService;
use Illuminate\Http\Response;

class FileController extends Controller
{
    public function __construct(
        private FileService $fileService
    )
    {
        $this->middleware('token-check')->only('store', 'destroy');
    }

    /**
     * Display a listing of the resource.
     * 
     * @unauthenticated
     */
    public function index()
    {
        return FileResource::collection(File::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        //upload the file
        $fileRecord = $this->fileService->upload(file: $request->file('file'), folder: 'uploads');

        return response()
            ->json(['data' => [
                'id' => $fileRecord->id,
            ]])
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     * 
     * @unauthenticated
     */
    public function show(File $file)
    {
        //download the file
        return $this->fileService->downloadFile(file: $file);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        //remove the file
        $this->fileService->removeFile(file: $file);

        return response([])->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
