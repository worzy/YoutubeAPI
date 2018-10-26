<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChannelsTable extends Migration {

	public function up()
	{
		Schema::create('channels', function(Blueprint $table) {
			$table->increments('id');
			$table->string('channel_name', 45)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('channels');
	}
}