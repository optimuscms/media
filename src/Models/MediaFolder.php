<?php

namespace Optimus\Media\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MediaFolder extends Model
{
    protected $fillable = [
        'name', 'parent_id'
    ];

    public function scopeFilter(Builder $query, Request $request)
    {
        if ($request->filled('parent')) {
            $parent = $request->input('parent');
            $query->where('parent_id', $parent === 'root' ? null : $parent);
        }
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'folder_id');
    }
}
