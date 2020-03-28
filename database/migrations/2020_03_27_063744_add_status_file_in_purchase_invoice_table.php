<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFileInPurchaseInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {       
            $table->string('file_invoice')->nullable()->after('purchase_order_number');     
            $table->string('status')->nullable()->after('file_invoice');
            $table->date('due_date')->nullable()->after('file_invoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->dropColumn('due_date');
            $table->dropColumn('status');
            $table->dropColumn('file_invoice');
        });
    }
}
