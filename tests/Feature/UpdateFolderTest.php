<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Tests\TestCase;
use Optimus\Media\Models\MediaFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @property MediaFolder folder
 */
class UpdateFolderTest extends TestCase
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
    public function it_can_update_a_folder()
    {
        $response = $this->patchJson(
            route('admin.media-folders.update', ['id' => $this->folder->id]),
            $newData = $this->validData()
        );

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedFolderJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'name' => $newData['name'],
                    'parent_id' => $newData['parent_id'],
                ]
            ]);
    }

    protected function validData($overrides = [])
    {
        return array_merge([
            'name' => 'New name',
            'parent_id' => null
        ], $overrides);
    }
}
