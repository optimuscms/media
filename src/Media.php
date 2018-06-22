<?php

namespace Optimus\Media;

use Optix\Media\Media as BaseMedia;

class Media extends BaseMedia
{
    public function folder()
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }
}
