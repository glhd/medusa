<?php

namespace Galahad\Medusa\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeField extends Command
{
	protected $signature = 'medusa:make-field {name}';
	
	protected $description = 'Create a new Medusa field type';
	
	/**
	 * @var \Illuminate\Contracts\Filesystem\Filesystem
	 */
	protected $files;
	
	public function __construct(Filesystem $files)
	{
		parent::__construct();
		
		$this->files = $files;
	}
	
	public function handle()
	{
		$name = Str::studly($this->argument('name'));
		
		$this->files->put(app_path("Fields/{$name}.php"), $this->compilePhpStub($name));
		$this->files->put(app_path("resources/vendor/medusa/fields/{$name}.js"), $this->compileJsStub($name));
		
		$this->info("Created field '$name'");
	}
	
	protected function compilePhpStub($name)
	{
		$stub = $this->files->get(__DIR__.'/stubs/field.php.stub');
		
		return str_replace('{{name}}', $name, $stub);
	}
	
	protected function compileJsStub($name)
	{
		$stub = $this->files->get(__DIR__.'/stubs/field.js.stub');
		
		return str_replace('{{name}}', $name, $stub);
	}
}
