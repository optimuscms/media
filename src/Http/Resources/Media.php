<?php

namespace Optimus\Media\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Media extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            ''
        ];
    }
}
