<?php

namespace Optimus\Media\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Folder extends Resource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'parent_id' => $this->parent_id
        ];
    }
}
