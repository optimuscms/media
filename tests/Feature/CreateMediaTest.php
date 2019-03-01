<?php

namespace Optimus\Media\Tests\Feature;

use Optimus\Media\Models\Media;
use Illuminate\Http\UploadedFile;
use Optimus\Media\Tests\TestCase;
use Optimus\Media\Models\MediaFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @property mixed folder
 */
class CreateMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();

        $this->folder = factory(MediaFolder::class)->create([
            'name' => 'A folder',
            'parent_id' => null,
        ]);

        config()->set('media.model', Media::class);
    }

    /** @test */
    public function it_can_create_media()
    {
        $response = $this->postJson(
            route('admin.media.store'),
            $data = $this->validData()
        );

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => $this->expectedMediaJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'folder_id' => $data['folder_id'],
                    'file_name' => 'asdf1.jpg'
                ]
            ]);

        $this->assertNotNull($folder = Media::find(
            $response->decodeResponseJson('data.id')
        ));
    }

    protected function validData($overrides = [])
    {
        return array_merge([
            'folder_id' => $this->folder->id,
            'file' => UploadedFile::fake()->image('asdf1.jpg'),
        ], $overrides);
    }
}
