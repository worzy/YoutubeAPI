<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVideosTable extends Migration {

	public function up()
	{
		Schema::create('videos', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title', 100)->nullable();
			$table->datetime('date')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('videos');
	}
}