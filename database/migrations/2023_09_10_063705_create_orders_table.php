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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("fullname")->nullable();
            $table->string("address");
            $table->string("cell_number")->nullable();
            $table->string("email")->nullable();
            $table->string("session_id");
            $table->integer("customerId");
            $table->float("bill");
            $table->string("status");
            $table->string("payment_id")->nullable();
            $table->string("payer_id")->nullable();
            $table->string("currency")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
