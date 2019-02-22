<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetMediaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_all_media()
    {
        $this->signIn();

        $response = $this->getJson(route('admin.media.index'));

        $response->assertOk();
    }
}
