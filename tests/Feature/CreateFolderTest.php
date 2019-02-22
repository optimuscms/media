<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Tests\TestCase;
use Optimus\Media\Models\MediaFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFolderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function it_can_create_a_folder()
    {
        $response = $this->postJson(
            route('admin.media-folders.store'),
            $data = $this->validData()
        );

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

    protected function validData($overrides = [])
    {
        return array_merge([
            'name' => 'New name',
            'parent_id' => null
        ], $overrides);
    }
}
