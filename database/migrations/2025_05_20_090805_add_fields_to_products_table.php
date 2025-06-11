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
        Schema::table('products', function (Blueprint $table) {
            $table->string('batchno')->nullable();
            $table->timestamp('expiry')->nullable();
            $table->decimal('totalprice', 12, 4)->nullable();
            $table->string('shelf')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('batchno');
            $table->dropColumn('expiry');
            $table->dropColumn('totalprice', 12, 4);
            $table->dropColumn('shelf');
        });
    }
};
