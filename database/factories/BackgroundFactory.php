<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Background;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

$factory->define(Background::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'image' => UploadedFile::fake()->image(Str::random(20).'.jpg', 300, 300),
    ];
});
