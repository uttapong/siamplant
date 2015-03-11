<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sessions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('created');
			$table->integer('last_visit');
			$table->string('last_ip', 200);
			$table->text('payload', 65535);
			$table->integer('last_activity');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sessions');
	}

}
