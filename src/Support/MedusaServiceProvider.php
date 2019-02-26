<?php

namespace Galahad\Medusa\Support;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Exceptions\ConfigurationException;
use Galahad\Medusa\Medusa;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class MedusaServiceProvider extends ServiceProvider
{
	public function boot() : void
	{
		require_once __DIR__.'helpers.php';
		
		$this->bootPermissions($this->app->make(Gate::class));
		$this->bootConfig();
	}
	
	/**
	 * @throws \Galahad\Medusa\Exceptions\ConfigurationException
	 */
	public function register() : void
	{
		$this->mergeConfigFrom($this->basePath('config/medusa.php'), 'medusa');
		
		$this->app->singleton('galahad.medusa', function(Application $app) {
			return new Medusa(
				$app->make(Dispatcher::class),
				$app['config']['medusa']
			);
		});
		
		$this->app->alias('galahad.medusa', Medusa::class);
		
		$content_model = $this->app->make(Repository::class)->get('medusa.content_model');
		$content_contract = Content::class;
		if (!is_subclass_of($content_model, $content_contract)) {
			throw new ConfigurationException("'{$content_model}' must implement '$content_contract'");
		}
		
		$this->app->alias($content_model, Content::class);
	}
	
	protected function bootPermissions(Gate $gate) : void
	{
		$gate->define('_viewMedusa', function() use ($gate) {
			return $gate->authorize('viewMedusa');
		});
	}
	
	protected function bootConfig() : self
	{
		if (method_exists($this->app, 'configPath')) {
			$this->publishes([
				$this->basePath('/config/medusa.php') => $this->app->configPath('medusa.php'),
			], 'medusa-config');
		}
		
		return $this;
	}
	
	protected function basePath(string $path = null) : string
	{
		$base_path = rtrim(dirname(__DIR__, 2), DIRECTORY_SEPARATOR);
		
		return null === $path
			? $base_path
			: $base_path.DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
	}
}
