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
        $fileName = 'asdf1.jpg';
        $data = [
            'folder_id' => $this->folder->id,
            'file' => UploadedFile::fake()->image($fileName),
        ];

        $response = $this->postJson(route('admin.media.store'), $data);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => $this->expectedMediaJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'folder_id' => $data['folder_id'],
                    'file_name' => $fileName
                ]
            ]);

        $this->assertNotNull($folder = Media::find(
            $response->decodeResponseJson('data.id')
        ));
    }

    /** @test */
    public function it_will_reject_invalid_folder()
    {
        $fileName = 'asdf1.jpg';
        $data = [
            'folder_id' => 9999,
            'file' => UploadedFile::fake()->image($fileName),
        ];
        $response = $this->postJson(route('admin.media.store'), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['folder_id']);
    }

    /** @test */
    public function it_will_reject_missing_file()
    {
        $data = [
            'folder_id' => $this->folder->id,
            'file' => null,
        ];
        $response = $this->postJson(route('admin.media.store'), $data);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }
}
