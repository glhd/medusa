<?php

namespace Galahad\Medusa\Console\Commands;

use Galahad\Medusa\Schema;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearSchemaCache extends Command
{
	protected $signature = 'medusa:clear-schema-cache';
	
	protected $description = 'Clear the Medusa GraphQL schema cache';
	
	/**
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;
	
	public function __construct(Filesystem $filesystem)
	{
		parent::__construct();
		
		$this->files = $filesystem;
	}
	
	public function handle()
	{
		$this->files->delete(Schema::cachePath());
		
		$this->info('Schema cached cleared!');
	}
}
