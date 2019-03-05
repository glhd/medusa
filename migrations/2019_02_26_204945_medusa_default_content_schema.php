<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MedusaDefaultContentSchema extends Migration
{
	/**
	 * Run migrations
	 */
	public function up()
	{
		$table = config('medusa.content_table', 'medusa_content');
		
		Schema::create($table, function(Blueprint $table) {
			$table->bigIncrements('id');
			
			$table->string('content_type')->index();
			$table->string('slug')->index();
			$table->string('description');
			$table->text('data');
			
			$table->string('unique_key')->nullable();
			
			$table->timestamp('published_at')->index()->nullable();
			$table->timestamps();
			$table->softDeletes();
			
			$table->unique(['content_type', 'slug']);
		});
		
		// Schema::table("{$table}_dependencies", function(Blueprint $table) {
		// 	$table->bigIncrements('id');
		// 	$table->unsignedBigInteger('content_id');
		// 	$table->unsignedBigInteger('depends_on_content_id');
		// 	$table->timestamps();
		//
		// 	$table->foreign('content_id')
		// 		->references('id')
		// 		->on($table)
		// 		->onUpdate('cascade')
		// 		->onDelete('cascade');
		//
		// 	$table->foreign('depends_on_content_id')
		// 		->references('id')
		// 		->on($table)
		// 		->onUpdate('cascade')
		// 		->onDelete('cascade');
		// });
	}
	
	/**
	 * Reverse migrations
	 */
	public function down()
	{
		Schema::dropIfExists(config('medusa.content_table', 'medusa_content'));
	}
}
