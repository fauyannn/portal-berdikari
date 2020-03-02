<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryNoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_note_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delivery_note_id')->unsigned()->nullable();
            $table->foreign('delivery_note_id')->references('id')->on('delivery_notes')->onDelete('cascade')->onUpdate('cascade'); 
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->float('qty')->nullable();
            $table->string('uom')->nullable();
            $table->float('rate',15,2)->nullable();
            $table->float('amount',15,2)->nullable();
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
        Schema::drop('delivery_note_items');
    }
}
