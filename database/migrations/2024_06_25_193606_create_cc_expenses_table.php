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
        Schema::create('cc_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('expense_id');
            $table->unsignedInteger('category_id');
            $table->date('date');
            $table->unsignedInteger('vendor_id');
            $table->string('description')->nullable();
            $table->float('cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cc_expenses');
    }
};
