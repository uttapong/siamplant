<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('username', 30);
			$table->string('displayname', 30);
			$table->string('password', 64);
			$table->string('firstname', 20);
			$table->string('lastname', 20)->nullable();
			$table->string('email', 50)->nullable();
			$table->string('address', 500)->nullable();
			$table->integer('province')->nullable();
			$table->integer('district')->nullable();
			$table->string('phone', 20)->nullable();
			$table->string('mobile', 20)->nullable();
			$table->string('fb_token', 50)->nullable();
			$table->string('tw_token', 50)->nullable();
			$table->string('gg_token', 50)->nullable();
			$table->text('picture')->nullable();
			$table->text('signature', 65535)->nullable();
			$table->date('birthdate')->nullable();
			$table->integer('role');
			$table->integer('status');
			$table->integer('fb_id');
			$table->string('link', 100)->nullable();
			$table->datetime('last_login')->nullable();
			$table->integer('group_id');
			$table->string('title', 50)->nullable();
			$table->string('url', 100)->nullable();
			$table->string('location', 30)->nullable();
			$table->integer('email_setting');
			$table->boolean('show_sig');
			$table->float('timezone');
			$table->boolean('dst');
			$table->integer('time_format');
			$table->integer('date_format');
			$table->string('language', 25);
			$table->integer('num_posts');
			$table->integer('last_post')->nullable();
			$table->integer('last_search')->nullable();
			$table->integer('last_email_sent')->nullable();
			$table->integer('last_report_sent')->nullable();
			$table->integer('registered');
			$table->string('registration_ip', 35);
			$table->integer('last_visit');
			$table->string('admin_note', 30)->nullable();
			$table->string('activate_string', 80)->nullable();
			$table->string('activate_key', 8)->nullable();
			$table->string('remember_token', 300);
			$table->text('favitems', 65535);
			$table->string('shopname', 40);
			$table->string('shopdetail', 200);
			$table->string('shopaddress', 200);
			$table->string('shopprovince', 200);
			$table->string('shopemail', 50);
			$table->string('shoppicture',100)->nullable();
			$table->string('shoptel', 20);
			$table->string('shopbank', 400);
			$table->datetime('shopstart', 400);
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
		Schema::drop('users');
	}

}
