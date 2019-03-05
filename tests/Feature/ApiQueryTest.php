<?php

namespace Galahad\Medusa\Tests\Feature;

use Galahad\Medusa\Models\Content;
use Galahad\Medusa\Tests\DatabaseTestCase;
use Galahad\Medusa\Tests\Support\Models\User;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Gate;

class ApiQueryTest extends DatabaseTestCase
{
	/**
	 * @var User
	 */
	protected $user;
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->user = factory(User::class)->create();
		
		$permissions = ['viewMedusa', 'view', 'create', 'update', 'delete'];
		
		foreach ($permissions as $permission) {
			Gate::define($permission, function(User $user) {
				return $user->is($this->user);
			});
		}
	}
	
	public function test_view_medusa_ability() : void
	{
		$query = '{
			__schema {
				types {
					name
				}
			}
		}';
		
		$structure = [
			'data' => [
				'__schema' => [
					'types',
				],
			],
		];
		
		$this->standardAssertions($query, $structure);
	}
	
	public function test_view_ability() : void
	{
		$content = factory(Content::class)->create();
		
		$query = "query GetContent{
			getContent(id: \"$content->id\") {
				slug
				description
				data
			}
		}";
		
		$structure = [
			'data' => [
				'getContent' => [
					'slug',
					'description',
					'data',
				],
			],
		];
		
		$this->standardAssertions($query, $structure);
	}
	
	public function test_create_ability() : void
	{
		$content = factory(Content::class)->raw();
		$data = json_encode(json_encode($content['data']));
		
		$query = "mutation CreateContent{
			createContent(content_type_id: \"page\", data: {$data}) {
				slug
				description
				data
			}
		}";
		
		$structure = [
			'data' => [
				'createContent' => [
					'slug',
					'description',
					'data',
				],
			],
		];
		
		$this->standardAssertions($query, $structure)
			->assertJson([
				'data' => [
					'createContent' => [
						'slug' => $content['data']['slug'],
						'description' => $content['data']['title'],
					],
				],
			]);
	}
	
	public function test_update_ability() : void
	{
		$content = factory(Content::class)->create();
		
		$changes = factory(Content::class)->raw();
		$data = json_encode(json_encode($changes['data']));
		
		$query = "mutation CreateContent{
			updateContent(id: \"{$content->id}\", data: {$data}) {
				slug
				description
				data
			}
		}";
		
		$structure = [
			'data' => [
				'updateContent' => [
					'slug',
					'description',
					'data',
				],
			],
		];
		
		$this->standardAssertions($query, $structure)
			->assertJson([
				'data' => [
					'updateContent' => [
						'slug' => $content->slug,
						'description' => $changes['data']['title'],
					],
				],
			]);
	}
	
	protected function standardAssertions($query, $structure) : TestResponse
	{
		$this->query($query)
			->assertStatus(401);
		
		$this->login($this->non_admin)
			->query($query)
			->assertStatus(403);
		
		return $this->login($this->user)
			->query($query)
			->assertStatus(200)
			->assertJsonStructure($structure);
	}
}
