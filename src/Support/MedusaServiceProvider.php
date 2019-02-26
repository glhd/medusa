<?php

namespace Galahad\Medusa\Support;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Exceptions\ConfigurationException;
use Galahad\Medusa\Http\Controllers\ContentController;
use Galahad\Medusa\Http\Middleware\Authorize;
use Galahad\Medusa\Http\Middleware\DispatchMedusaEvent;
use Galahad\Medusa\Http\Middleware\ServeInterface;
use Galahad\Medusa\Medusa;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class MedusaServiceProvider extends ServiceProvider
{
	public function boot() : void
	{
		require_once rtrim(__DIR__, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'helpers.php';
		
		$this->bootConfig();
		
		$this->bootPermissions($this->app->make(Gate::class));
		$this->bootRoutes($this->app->make(Registrar::class));
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
		if (class_exists($content_model)) {
			$content_contract = Content::class;
			if (!is_a($content_model, $content_contract)) {
				throw new ConfigurationException("'{$content_model}' must implement '$content_contract'");
			}
			
			$this->app->alias($content_model, Content::class);
		}
	}
	
	/**
	 * Like Laravel Nova, Medusa allows access in local development by default
	 *
	 * @param \Illuminate\Contracts\Auth\Access\Gate $gate
	 */
	protected function bootPermissions(Gate $gate) : void
	{
		$gate->define('_viewMedusa', function() use ($gate) {
			if ($gate->has('viewMedusa')) {
				return $gate->authorize('viewMedusa');
			}
			
			return $this->app->isLocal();
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
	
	protected function bootRoutes(Registrar $registrar) : self
	{
		$group = [
			'middleware' => $this->config('middleware'),
			'prefix' => $this->config('path'),
			'as' => 'medusa.',
		];
		
		$registrar->group($group, function() use ($registrar) {
			$registrar->resource('content', ContentController::class);
		});
		
		return $this;
	}
	
	protected function basePath(string $path = null) : string
	{
		$base_path = rtrim(dirname(__DIR__, 2), DIRECTORY_SEPARATOR);
		
		return null === $path
			? $base_path
			: $base_path.DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
	}
	
	protected function config($key, $default = null)
	{
		return $this->app->make(Repository::class)->get("medusa.{$key}", $default);
	}
}
