<?php

namespace Optimus\Media;

use Illuminate\Database\Eloquent\Model;

class MediaFolder extends Model
{
    protected $fillable = [
        'name', 'parent_id'
    ];

    public function media()
    {
        return $this->hasMany(Media::class, 'folder_id');
    }
}
