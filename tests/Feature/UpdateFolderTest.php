<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Tests\TestCase;
use Optimus\Media\Models\MediaFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @property MediaFolder folder1
 * @property MediaFolder folder2
 */
class UpdateFolderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->folder1 = factory(MediaFolder::class)->create([
            'name' => 'Folder 1',
            'parent_id' => null,
        ]);
        $this->folder2 = factory(MediaFolder::class)->create([
            'name' => 'Folder 2',
            'parent_id' => null,
        ]);

        $this->signIn();
    }

    /** @test */
    public function it_can_update_a_folder_name()
    {
        $newData = [
            'name' => 'New name',
            'parent_id' => null
        ];
        $response = $this->patchJson(
            route('admin.media-folders.update', ['id' => $this->folder1->id]),
            $newData
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

    /** @test */
    public function it_will_reject_invalid_parent_folder()
    {
        $newData = [
            'name' => 'New name',
            'parent_id' => 9999
        ];
        $response = $this->patchJson(
            route('admin.media-folders.update', ['id' => $this->folder1->id]),
            $newData
        );

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['parent_id']);
    }

    /** @test */
    public function it_will_reject_parent_equal_to_itself()
    {
        $newData = [
            'parent_id' => $this->folder1->id
        ];
        $response = $this->patchJson(
            route('admin.media-folders.update', ['id' => $this->folder1->id]),
            $newData
        );

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['parent_id']);
    }

    /** @test */
    public function it_will_reject_missing_folder_name()
    {
        $response1 = $this->patchJson(
            route('admin.media-folders.update', ['id' => $this->folder1->id]),
            [
                'name' => '',
                'parent_id' => null
            ]);

        $response1
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        $response2 = $this->patchJson(
            route('admin.media-folders.update', ['id' => $this->folder1->id]),
            [
                'name' => null,
                'parent_id' => null
            ]);

        $response2
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}
