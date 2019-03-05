<?php

namespace Galahad\Medusa\Tests;

use Galahad\Medusa\Tests\Support\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseTestCase extends TestCase
{
	use RefreshDatabase;
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->loadLaravelMigrations(['--database' => 'medusa']);
		$this->loadMigrationsFrom(realpath(__DIR__.'/../migrations'));
		$this->artisan('migrate', ['--database' => 'medusa']);
		
		$this->withFactories(__DIR__.'/factories');
	}
	
	protected function login(User $user = null) : self
	{
		return $this->actingAs($user ?? factory(User::class)->create());
	}
}
