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
        Schema::create('bas_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('BAS account code (e.g., 1.1.1.01.01)');
            $table->string('name')->comment('Account name');
            $table->text('description')->nullable()->comment('Account description');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Parent account ID for hierarchy');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense'])->comment('Account type');
            $table->integer('level')->default(1)->comment('Hierarchy level');
            $table->boolean('is_active')->default(true)->comment('Account status');
            $table->timestamps();

            // Indexes for performance
            $table->index('code');
            $table->index('parent_id');
            $table->index(['type', 'is_active']);
            $table->index('level');
            
            // Foreign key constraint
            $table->foreign('parent_id')->references('id')->on('bas_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bas_accounts');
    }
};