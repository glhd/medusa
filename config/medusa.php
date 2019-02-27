<?php

return [
	/*
	|--------------------------------------------------------------------------
	| Medusa Path
	|--------------------------------------------------------------------------
	|
	| This is the root path to serve Medusa from.
	|
	*/
	
	'path' => env('MEDUSA_PATH', '/medusa'),
	
	/*
	|--------------------------------------------------------------------------
	| Default Guard
	|--------------------------------------------------------------------------
	|
	| This is the guard that Medusa should use when resolving users and
	| checking permissions (defaults to the Laravel "web" guard).
	|
	*/
	
	'guard' => env('MEDUSA_GUARD', 'web'),
	
	/*
	|--------------------------------------------------------------------------
	| Default Middleware
	|--------------------------------------------------------------------------
	|
	| Middleware to pass Medusa requests through. Should at least use auth
	| middleware of some sort.
	|
	*/
	
	'middleware' => ['web'],
	
	/*
	|--------------------------------------------------------------------------
	| Default Content Model Configuration
	|--------------------------------------------------------------------------
	|
	| Here you may specify the model that represents Medusa content in your app.
	| This model must implement the Galahad\Medusa\Contracts\Content interface
	| If you are using the default Content model, you can also override the
	| tables that content are stored in.
	|
	*/
	
	'content_model' => 'Galahad\\Medusa\\Models\\Content',
	'content_table' => 'medusa_content',
];
