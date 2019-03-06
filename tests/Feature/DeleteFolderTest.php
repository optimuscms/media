<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Tests\TestCase;
use Optimus\Media\Models\MediaFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteFolderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_a_folder()
    {
        $this->signIn();

        $folder = factory(MediaFolder::class)->create([
            'name' => 'Name',
            'parent_id' => null
        ]);

        $response = $this->deleteJson(
            route('admin.media-folders.destroy', ['id' => $folder->id])
        );

        $response->assertStatus(204);

        $this->assertDatabaseMissing($folder->getTable(), [
            'id' => $folder->id
        ]);
    }
}
