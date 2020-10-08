<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_detail', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('stock_item_id');
			$table->enum('type', array('raw','ng','fg'));
			$table->float('original_amount', 10, 0);
			$table->float('amount', 10, 0);
			$table->string('batch_no');
			$table->boolean('status')->default(0);
			$table->string('erp_stock_entry')->nullable();
			$table->timestamps();
			$table->index(['status','stock_item_id'], 'index1');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stock_detail');
	}

}
