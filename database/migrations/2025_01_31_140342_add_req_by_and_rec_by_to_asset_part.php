<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asset_part', function (Blueprint $table) {
            $table->text('req_by')->after('quantity')->nullable();
            $table->text('rec_by')->after('req_by')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_part', function (Blueprint $table) {
            $table->dropColumn('req_by');
            $table->dropColumn('rec_by');
        });
    }
};
