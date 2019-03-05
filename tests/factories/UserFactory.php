<?php

use Faker\Generator as Faker;
use Galahad\Medusa\Tests\Support\Models\User;

/* @var $factory Illuminate\Database\Eloquent\Factory */

$factory->define(User::class, function(Faker $faker) {
	return [
		'name' => $faker->name,
		'email' => $faker->email,
		'password' => bcrypt('123456'),
	];
});
