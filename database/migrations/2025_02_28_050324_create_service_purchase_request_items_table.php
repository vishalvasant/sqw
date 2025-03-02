<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('service_purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_purchase_request_id');
            $table->unsignedBigInteger('service_id');
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('service_purchase_request_id', 'test_foreign')->references('id')->on('service_purchase_requests')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('product_services')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_purchase_request_items');
    }
};

