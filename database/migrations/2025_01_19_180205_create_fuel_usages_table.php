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
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade'); // For vehicle association
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null'); // For inventory fuel product
            $table->decimal('fuel_amount', 8, 2); // Amount of fuel
            $table->decimal('cost_per_liter', 8, 2); // Cost per liter
            $table->date('date'); // Date of usage
            $table->string('purpose')->nullable(); // Optional purpose/notes
            $table->decimal('distance_covered', 8, 2)->nullable(); // Distance covered (optional)
            $table->decimal('hours_used', 8, 2)->nullable(); // Hours used (optional)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fuel_usages');
    }
}
