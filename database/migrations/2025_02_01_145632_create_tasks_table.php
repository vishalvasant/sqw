<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade'); // User assigned
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade'); // User assigned
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // User who created the task
            $table->enum('status', ['pending', 'in_progress', 'completed', 'approved'])->default('pending');
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
