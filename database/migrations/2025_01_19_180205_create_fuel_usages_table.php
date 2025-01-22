<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelUsagesTable extends Migration
{
    public function up()
    {
        Schema::create('fuel_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->decimal('fuel_amount', 8, 2);
            $table->decimal('cost_per_liter', 8, 2);
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fuel_usages');
    }
}
