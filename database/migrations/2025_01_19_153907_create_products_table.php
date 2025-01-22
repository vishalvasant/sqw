<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Product name
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key for category
            $table->foreignId('unit_id')->constrained()->onDelete('cascade'); // Foreign key for unit
            $table->decimal('price', 10, 2); // Price of the product
            $table->decimal('stock', 10, 2); // Stock quantity
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}

