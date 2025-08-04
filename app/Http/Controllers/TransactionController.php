<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\BasAccount;
use App\Models\HospitalUnit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['basAccount', 'hospitalUnit', 'creator', 'approver']);

        // Apply filters
        if ($request->filled('search')) {
            $query->where('transaction_number', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(15);

        return Inertia::render('transactions/index', [
            'transactions' => $transactions,
            'filters' => $request->only(['search', 'type', 'status', 'date_from', 'date_to']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $basAccounts = BasAccount::active()->get();
        $hospitalUnits = HospitalUnit::active()->get();

        return Inertia::render('transactions/create', [
            'basAccounts' => $basAccounts,
            'hospitalUnits' => $hospitalUnits,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        $data['transaction_number'] = $this->generateTransactionNumber($data['type']);
        $data['created_by'] = auth()->id();

        $transaction = Transaction::create($data);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['basAccount', 'hospitalUnit', 'creator', 'approver']);

        return Inertia::render('transactions/show', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        if ($transaction->status === 'locked') {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Cannot edit locked transaction.');
        }

        $basAccounts = BasAccount::active()->get();
        $hospitalUnits = HospitalUnit::active()->get();

        return Inertia::render('transactions/edit', [
            'transaction' => $transaction,
            'basAccounts' => $basAccounts,
            'hospitalUnits' => $hospitalUnits,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        if ($transaction->status === 'locked') {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Cannot update locked transaction.');
        }

        $transaction->update($request->validated());

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if ($transaction->status === 'locked') {
            return redirect()->route('transactions.index')
                ->with('error', 'Cannot delete locked transaction.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Generate a unique transaction number.
     *
     * @param string $type
     * @return string
     */
    protected function generateTransactionNumber(string $type): string
    {
        $prefix = match ($type) {
            'income' => 'REV',
            'expense' => 'EXP',
            'return' => 'RET',
            'correction' => 'COR',
            default => 'TRX',
        };

        $date = date('Ymd');
        $lastTransaction = Transaction::where('transaction_number', 'like', $prefix . '-' . $date . '-%')
                                    ->latest('id')
                                    ->first();

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->transaction_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . $date . '-' . str_pad((string) $newNumber, 4, '0', STR_PAD_LEFT);
    }
}