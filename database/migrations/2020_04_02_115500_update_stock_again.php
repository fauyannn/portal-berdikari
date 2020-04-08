<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStockAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock', function (Blueprint $table) {
            $table->string('supplier')->after('id')->default('');
            $table->integer('type')->after('supplier')->default(1);
            $table->string('not_good_uom')->nullable()->after('not_good_qty');
            $table->dropColumn('wip_qty');
            $table->dropColumn('finish_good_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock', function (Blueprint $table) {
            $table->dropColumn('supplier');
            $table->dropColumn('type');
            $table->dropColumn('not_good_uom');
            $table->float('wip_qty')->nullable();
            $table->float('finish_good_qty')->nullable();
        });
    }
}
