<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('service_purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_purchase_order_id');
            $table->unsignedBigInteger('service_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();

            $table->foreign('service_purchase_order_id')->references('id')->on('service_purchase_orders')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('product_services')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_purchase_order_items');
    }
};
