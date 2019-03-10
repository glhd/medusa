<?php

namespace Galahad\Medusa\Tests;

use Galahad\Medusa\Support\Facades\Medusa;
use Galahad\Medusa\Support\MedusaServiceProvider;
use Galahad\Medusa\Tests\Support\TestContentTypeRepository;
use Illuminate\Foundation\Testing\TestResponse;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
	protected function setUp()
	{
		parent::setUp();
		
		$this->app->instance('glhd.medusa.resolvers.content_type', new TestContentTypeRepository());
	}
	
	protected function getPackageProviders($app) : array
	{
		return [MedusaServiceProvider::class];
	}
	
	protected function getPackageAliases($app) : array
	{
		return [
			'Medusa' => Medusa::class,
		];
	}
	
	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('app.key', 'base64:ogrRLheNPge0On8KRJgoIeZ2y3BuubHKnsc37UHFVkA=');
		$app['config']->set('database.default', 'medusa');
		$app['config']->set('database.connections.medusa', [
			'driver' => 'sqlite',
			'database' => ':memory:',
			'prefix' => '',
		]);
	}
	
	protected function query(string $query, array $headers = []) : TestResponse
	{
		return $this->postJson(route('medusa.graphql'), compact('query'), $headers);
	}
}
