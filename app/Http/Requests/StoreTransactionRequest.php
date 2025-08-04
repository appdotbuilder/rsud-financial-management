<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_date' => 'required|date',
            'journal_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'bas_account_id' => 'required|exists:bas_accounts,id',
            'hospital_unit_id' => 'required|exists:hospital_units,id',
            'type' => 'required|in:income,expense,return,correction',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'proof_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'transaction_date.required' => 'Transaction date is required.',
            'journal_date.required' => 'Journal date is required.',
            'bas_account_id.required' => 'BAS Account is required.',
            'bas_account_id.exists' => 'Selected BAS Account is invalid.',
            'hospital_unit_id.required' => 'Hospital Unit is required.',
            'hospital_unit_id.exists' => 'Selected Hospital Unit is invalid.',
            'type.required' => 'Transaction type is required.',
            'type.in' => 'Transaction type must be income, expense, return, or correction.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Amount must be greater than or equal to 0.',
            'description.required' => 'Description is required.',
            'proof_file.mimes' => 'Proof file must be a PDF or image file.',
            'proof_file.max' => 'Proof file must not exceed 5MB.',
        ];
    }
}