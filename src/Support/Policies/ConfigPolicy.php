<?php

namespace Galahad\Medusa\Support\Policies;

use Galahad\Medusa\Contracts\Content;
use Illuminate\Contracts\Auth\Authenticatable;

class ConfigPolicy
{
	public function view(Authenticatable $user) : bool
	{
		return in_array($user->getAuthIdentifier(), config('medusa.admin_ids', []));
	}
	
	public function create(Authenticatable $user) : bool
	{
		return in_array($user->getAuthIdentifier(), config('medusa.admin_ids', []));
	}
	
	public function update(Authenticatable $user, Content $content) : bool
	{
		return in_array($user->getAuthIdentifier(), config('medusa.admin_ids', []));
	}
	
	public function delete(Authenticatable $user, Content $content) : bool
	{
		return in_array($user->getAuthIdentifier(), config('medusa.admin_ids', []));
	}
}
