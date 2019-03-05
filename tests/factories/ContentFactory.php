<?php

use Faker\Generator as Faker;
use Galahad\Medusa\Models\Content;
use Illuminate\Support\Str;

/* @var $factory Illuminate\Database\Eloquent\Factory */

$factory->define(Content::class, function(Faker $faker) {
	$description = $faker->realText(45);
	$slug = Str::slug($description);
	
	return [
		'content_type' => 'page',
		'slug' => $slug,
		'description' => $description,
		'data' => [
			'title' => $description,
			'slug' => $slug,
			'body' => $faker->realText(),
		],
		'unique_key' => str_random(),
		'published_at' => now(),
	];
});
