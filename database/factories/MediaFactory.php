<?php

use Faker\Generator as Faker;
use Optimus\Media\Models\Media;

$factory->define(Media::class, function (Faker $faker) {
    return [
        'folder_id' => function () {
            return factory(Media::class)->create()->id;
        },
        'name' => $faker->word,
        'file_name' => "file.{$faker->fileExtension}",
        'disk' => config('media.disk'),
        'mime_type' => $faker->mimeType,
        'size' => $faker->randomNumber(4)
    ];
});
