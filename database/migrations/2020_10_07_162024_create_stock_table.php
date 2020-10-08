<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::drop('stock');
		Schema::create('stock', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('supplier')->default('1');
			$table->string('item_code');
			$table->string('item_name');
			$table->string('uom');
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
		Schema::drop('stock');
	}

}
