<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kitchen_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('breakfast_count')->default(0);
            $table->integer('lunch_count')->default(0);
            $table->integer('dinner_count')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kitchen_records');
    }
};
