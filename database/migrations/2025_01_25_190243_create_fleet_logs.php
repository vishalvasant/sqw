<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('fleet_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fleet_id')->constrained('fleets')->onDelete('cascade');
            $table->enum('type', ['per_hour', 'per_km', 'fixed_trip']);
            $table->decimal('cost', 10, 2);
            $table->decimal('duration_or_distance', 10, 2); // Hours or Kilometers
            $table->date('log_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fleet_logs');
    }
};
