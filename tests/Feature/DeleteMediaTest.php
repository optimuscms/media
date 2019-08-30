<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Models\Media;
use Optimus\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteMediaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_media()
    {
        $this->signIn();

        $media = factory(Media::class)->create([
            'name' => 'Name',
            'folder_id' => null,
        ]);

        $response = $this->deleteJson(
            route('admin.api.media.destroy', ['id' => $media->id])
        );

        $response->assertStatus(204);

        $this->assertDatabaseMissing($media->getTable(), [
            'id' => $media->id,
        ]);
    }
}
