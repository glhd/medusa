<?php

namespace Galahad\Medusa;

use Galahad\Medusa\Resolvers\DefaultResolver;
use GraphQL\Language\AST\DocumentNode;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Schema as SchemaType;
use GraphQL\Utils\BuildSchema;
use GraphQL\Language\Parser;
use Illuminate\Contracts\Support\Arrayable;
use GraphQL\Utils\AST;
use Throwable;

class Schema implements Arrayable
{
	/**
	 * @var SchemaType
	 */
	protected $schema;
	
	public static function cachePath() : string
	{
		return env('MEDUSA_SCHEMA_CACHE', app()->storagePath().'/medusa/schema.php');
	}
	
	public function schema() : SchemaType
	{
		if (null === $this->schema) {
			$document = $this->loadFromCache() ?? $this->buildFromSource();
			$this->schema = BuildSchema::build($document, [$this, 'decorateType']);
		}
		
		return $this->schema;
	}
	
	public function toArray() : array
	{
		return AST::toArray($this->buildFromSource());
	}
	
	public function decorateType(array $typeConfig) : array
	{
		$typeConfig['resolveField'] = function($value, $args, $context, ResolveInfo $info) use ($typeConfig) {
			$class_name = DefaultResolver::findResolver($typeConfig['name']);
			$resolver = new $class_name($value, $args, $context, $info);
			return $resolver();
		};
		
		return $typeConfig;
	}
	
	protected function loadFromCache() : ?DocumentNode
	{
		$filename = static::cachePath();
		
		if (!is_readable($filename)) {
			return null;
		}
		
		try {
			$node = AST::fromArray(require $filename);
			return $node instanceof DocumentNode ? $node : null;
		} catch (Throwable $e) {
			return null;
		}
	}
	
	protected function buildFromSource() : DocumentNode
	{
		$contents = file_get_contents(medusa()->basePath('resources/schema.graphql'));
		return Parser::parse($contents);
	}
}
