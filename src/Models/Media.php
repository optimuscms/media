<?php

namespace Optimus\Media\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Optix\Media\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    protected $fillable = [
        'folder_id', 'name', 'file_name', 'disk', 'mime_type', 'size'
    ];

    public function folder()
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    public function scopeFilter(Builder $query, Request $request)
    {
        if ($request->filled('folder')) {
            $folder = $request->query('folder');
            $query->where('folder_id', $folder === 'root' ? null : $folder);
        }
    }
}
