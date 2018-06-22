<?php

namespace Optimus\Media\Http\Controllers;

use Illuminate\Http\Request;
use Optimus\Media\MediaFolder;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Optimus\Media\Http\Resources\Folder as FolderResource;

class FoldersController extends Controller
{
    public function index()
    {
        $folders = MediaFolder::all();

        return FolderResource::collection($folders);
    }

    public function store(Request $request)
    {
        $this->validateFolder($request);

        $folder = MediaFolder::create([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id')
        ]);

        return new FolderResource($folder);
    }

    public function show($id)
    {
        $folder = MediaFolder::findOrFail($id);

        return new FolderResource($folder);
    }

    public function update(Request $request, $id)
    {
        $folder = MediaFolder::findOrFail($id);

        $this->validateFolder($request, $folder);

        $folder->update([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id')
        ]);

        return new FolderResource($folder);
    }

    public function delete($id)
    {
        $folder = MediaFolder::findOrFail($id);

        $folder->media->each->delete();

        $folder->delete();

        return response(null, 204);
    }

    protected function validateFolder(Request $request, MediaFolder $folder = null)
    {
        $request->validate([
            'name' => 'required',
            'parent_id' => [
                'nullable',
                Rule::exists('media_folders', 'id')->where(function ($query) use ($folder) {
                    $query->when($folder, function ($query) use ($folder) {
                        $query->where('id', '<>', $folder->id);
                    });
                })
            ]
        ]);
    }
}
