<?php

namespace Optimus\Media\Http\Controllers;

use Illuminate\Http\Request;
use Optix\Media\MediaUploader;
use Optimus\Media\Models\Media;
use Illuminate\Routing\Controller;
use Optix\Media\Jobs\PerformConversions;
use Optimus\Media\Http\Resources\MediaResource;
use Optimus\Media\Http\Requests\StoreMediaRequest;
use Optimus\Media\Http\Requests\UpdateMediaRequest;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $media = Media::filter($request)->get();

        return MediaResource::collection($media);
    }

    public function store(StoreMediaRequest $request)
    {
        $media = MediaUploader::fromFile($request->file('file'))
            ->withAttributes($request->only('folder_id'))
            ->upload();

        if (starts_with($media->mime_type, 'image')) {
            PerformConversions::dispatch($media, ['400x300']);
        }

        return (new MediaResource($media))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $media = Media::findOrFail($id);

        return new MediaResource($media);
    }

    public function update(UpdateMediaRequest $request, $id)
    {
        $media = Media::findOrFail($id);

        $media->update($request->only([
            'folder_id',
            'name'
        ]));

        return new MediaResource($media);
    }

    public function destroy($id)
    {
        Media::findOrFail($id)->delete();

        return response(null, 204);
    }
}
