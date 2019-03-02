<?php

namespace Galahad\Medusa\Http\Controllers;

use Galahad\Medusa\Contracts\ContentType;
use Galahad\Medusa\Http\Middleware\Authorize;
use Galahad\Medusa\Http\Middleware\DispatchMedusaEvent;
use Galahad\Medusa\Serializers\ContentTypeSerializer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\HtmlString;

class FrontendController extends Controller
{
	public function __construct()
	{
		$this->middleware([
			DispatchMedusaEvent::class,
			Authorize::class,
		]);
	}
	
	public function __invoke(Request $request)
	{
		return view('medusa::frontend', [
			'config' => $this->config(),
			'script' => $this->script(),
		]);
	}
	
	protected function config() : HtmlString
	{
		$path = '/'.trim(config('medusa.path', 'medusa'), '/');
		
		$config = json_encode([
			'basepath' => "{$path}/web",
			'graphql_endpoint' => url("{$path}/graphql"),
		]);
		
		return new HtmlString("<script>window.__MEDUSA__ = {$config}</script>");
	}
	
	protected function script() : HtmlString
	{
		$hot = medusa()->basePath('resources/js/dist/hot');
		
		if (file_exists($hot) && app()->isLocal()) {
			$hmr_path = trim(file_get_contents($hot));
			return new HtmlString("<script crossorigin src=\"{$hmr_path}medusa.js\"></script>");
		}
		
		$script = file_get_contents(medusa()->basePath('resources/js/dist/medusa.js'));
		return new HtmlString("<script>{$script}</script>");
	}
}
