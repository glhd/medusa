<?php

namespace Galahad\Medusa\Support;

use Galahad\Medusa\Console\Commands\CacheSchema;
use Galahad\Medusa\Console\Commands\ClearSchemaCache;
use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Contracts\ContentRepository;
use Galahad\Medusa\Contracts\ContentTypeRepository;
use Galahad\Medusa\Events\ServingMedusa;
use Galahad\Medusa\Exceptions\ConfigurationException;
use Galahad\Medusa\Http\Controllers\ApiController;
use Galahad\Medusa\Http\Controllers\FrontendController;
use Galahad\Medusa\Medusa;
use Galahad\Medusa\Repositories\Content\EloquentRepository;
use Galahad\Medusa\Repositories\ContentType\ConventionalRepository;
use Galahad\Medusa\Schema;
use Galahad\Medusa\Support\Policies\ConfigPolicy;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MedusaServiceProvider extends ServiceProvider
{
	public function boot() : void
	{
		require_once rtrim(__DIR__, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'helpers.php';
		
		$this->bootConfig()
			->bootPermissions($this->app->make(Gate::class), $this->app->make(Dispatcher::class))
			->bootRoutes($this->app->make(Registrar::class))
			->bootViews()
			->bootMigrations()
			->bootPublic()
			->bootCommands();
	}
	
	/**
	 * @throws \Galahad\Medusa\Exceptions\ConfigurationException
	 */
	public function register() : void
	{
		$this->mergeConfigFrom($this->basePath('config/medusa.php'), 'medusa');
		
		$this->app->singleton(Schema::class);
		
		$this->app->singleton('glhd.medusa.resolvers.content_type', function(Application $app) {
			return new ConventionalRepository($app);
		});
		
		$this->app->alias('glhd.medusa.resolvers.content_type', ContentTypeRepository::class);
		
		$this->app->singleton('glhd.medusa.resolvers.content', function(Application $app) {
			return new EloquentRepository($app->make(Content::class));
		});
		
		$this->app->alias('glhd.medusa.resolvers.content', ContentRepository::class);
		
		$this->app->singleton('galahad.medusa', function(Application $app) {
			return (new Medusa(
				$app->make(Dispatcher::class),
				$app['config']['medusa']
			))->setContentTypeResolver($app->make('glhd.medusa.resolvers.content_type'));
		});
		
		$this->app->alias('galahad.medusa', Medusa::class);
		
		$content_model = $this->app->make(Repository::class)->get('medusa.content_model');
		if (class_exists($content_model)) {
			$content_contract = Content::class;
			if (!is_subclass_of($content_model, $content_contract)) {
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
	protected function bootPermissions(Gate $gate, Dispatcher $dispatcher) : self
	{
		$gate->define('_viewMedusa', function() use ($gate) {
			if ($gate->has('viewMedusa')) {
				return $gate->authorize('viewMedusa');
			}
			
			return $this->app->isLocal() || in_array(Auth::id(), config('medusa.admin_ids', []));
		});
		
		$dispatcher->listen(ServingMedusa::class, function() use ($gate) {
			if (null === $gate->getPolicyFor(Content::class)) {
				$gate->policy(Content::class, ConfigPolicy::class);
			}
		});
		
		return $this;
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
		$path = $this->config('path');
		
		if ($registrar instanceof Router) {
			$registrar
				->redirect($path, "{$path}/web")
				->middleware($this->config('middleware'));
		}
		
		$registrar
			->get("{$path}/web/{any?}", FrontendController::class)
			->name('medusa.frontend')
			->where('any', '.*');
		
		$registrar
			->any("{$path}/graphql", ApiController::class)
			->middleware($this->config('middleware'));
		
		return $this;
	}
	
	protected function bootViews() : self
	{
		$path = $this->basePath('resources/views');
		
		$this->loadViewsFrom($path, 'medusa');
		
		if (method_exists($this->app, 'resourcePath')) {
			$this->publishes([
				$path => $this->app->resourcePath('views/vendor/medusa'),
			], 'medusa-views');
		}
		
		return $this;
	}
	
	protected function bootMigrations() : self
	{
		if (method_exists($this->app, 'databasePath')) {
			$this->publishes([
				$this->basePath('migrations/') => $this->app->databasePath('migrations'),
			], 'medusa-migrations');
		}
		
		return $this;
	}
	
	protected function bootPublic() : self
	{
		if (method_exists($this->app, 'publicPath')) {
			$this->publishes([
				$this->basePath('resources/public') => $this->app->publicPath('vendor/medusa'),
			], 'medusa-public');
		}
		
		return $this;
	}
	
	protected function bootCommands() : self
	{
		if ($this->app->runningInConsole()) {
			$this->commands([
				CacheSchema::class,
				ClearSchemaCache::class,
			]);
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
	
	protected function config($key, $default = null)
	{
		return $this->app->make(Repository::class)->get("medusa.{$key}", $default);
	}
}
