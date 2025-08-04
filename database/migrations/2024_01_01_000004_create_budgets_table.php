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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->year('fiscal_year')->comment('Budget fiscal year');
            $table->unsignedBigInteger('bas_account_id')->comment('BAS account ID');
            $table->unsignedBigInteger('hospital_unit_id')->comment('Hospital unit ID');
            $table->enum('type', ['initial', 'shifting', 'revised'])->comment('Budget type');
            $table->decimal('amount', 15, 2)->comment('Budget amount');
            $table->text('description')->nullable()->comment('Budget description');
            $table->enum('status', ['draft', 'approved', 'locked'])->default('draft')->comment('Budget status');
            $table->unsignedBigInteger('created_by')->comment('User who created the budget');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('User who approved the budget');
            $table->timestamp('approved_at')->nullable()->comment('Approval timestamp');
            $table->timestamps();

            // Indexes for performance
            $table->index('fiscal_year');
            $table->index('bas_account_id');
            $table->index('hospital_unit_id');
            $table->index(['type', 'status']);
            $table->index(['fiscal_year', 'bas_account_id', 'hospital_unit_id']);
            
            // Foreign key constraints
            $table->foreign('bas_account_id')->references('id')->on('bas_accounts');
            $table->foreign('hospital_unit_id')->references('id')->on('hospital_units');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};