<?php

namespace Galahad\Medusa\Resolvers;

use Galahad\Medusa\Collections\ContentTypeCollection;
use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\ContentTypeResolver;
use Galahad\Medusa\Exceptions\ContentTypeNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class ConventionalResolver implements ContentTypeResolver
{
	/**
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * @param $name
	 * @return \Galahad\Medusa\Contracts\ContentType|null
	 * @throws \Galahad\Medusa\Exceptions\ContentTypeNotFoundException
	 */
	public function resolve($name) : ContentType
	{
		$class_name = $this->normalizeClassName($name);
		
		if (!class_exists($class_name) || !is_subclass_of($class_name, ContentType::class)) {
			throw (new ContentTypeNotFoundException("Cannot find Content Type '$name' (looked for '$class_name')"))
				->setRequestedContentType($class_name);
		}
		
		return $this->app->make($class_name);
	}
	
	public function exists($name) : bool
	{
		$class_name = $this->normalizeClassName($name);
		
		return class_exists($class_name)
			&& is_subclass_of($class_name, ContentType::class);
	}
	
	public function all() : ContentTypeCollection
	{
		return new ContentTypeCollection($this->findFiles());
	}
	
	protected function findFiles() : array
	{
		if (!is_dir($path = $this->app->path('ContentTypes'))) {
			return [];
		}
		
		return Collection::make((new Finder())->in($path)->files())
			->map(function(SplFileInfo $file) {
				$relative_path = Str::after($file->getPathname(), $this->app->path().DIRECTORY_SEPARATOR);
				$relative_class_name = str_replace(['/', '.php'], ['\\', ''], $relative_path);
				return $this->app->getNamespace().$relative_class_name;
			})
			->filter(function($class_name) {
				return is_subclass_of($class_name, ContentType::class);
			})
			->mapWithKeys(function($class_name) {
				$content_type = $this->app->make($class_name);
				return [$content_type->getId() => $content_type];
			})
			->toArray();
	}
	
	protected function normalizeClassName($name) : string
	{
		if (class_exists($name)) {
			return $name;
		}
		
		return $this->app->getNamespace().'ContentTypes\\'.Str::studly($name);
	}
}
