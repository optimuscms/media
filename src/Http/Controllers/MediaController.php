<?php

namespace Optimus\Media\Http\Controllers;

use Optix\Media\Media;
use Illuminate\Http\Request;
use Optix\Media\MediaUploader;
use Illuminate\Routing\Controller;
use Optimus\Media\Http\Resources\Media as MediaResource;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::all();

        return MediaResource::collection($media);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'file|max:' . config('media.max_file_size'),
            'folder_id' => 'exists:media_folders,id|nullable'
        ]);

        $media = MediaUploader::fromFile($request->file('file'))
            ->withAttributes(['folder_id' => $request->input('folder_id')])
            ->upload();

        return new MediaResource($media);
    }

    public function show($id)
    {
        $media = Media::findOrFail($id);

        return new MediaResource($media);
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        $request->validate([
            'name' => 'required'
        ]);

        $media->name = $request->input('name');
        $media->save();

        return new MediaResource($media);
    }

    public function delete($id)
    {
        Media::findOrFail($id)->delete();

        return response(null, 204);
    }
}
