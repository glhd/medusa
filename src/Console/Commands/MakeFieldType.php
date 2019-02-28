<?php

namespace Galahad\Medusa\Console\Commands;

use Illuminate\Console\Command;

class MakeFieldType extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'make:field-type {name}';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new Medusa field type';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$name = $this->argument('name');
		
		file_put_contents(
			app_path('FieldTypes/'.$name.'.php'),
			$this->compilePhpStub($name)
		);
		file_put_contents(
			base_path('js/apps/cms/components/FieldTypes/'.$name.'.js'),
			$this->compileJsStub($name)
		);
		
		$this->info('Field type "'.$name.'" created.');
	}
	
	protected function compilePhpStub($name)
	{
		return str_replace(
			'{{name}}',
			$name,
			file_get_contents(__DIR__.'/stubs/FieldType/php.stub')
		);
	}
	
	protected function compileJsStub($name)
	{
		return str_replace(
			'{{name}}',
			$name,
			file_get_contents(__DIR__.'/stubs/FieldType/javascript.stub')
		);
	}
}
