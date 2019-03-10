<?php

namespace Galahad\Medusa\Http\Controllers;

use Galahad\Medusa\Http\Middleware\Authenticate;
use Galahad\Medusa\Http\Middleware\Authorize;
use Galahad\Medusa\Http\Middleware\DispatchMedusaEvent;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\HtmlString;

class FrontendController extends Controller
{
	public function __construct()
	{
		$this->middleware(array_merge(
			[DispatchMedusaEvent::class],
			config('medusa.middleware', []),
			[Authenticate::class, Authorize::class]
		));
	}
	
	public function __invoke(Request $request)
	{
		return view('medusa::frontend', [
			'config' => $this->config(),
			'script' => $this->script(),
			'styles' => $this->styles(),
		]);
	}
	
	protected function config() : HtmlString
	{
		$path = '/'.trim(config('medusa.path', 'medusa'), '/');
		
		$config = json_encode([
			'name' => config('medusa.name', 'Medusa'),
			'basepath' => "{$path}/web",
			'graphql_endpoint' => url("{$path}/graphql"),
			'csrf_token' => session()->token(),
			'env' => app()->environment(),
		]);
		
		return new HtmlString("<script>window.__MEDUSA__ = {$config}</script>");
	}
	
	protected function script() : HtmlString
	{
		if ($hmr_path = $this->hmr()) {
			return new HtmlString("<script crossorigin src=\"{$hmr_path}medusa.js\"></script>");
		}
		
		$script = file_get_contents(medusa()->basePath('resources/public/medusa.js'));
		return new HtmlString("<script>{$script}</script>");
	}
	
	protected function styles() : HtmlString
	{
		if ($this->hmr()) {
			return new HtmlString('');
		}
		
		$style = file_get_contents(medusa()->basePath('resources/public/medusa.css'));
		return new HtmlString("<style>{$style}</style>");
	}
	
	protected function hmr() : ?string
	{
		$hot = medusa()->basePath('resources/public/hot');
		
		if (file_exists($hot) && app()->isLocal()) {
			return trim(file_get_contents($hot));
		}
		
		return false;
	}
}
