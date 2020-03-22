<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseOrderColumnInDeliveryNoteItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_note_items', function (Blueprint $table) {
            //
            $table->string('purchase_order')->nullable()->after('delivery_note_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_note_items', function (Blueprint $table) {
            //
            $table->dropColumn('purchase_order');
        });
    }
}