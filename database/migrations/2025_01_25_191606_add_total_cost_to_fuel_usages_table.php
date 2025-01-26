<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalCostToFuelUsagesTable extends Migration
{
    public function up()
    {
        Schema::table('fuel_usages', function (Blueprint $table) {
            $table->decimal('total_cost', 10, 2)->after('cost_per_liter')->nullable();
        });
    }

    public function down()
    {
        Schema::table('fuel_usages', function (Blueprint $table) {
            $table->dropColumn('total_cost');
        });
    }
}
