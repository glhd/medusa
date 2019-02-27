<?php

namespace Galahad\Medusa\Support;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Contracts\ContentTypeResolver;
use Galahad\Medusa\Exceptions\ConfigurationException;
use Galahad\Medusa\Http\Controllers\ContentController;
use Galahad\Medusa\Medusa;
use Galahad\Medusa\Resolvers\ConventionalResolver;
use Galahad\Medusa\View\MedusaView;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class MedusaServiceProvider extends ServiceProvider
{
	public function boot() : void
	{
		require_once rtrim(__DIR__, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'helpers.php';
		
		$this->bootConfig()
			->bootPermissions($this->app->make(Gate::class))
			->bootRoutes($this->app->make(Registrar::class))
			->bootBlade($this->app->make(BladeCompiler::class))
			->bootViews()
			->bootMigrations();
	}
	
	/**
	 * @throws \Galahad\Medusa\Exceptions\ConfigurationException
	 */
	public function register() : void
	{
		$this->mergeConfigFrom($this->basePath('config/medusa.php'), 'medusa');
		
		$this->app->singleton('galahad.medusa.resolver', function(Application $app) {
			return new ConventionalResolver($app);
		});
		
		$this->app->alias('galahad.medusa.resolver', ContentTypeResolver::class);
		
		$this->app->singleton('galahad.medusa', function(Application $app) {
			return (new Medusa(
				$app->make(Dispatcher::class),
				$app['config']['medusa']
			))->setContentTypeResolver($app->make('galahad.medusa.resolver'));
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
	protected function bootPermissions(Gate $gate) : self
	{
		$gate->define('_viewMedusa', function() use ($gate) {
			if ($gate->has('viewMedusa')) {
				return $gate->authorize('viewMedusa');
			}
			
			return $this->app->isLocal();
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
	
	protected function bootBlade(BladeCompiler $compiler) : self
	{
		$compiler->directive('medusa', function ($expression) {
			$view_class = MedusaView::class;
			$factory_class = Factory::class;
			$base_path = $this->basePath();
			return "<?php echo (new {$view_class}(app('galahad.medusa.resolver'), app({$factory_class}::class), '{$base_path}', $expression)); ?>";
		});
		
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
				$this->basePath('migrations/') => $this->app->databasePath('migrations')
			], 'medusa-migrations');
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
