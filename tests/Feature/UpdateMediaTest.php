<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Models\Media;
use Optimus\Media\Tests\TestCase;
use Optimus\Media\Models\MediaFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @property MediaFolder folder1
 * @property MediaFolder folder2
 * @property Media media
 * @property string name2
 */
class UpdateMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signIn();

        $this->folder1 = factory(MediaFolder::class)->create([
            'name' => 'folder-1',
            'parent_id' => null,
        ]);
        $this->folder2 = factory(MediaFolder::class)->create([
            'name' => 'folder-2',
            'parent_id' => null,
        ]);
        $this->media = factory(Media::class)->create([
            'name' => 'name-1',
            'folder_id' => $this->folder1->id,
        ]);
        $this->name2 = 'name-2';
    }

    /** @test */
    public function it_can_update_the_name_and_folder()
    {
        $response = $this->patchJson(
            route('admin.media.update', ['id' => $this->media->id]),
            [
                'name' => $this->name2,
                'folder_id' => $this->folder2->id,
            ]
        );

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedMediaJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'name' => $this->name2,
                    'folder_id' => $this->folder2->id,
                ]
            ]);
    }

    /** @test */
    public function it_can_set_folder_id_to_null()
    {
        $response = $this->patchJson(
            route('admin.media.update', ['id' => $this->media->id]),
            [
                'folder_id' => null,
            ]
        );

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedMediaJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'folder_id' => null,
                ]
            ]);
    }

    /** @test */
    public function it_will_reject_invalid_folder()
    {
        $response = $this->patchJson(
            route('admin.media.update', ['id' => $this->media->id]),
            [
                'folder_id' => 99999,
            ]
        );

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['folder_id']);
    }

    /** @test */
    public function it_will_make_sure_a_name_cant_be_removed()
    {
        $response1 = $this->patchJson(
            route('admin.media.update', ['id' => $this->media->id]),
            ['name' => '']
        );

        $response1->assertStatus(422)->assertJsonValidationErrors(['name']);

        $response2 = $this->patchJson(
            route('admin.media.update', ['id' => $this->media->id]),
            ['name' => null]
        );

        $response2->assertStatus(422)->assertJsonValidationErrors(['name']);
    }
}
