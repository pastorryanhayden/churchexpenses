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
        Schema::create('split_incomes', function (Blueprint $table) {
            $table->id();
            $table->string('notes')->nullable();
            $table->float('amount');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('entry_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('split_incomes');
    }
};
