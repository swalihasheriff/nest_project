<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stocktakes', function (Blueprint $table) {
            $table->dropColumn(['count', 'variance', 'notes', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocktakes', function (Blueprint $table) {
            $table->integer('product_id')->nullable();
            $table->integer('count')->nullable();
            $table->integer('variance')->nullable();
            $table->text('notes')->nullable();
        });
    }
};
