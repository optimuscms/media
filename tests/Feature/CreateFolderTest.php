<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Tests\TestCase;
use Optimus\Media\Models\MediaFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFolderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function it_can_create_a_folder()
    {
        $data = [
            'name' => 'New name',
            'parent_id' => null
        ];
        $response = $this->postJson(route('admin.media-folders.store'), $data);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => $this->expectedFolderJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'name' => $data['name'],
                    'parent_id' => $data['parent_id'],
                ]
            ]);

        $this->assertNotNull($folder = MediaFolder::find(
            $response->decodeResponseJson('data.id')
        ));
    }

    /** @test */
    public function it_will_reject_invalid_parent_folder()
    {
        $data = [
            'name' => 'New name',
            'parent_id' => 9999
        ];
        $response = $this->postJson(route('admin.media-folders.store'), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['parent_id']);
    }

    /** @test */
    public function it_will_reject_missing_folder_name()
    {
        $response1 = $this->postJson(route('admin.media-folders.store'), [
            'name' => '',
            'parent_id' => null
        ]);

        $response1
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        $response2 = $this->postJson(route('admin.media-folders.store'), [
            'name' => null,
            'parent_id' => null
        ]);

        $response2
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}
