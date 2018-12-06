<?php

namespace Optimus\Media\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Optimus\Media\Models\MediaFolder;
use Optimus\Media\Http\Resources\FolderResource;

class FoldersController extends Controller
{
    public function index(Request $request)
    {
        $folders = MediaFolder::filter($request)->get();

        return FolderResource::collection($folders);
    }

    public function store(Request $request)
    {
        $this->validateFolder($request);

        $folder = MediaFolder::create($request->all());

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

    protected function validateFolder(Request $request, MediaFolder $folder = null)
    {
        $request->validate([
            'name' => $folder ? 'filled' : 'required',
            'parent_id' => [
                'nullable',
                Rule::exists('media_folders', 'id')
                    ->where(function ($query) use ($folder) {
                        $query->when($folder, function ($query) use ($folder) {
                            $query->where('id', '<>', $folder->id);
                        });
                    })
            ]
        ]);
    }
}
