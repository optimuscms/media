<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Tests\TestCase;
use Optimus\Media\Models\MediaFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @property MediaFolder folder
 */
class DeleteFolderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->folder = factory(MediaFolder::class)->create([
            'name' => 'Old name',
            'parent_id' => null,
        ]);

        $this->signIn();
    }

    /** @test */
    public function it_can_delete_a_folder()
    {
        $response = $this->deleteJson(
            route('admin.media-folders.destroy', ['id' => $this->folder->id])
        );

        $response->assertStatus(204);

        $this->assertDatabaseMissing($this->folder->getTable(), [
            'id' => $this->folder->id
        ]);
    }
}
