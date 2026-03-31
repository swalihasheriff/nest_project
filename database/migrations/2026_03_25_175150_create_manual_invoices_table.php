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
        Schema::create('manual_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('supplier');
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('received_on')->nullable();
            $table->string('received_by')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('rounding', 10, 2)->default(0);
            $table->integer('status')->default(1)->comment('1->Received, 2->Finalised');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_invoices');
    }
};
