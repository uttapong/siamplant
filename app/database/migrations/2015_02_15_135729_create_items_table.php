<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('items', function(Blueprint $table) {
			$table->increments('id');
			$table->string('itemname', 100);
			$table->integer('cat');
			$table->integer('price');
			$table->integer('amount');
			$table->integer('remaining');
			$table->integer('shippingprice');
			$table->string('detail', 400);
			$table->string('filelist', 300);
			$table->datetime('date_added');
			$table->datetime('last_updated');
			$table->integer('seller');
			$table->string('status', 10);
			$table->string('reserved_user', 500);
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
		Schema::drop('items');
	}

}
