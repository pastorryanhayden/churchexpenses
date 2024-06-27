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
        Schema::create('credit_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cc_payment_id');
            $table->date('date');
            $table->string('vendor')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->float('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_expenses');
    }
};
