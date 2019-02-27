<?php

namespace Galahad\Medusa\View;

use Galahad\Medusa\Contracts\Content;
use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Contracts\ContentTypeResolver;
use Galahad\Medusa\Support\Stitcher;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\HtmlString;
use InvalidArgumentException;

class MedusaView implements Htmlable
{
	/**
	 * @var string
	 */
	protected $base_path;
	
	/**
	 * @var \Illuminate\Contracts\View\Factory
	 */
	protected $view_factory;
	
	/**
	 * @var \Galahad\Medusa\Contracts\Content
	 */
	protected $content;
	
	/**
	 * @var \Galahad\Medusa\Contracts\ContentType
	 */
	protected $content_type;
	
	/**
	 * MedusaView constructor.
	 *
	 * @param \Galahad\Medusa\Contracts\ContentTypeResolver $resolver
	 * @param \Illuminate\Contracts\View\Factory $view_factory
	 * @param string $base_path
	 * @param string|Content $content
	 */
	public function __construct(ContentTypeResolver $resolver, Factory $view_factory, string $base_path, $content)
	{
		$this->view_factory = $view_factory;
		$this->base_path = $base_path;
		
		if ($content instanceof Content) {
			$this->content = $content;
			$this->content_type = $content->getContentType();
		} else if ($content instanceof ContentType) {
			$this->content_type = $content;
		} else if (is_string($content)) {
			$this->content_type = $resolver->resolve($content);
		}
		
		if (!($this->content_type instanceof ContentType)) {
			throw new InvalidArgumentException('You must pass a Content Type or a Content instance to '.__CLASS__);
		}
	}
	
	public function toHtml() : string
	{
		$view_data = [
			'content_type' => $this->content_type,
			'stitched' => new Stitcher($this->content_type),
			'content' => $this->content,
		];
		
		$hot = $this->base_path.'/resources/js/dist/hot';
		
		if (file_exists($hot)) {
			$hmr_path = trim(file_get_contents($hot));
			$view_data['inline'] = false;
			$view_data['src'] = $hmr_path.'medusa.js';
			$view_data['script'] = null;
		} else {
			$view_data['inline'] = true;
			$view_data['script'] = new HtmlString(file_get_contents($this->base_path.'/resources/js/dist/medusa.js'));
			$view_data['src'] = null;
		}
		
		return $this->view_factory->make('medusa::instance', $view_data)->render();
	}
	
	public function __toString()
	{
		return $this->toHtml();
	}
}
