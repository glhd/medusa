<?php

namespace Galahad\Medusa\View;

use Galahad\Medusa\Contracts\Content;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\HtmlString;

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
	 * MedusaView constructor.
	 *
	 * @param \Illuminate\Contracts\View\Factory $view_factory
	 * @param string $base_path
	 * @param string|Content $content
	 */
	public function __construct(Factory $view_factory, string $base_path, $content = null)
	{
		// TODO: Handle content type as 3rd prop
		
		$this->view_factory = $view_factory;
		$this->base_path = $base_path;
		$this->content = $content;
	}
	
	public function toHtml() : string
	{
		$view_data = [
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
