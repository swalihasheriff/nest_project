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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('invoice_no')->unique();
            $table->boolean('status')->default(1);
            $table->text('reference')->nullable();
            $table->boolean('type');
            $table->date('delivery_date')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
