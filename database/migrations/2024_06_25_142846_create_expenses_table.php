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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->float('credit_ammount')->default(0);
            $table->float('debit_ammount')->default(0);
            $table->unsignedInteger('code')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('reference')->nullable();
            $table->string('memo')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
