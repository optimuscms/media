<?php

namespace Optimus\Media\Tests\Unit;

use Mockery;
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
        $requestParams = ['folder' => 1];

        $query = Mockery::mock(Builder::class);

        $query->shouldReceive('where')->with('folder_id', $requestParams['folder'])
            ->once()
            ->andReturnSelf();

        $this->media->scopeApplyFilters($query, $requestParams);
    }
}
