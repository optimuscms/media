<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Models\Media;
use Optimus\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @property Media media
 */
class DeleteMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();

        $this->media = factory(Media::class)->create([
            'name' => 'name-1',
            'folder_id' => null,
        ]);
    }

    /** @test */
    public function it_can_delete_media()
    {
        $response = $this->deleteJson(
            route('admin.media.destroy', ['id' => $this->media->id])
        );

        $response->assertStatus(204);

        $this->assertDatabaseMissing($this->media->getTable(), [
            'id' => $this->media->id
        ]);
    }
}
