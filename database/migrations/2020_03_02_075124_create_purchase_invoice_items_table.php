<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_purchase_invoice')->unsigned()->nullable();
            $table->foreign('id_purchase_invoice')->references('id')->on('purchase_invoices')->onDelete('cascade')->onUpdate('cascade'); 
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->float('qty')->nullable();
            $table->string('uom')->nullable();
            $table->float('rate',15,2)->nullable();
            $table->float('amount',15,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('purchase_invoice_items');
    }
}
