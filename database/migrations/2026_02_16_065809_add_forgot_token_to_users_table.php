<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('forgot_token', 100)->nullable();
            $table->timestamp('forgot_token_created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['forgot_token', 'forgot_token_created_at']);
        });
    }
};

