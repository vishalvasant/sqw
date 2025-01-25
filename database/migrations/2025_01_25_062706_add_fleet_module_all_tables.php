<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Fleets Table
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['machine', 'vehicle']); // machine or vehicle
            $table->enum('cost_type', ['per_hour', 'per_km', 'fixed_trip']);
            $table->decimal('base_cost', 10, 2)->nullable();
            $table->timestamps();
        });

        // Drivers Table
        // Schema::create('drivers', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('license_number')->nullable();
        //     $table->string('contact_number');
        //     $table->timestamps();
        // });

        // Fuel Logs Table
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Assuming products table is for inventory
            $table->decimal('quantity', 10, 2);
            $table->decimal('cost', 10, 2);
            $table->timestamp('refueled_at');
            $table->timestamps();
        });

        // Trip Logs Table
        Schema::create('trip_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('cascade');
            $table->enum('cost_type', ['per_hour', 'per_km', 'fixed_trip']);
            $table->integer('duration_or_distance')->nullable(); // Hours or Kilometers
            $table->decimal('trip_cost', 10, 2);
            $table->timestamp('trip_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_logs');
        Schema::dropIfExists('fuel_logs');
        // Schema::dropIfExists('drivers');
        Schema::dropIfExists('fleets');
    }
};
