<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Tests\TestCase;
use Optimus\Media\Models\MediaFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFolderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function it_can_display_all_folders()
    {
        factory(MediaFolder::class, 3)->create();

        $response = $this->getJson(
            route('admin.media-folders.index')
        );

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->expectedFolderJsonStructure()
                ]
            ]);
    }

    /** @test */
    public function it_can_display_a_specific_folder()
    {
        $folder = factory(MediaFolder::class)->create();

        $response = $this->getJson(
            route('admin.media-folders.show', ['id' => $folder->id])
        );

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedFolderJsonStructure()
            ])
            ->assertJson(
                [
                    'data' => [
                        'id' => $folder->id,
                        'name' => $folder->name,
                        'parent_id' => $folder->parent_id,
                        'created_at' => $folder->created_at,
                        'updated_at' => $folder->updated_at
                    ]
                ]
            );
    }
}
