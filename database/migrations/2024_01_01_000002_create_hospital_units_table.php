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
        Schema::create('hospital_units', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Unit code');
            $table->string('name')->comment('Unit name');
            $table->text('description')->nullable()->comment('Unit description');
            $table->boolean('is_active')->default(true)->comment('Unit status');
            $table->timestamps();

            // Indexes for performance
            $table->index('code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_units');
    }
};