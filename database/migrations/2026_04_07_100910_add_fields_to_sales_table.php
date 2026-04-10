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
        Schema::table('sales', function (Blueprint $table) {
            $table->text('delivery_address')->nullable()->after('delivery_date');

            $table->decimal('round', 10, 2)->default(0)->after('delivery_address');

            $table->string('payment_mode')->nullable()->after('round');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['delivery_address', 'round', 'payment_mode']);
        });
    }
};
