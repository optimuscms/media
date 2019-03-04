<?php

namespace Optimus\Media\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Optimus\Media\Models\MediaFolder;
use Optimus\Media\Http\Resources\FolderResource;
use Optimus\Media\Http\Requests\StoreFolderRequest;
use Optimus\Media\Http\Requests\UpdateFolderRequest;

class FoldersController extends Controller
{
    public function index(Request $request)
    {
        $folders = MediaFolder::filter($request)->get();

        return FolderResource::collection($folders);
    }

    public function store(StoreFolderRequest $request)
    {
        $folder = MediaFolder::create($request->all());

        return new FolderResource($folder);
    }

    public function show($id)
    {
        $folder = MediaFolder::findOrFail($id);

        return new FolderResource($folder);
    }

    public function update(UpdateFolderRequest $request, $id)
    {
        $folder = MediaFolder::findOrFail($id);

        $folder->update($request->all());

        return new FolderResource($folder);
    }

    public function destroy($id)
    {
        $folder = MediaFolder::findOrFail($id);

        $folder->media->each->delete();

        $folder->delete();

        return response(null, 204);
    }
}
