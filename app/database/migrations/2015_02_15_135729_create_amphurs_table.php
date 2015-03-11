<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAmphursTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('amphurs', function(Blueprint $table) {
			$table->increments('id');
			$table->string('code', 4);
			$table->string('name', 150);
			$table->string('name_eng', 150);
			$table->integer('geo_id');
			$table->integer('province_id');
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
		Schema::drop('amphurs');
	}

}
