<?php

return [
	/*
	|--------------------------------------------------------------------------
	| Medusa Name
	|--------------------------------------------------------------------------
	|
	| This is the root path to serve Medusa from.
	|
	*/
	
	'name' => env('MEDUSA_NAME', 'Medusa'),
	
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
	| Middleware to pass Medusa requests through. Medusa will automatically
	| apply authentication middleware using the configured guard.
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
	| tables that content is stored in.
	|
	*/
	
	'content_model' => 'Galahad\\Medusa\\Models\\Content',
	'content_table' => 'medusa_content',
	
	/*
	|--------------------------------------------------------------------------
	| Authorization
	|--------------------------------------------------------------------------
	|
	| Typically, you will want to register a Policy for access to medusa content.
	| But, to get things started quickly, you can add a list of users that should
	| have full admin access here.
	|
	*/
	
	'admin_ids' => [],
];
