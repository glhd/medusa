<?php

namespace Galahad\Medusa\Console\Commands;

use Galahad\Medusa\Schema;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use LogicException;
use Throwable;

class CacheSchema extends Command
{
	protected $signature = 'medusa:cache-schema';
	
	protected $description = 'Cache the Medusa GraphQL schema';
	
	/**
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;
	
	/**
	 * @var \Galahad\Medusa\Schema
	 */
	protected $loader;
	
	public function __construct(Filesystem $filesystem, Schema $loader)
	{
		parent::__construct();
		
		$this->files = $filesystem;
		$this->loader = $loader;
	}
	
	public function handle()
	{
		$this->call('medusa:clear-schema-cache');
		
		$path = Schema::cachePath();
		$schema = $this->loader->toArray();
		
		$this->files->makeDirectory(dirname($path), 0755, true);
		$this->files->put($path, '<?php return '.var_export($schema, true).';'.PHP_EOL);
		
		try {
			require $path;
		} catch (Throwable $e) {
			$this->files->delete($path);
			throw new LogicException('Your schema is not serializable.', 0, $e);
		}
		
		$this->info('Schema cached successfully!');
	}
}
