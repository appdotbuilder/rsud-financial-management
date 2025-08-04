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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique()->comment('Transaction reference number');
            $table->date('transaction_date')->comment('Transaction date');
            $table->date('journal_date')->comment('Journal entry date');
            $table->date('payment_date')->nullable()->comment('Payment date');
            $table->unsignedBigInteger('bas_account_id')->comment('BAS account ID');
            $table->unsignedBigInteger('hospital_unit_id')->comment('Hospital unit ID');
            $table->enum('type', ['income', 'expense', 'return', 'correction'])->comment('Transaction type');
            $table->decimal('amount', 15, 2)->comment('Transaction amount');
            $table->text('description')->comment('Transaction description');
            $table->string('proof_file')->nullable()->comment('Proof document file path');
            $table->enum('status', ['draft', 'approved', 'locked'])->default('draft')->comment('Transaction status');
            $table->unsignedBigInteger('created_by')->comment('User who created the transaction');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('User who approved the transaction');
            $table->timestamp('approved_at')->nullable()->comment('Approval timestamp');
            $table->timestamps();

            // Indexes for performance
            $table->index('transaction_number');
            $table->index('transaction_date');
            $table->index('bas_account_id');
            $table->index('hospital_unit_id');
            $table->index(['type', 'status']);
            $table->index('created_by');
            
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
        Schema::dropIfExists('transactions');
    }
};