<?php

namespace Optimus\Media\Tests\Unit;

use Mockery;
use Illuminate\Http\Request;
use Optimus\Media\Models\Media;
use Optimus\Media\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaTest extends TestCase
{
    protected $media;

    protected function setUp(): void
    {
        parent::setUp();

        $this->media = new Media();
    }

    /** @test */
    public function it_registers_the_folder_relationship()
    {
        $this->assertInstanceOf(
            BelongsTo::class, $this->media->folder()
        );
    }

    /** @test */
    public function it_registers_the_filter_scope()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('filled')->with('folder')->once()->andReturn(true);
        $request->shouldReceive('input')->with('folder')->andReturn($folderId = 1);

        $query = Mockery::mock(Builder::class);
        $query->shouldReceive('where')->with('folder_id', $folderId)->once()->andReturnSelf();

        $this->media->scopeFilter($query, $request);
    }
}
