<?php

namespace Database\Seeders;

use App\Models\BasAccount;
use App\Models\Budget;
use App\Models\HospitalUnit;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinancialSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Hospital Units
        $units = [
            ['code' => 'IGD', 'name' => 'Instalasi Gawat Darurat', 'description' => 'Emergency Department'],
            ['code' => 'RAWAT', 'name' => 'Rawat Inap', 'description' => 'Inpatient Care'],
            ['code' => 'JALAN', 'name' => 'Rawat Jalan', 'description' => 'Outpatient Care'],
            ['code' => 'LAB', 'name' => 'Laboratorium', 'description' => 'Laboratory Services'],
            ['code' => 'RAD', 'name' => 'Radiologi', 'description' => 'Radiology Department'],
            ['code' => 'FARM', 'name' => 'Farmasi', 'description' => 'Pharmacy'],
        ];

        foreach ($units as $unit) {
            HospitalUnit::create($unit);
        }

        // Create BAS Accounts following SAP BLUD structure
        $accounts = [
            // Assets (Level 1)
            ['code' => '1', 'name' => 'ASET', 'type' => 'asset', 'level' => 1, 'parent_id' => null],
            ['code' => '1.1', 'name' => 'ASET LANCAR', 'type' => 'asset', 'level' => 2, 'parent_id' => 1],
            ['code' => '1.1.1', 'name' => 'Kas dan Setara Kas', 'type' => 'asset', 'level' => 3, 'parent_id' => 2],
            ['code' => '1.1.1.01', 'name' => 'Kas di Bendahara Pengeluaran', 'type' => 'asset', 'level' => 4, 'parent_id' => 3],
            ['code' => '1.1.1.02', 'name' => 'Kas di Bendahara Penerimaan', 'type' => 'asset', 'level' => 4, 'parent_id' => 3],
            ['code' => '1.1.2', 'name' => 'Investasi Jangka Pendek', 'type' => 'asset', 'level' => 3, 'parent_id' => 2],
            ['code' => '1.1.3', 'name' => 'Piutang', 'type' => 'asset', 'level' => 3, 'parent_id' => 2],
            ['code' => '1.1.3.01', 'name' => 'Piutang Pasien', 'type' => 'asset', 'level' => 4, 'parent_id' => 7],
            ['code' => '1.1.3.02', 'name' => 'Piutang BPJS', 'type' => 'asset', 'level' => 4, 'parent_id' => 7],

            // Liabilities (Level 1)
            ['code' => '2', 'name' => 'KEWAJIBAN', 'type' => 'liability', 'level' => 1, 'parent_id' => null],
            ['code' => '2.1', 'name' => 'KEWAJIBAN JANGKA PENDEK', 'type' => 'liability', 'level' => 2, 'parent_id' => 10],
            ['code' => '2.1.1', 'name' => 'Utang Usaha', 'type' => 'liability', 'level' => 3, 'parent_id' => 11],
            ['code' => '2.1.2', 'name' => 'Utang Pajak', 'type' => 'liability', 'level' => 3, 'parent_id' => 11],

            // Equity (Level 1)
            ['code' => '3', 'name' => 'EKUITAS', 'type' => 'equity', 'level' => 1, 'parent_id' => null],
            ['code' => '3.1', 'name' => 'EKUITAS AWAL', 'type' => 'equity', 'level' => 2, 'parent_id' => 14],

            // Revenue (Level 1)
            ['code' => '4', 'name' => 'PENDAPATAN', 'type' => 'revenue', 'level' => 1, 'parent_id' => null],
            ['code' => '4.1', 'name' => 'PENDAPATAN USAHA', 'type' => 'revenue', 'level' => 2, 'parent_id' => 16],
            ['code' => '4.1.1', 'name' => 'Pendapatan Pelayanan Kesehatan', 'type' => 'revenue', 'level' => 3, 'parent_id' => 17],
            ['code' => '4.1.1.01', 'name' => 'Pendapatan Rawat Inap', 'type' => 'revenue', 'level' => 4, 'parent_id' => 18],
            ['code' => '4.1.1.02', 'name' => 'Pendapatan Rawat Jalan', 'type' => 'revenue', 'level' => 4, 'parent_id' => 18],
            ['code' => '4.1.1.03', 'name' => 'Pendapatan IGD', 'type' => 'revenue', 'level' => 4, 'parent_id' => 18],

            // Expenses (Level 1)
            ['code' => '5', 'name' => 'BEBAN', 'type' => 'expense', 'level' => 1, 'parent_id' => null],
            ['code' => '5.1', 'name' => 'BEBAN OPERASIONAL', 'type' => 'expense', 'level' => 2, 'parent_id' => 22],
            ['code' => '5.1.1', 'name' => 'Beban Pegawai', 'type' => 'expense', 'level' => 3, 'parent_id' => 23],
            ['code' => '5.1.1.01', 'name' => 'Gaji dan Tunjangan', 'type' => 'expense', 'level' => 4, 'parent_id' => 24],
            ['code' => '5.1.2', 'name' => 'Beban Barang dan Jasa', 'type' => 'expense', 'level' => 3, 'parent_id' => 23],
            ['code' => '5.1.2.01', 'name' => 'Obat-obatan', 'type' => 'expense', 'level' => 4, 'parent_id' => 26],
            ['code' => '5.1.2.02', 'name' => 'Alat Kesehatan', 'type' => 'expense', 'level' => 4, 'parent_id' => 26],
        ];

        foreach ($accounts as $index => $account) {
            BasAccount::create(array_merge($account, ['id' => $index + 1]));
        }

        // Create sample budgets for current year
        $currentYear = date('Y');
        $hospitalUnits = HospitalUnit::all();
        $expenseAccounts = BasAccount::where('type', 'expense')->where('level', 4)->get();

        foreach ($hospitalUnits as $unit) {
            foreach ($expenseAccounts as $account) {
                Budget::create([
                    'fiscal_year' => $currentYear,
                    'bas_account_id' => $account->id,
                    'hospital_unit_id' => $unit->id,
                    'type' => 'initial',
                    'amount' => random_int(10000000, 100000000), // 10M - 100M
                    'description' => "Budget for {$account->name} - {$unit->name}",
                    'status' => 'approved',
                    'created_by' => 1,
                    'approved_by' => 1,
                    'approved_at' => now(),
                ]);
            }
        }

        // Create sample transactions
        $revenueAccounts = BasAccount::where('type', 'revenue')->where('level', 4)->get();
        
        // Create revenue transactions
        for ($i = 0; $i < 50; $i++) {
            $date = now()->subDays(random_int(1, 365));
            $revenueAccount = $revenueAccounts->random();
            $unit = $hospitalUnits->random();
            
            Transaction::create([
                'transaction_number' => 'REV-' . date('Ymd') . '-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'transaction_date' => $date,
                'journal_date' => $date,
                'payment_date' => $date->addDays(random_int(0, 30)),
                'bas_account_id' => $revenueAccount->id,
                'hospital_unit_id' => $unit->id,
                'type' => 'income',
                'amount' => random_int(1000000, 50000000), // 1M - 50M
                'description' => "Revenue from {$revenueAccount->name} - {$unit->name}",
                'status' => 'approved',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => $date->addHours(random_int(1, 24)),
            ]);
        }

        // Create expense transactions
        for ($i = 0; $i < 100; $i++) {
            $date = now()->subDays(random_int(1, 365));
            $expenseAccount = $expenseAccounts->random();
            $unit = $hospitalUnits->random();
            
            Transaction::create([
                'transaction_number' => 'EXP-' . date('Ymd') . '-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'transaction_date' => $date,
                'journal_date' => $date,
                'payment_date' => $date->addDays(random_int(1, 15)),
                'bas_account_id' => $expenseAccount->id,
                'hospital_unit_id' => $unit->id,
                'type' => 'expense',
                'amount' => random_int(500000, 25000000), // 500K - 25M
                'description' => "Expense for {$expenseAccount->name} - {$unit->name}",
                'status' => 'approved',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => $date->addHours(random_int(1, 24)),
            ]);
        }
    }
}