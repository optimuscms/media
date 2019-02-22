<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Tests\TestCase;

class GetMediaTest extends TestCase
{
    /** @test */
    public function it_can_display_all_media()
    {
        $response = $this->getJson(route('admin.media.index'));

        $response->assertOk();
    }
}
