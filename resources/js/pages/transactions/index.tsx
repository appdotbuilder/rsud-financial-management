import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useState } from 'react';

interface BasAccount {
    id: number;
    code: string;
    name: string;
}

interface HospitalUnit {
    id: number;
    code: string;
    name: string;
}

interface User {
    id: number;
    name: string;
}

interface Transaction {
    id: number;
    transaction_number: string;
    transaction_date: string;
    journal_date: string;
    payment_date: string | null;
    type: string;
    amount: string;
    description: string;
    status: string;
    bas_account: BasAccount;
    hospital_unit: HospitalUnit;
    creator: User;
    approver: User | null;
}

interface PaginatedTransactions {
    data: Transaction[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

interface Filters {
    search?: string;
    type?: string;
    status?: string;
    date_from?: string;
    date_to?: string;
}

interface Props {
    transactions: PaginatedTransactions;
    filters: Filters;
    [key: string]: unknown;
}

const formatCurrency = (amount: string | number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(Number(amount));
};

const getTypeColor = (type: string) => {
    switch (type) {
        case 'income':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        case 'expense':
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        case 'return':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        case 'correction':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'approved':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        case 'locked':
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
        case 'draft':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }
};

export default function TransactionsIndex({ transactions, filters }: Props) {
    const [searchForm, setSearchForm] = useState({
        search: filters.search || '',
        type: filters.type || '',
        status: filters.status || '',
        date_from: filters.date_from || '',
        date_to: filters.date_to || '',
    });

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('transactions.index'), searchForm, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const handleReset = () => {
        setSearchForm({
            search: '',
            type: '',
            status: '',
            date_from: '',
            date_to: '',
        });
        router.get(route('transactions.index'));
    };

    return (
        <AppShell>
            <Head title="Transactions" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
                            💸 Transaction Management
                        </h1>
                        <p className="text-gray-600 dark:text-gray-400">
                            Manage all financial transactions for revenue and expenditure
                        </p>
                    </div>
                    <Link href={route('transactions.create')}>
                        <Button>➕ Add Transaction</Button>
                    </Link>
                </div>

                {/* Filters */}
                <div className="bg-white rounded-lg shadow-md p-6 dark:bg-gray-800">
                    <form onSubmit={handleSearch} className="space-y-4">
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Search
                                </label>
                                <Input
                                    type="text"
                                    placeholder="Transaction number or description..."
                                    value={searchForm.search}
                                    onChange={(e) => setSearchForm({ ...searchForm, search: e.target.value })}
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Type
                                </label>
                                <select
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                    value={searchForm.type}
                                    onChange={(e) => setSearchForm({ ...searchForm, type: e.target.value })}
                                >
                                    <option value="">All Types</option>
                                    <option value="income">Income</option>
                                    <option value="expense">Expense</option>
                                    <option value="return">Return</option>
                                    <option value="correction">Correction</option>
                                </select>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Status
                                </label>
                                <select
                                    className="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                    value={searchForm.status}
                                    onChange={(e) => setSearchForm({ ...searchForm, status: e.target.value })}
                                >
                                    <option value="">All Status</option>
                                    <option value="draft">Draft</option>
                                    <option value="approved">Approved</option>
                                    <option value="locked">Locked</option>
                                </select>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    From Date
                                </label>
                                <Input
                                    type="date"
                                    value={searchForm.date_from}
                                    onChange={(e) => setSearchForm({ ...searchForm, date_from: e.target.value })}
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    To Date
                                </label>
                                <Input
                                    type="date"
                                    value={searchForm.date_to}
                                    onChange={(e) => setSearchForm({ ...searchForm, date_to: e.target.value })}
                                />
                            </div>
                        </div>
                        <div className="flex space-x-2">
                            <Button type="submit">🔍 Search</Button>
                            <Button type="button" variant="outline" onClick={handleReset}>
                                🔄 Reset
                            </Button>
                        </div>
                    </form>
                </div>

                {/* Transactions Table */}
                <div className="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead className="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        Transaction
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        Date
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        Account & Unit
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        Type
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        Amount
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        Status
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                {transactions.data.map((transaction) => (
                                    <tr key={transaction.id} className="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm font-medium text-gray-900 dark:text-white">
                                                {transaction.transaction_number}
                                            </div>
                                            <div className="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                                {transaction.description}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {new Date(transaction.transaction_date).toLocaleDateString('id-ID')}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm text-gray-900 dark:text-white">
                                                {transaction.bas_account.code} - {transaction.bas_account.name}
                                            </div>
                                            <div className="text-sm text-gray-500 dark:text-gray-400">
                                                {transaction.hospital_unit.name}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getTypeColor(transaction.type)}`}>
                                                {transaction.type}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <span className={transaction.type === 'income' ? 'text-green-600' : 'text-red-600'}>
                                                {transaction.type === 'income' ? '+' : '-'}{formatCurrency(transaction.amount)}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(transaction.status)}`}>
                                                {transaction.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div className="flex space-x-2">
                                                <Link
                                                    href={route('transactions.show', transaction.id)}
                                                    className="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                >
                                                    👁️ View
                                                </Link>
                                                {transaction.status !== 'locked' && (
                                                    <Link
                                                        href={route('transactions.edit', transaction.id)}
                                                        className="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                    >
                                                        ✏️ Edit
                                                    </Link>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {transactions.last_page > 1 && (
                        <div className="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                            <div className="flex-1 flex justify-between sm:hidden">
                                {transactions.current_page > 1 && (
                                    <Link
                                        href={route('transactions.index', { ...filters, page: transactions.current_page - 1 })}
                                        className="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                    >
                                        Previous
                                    </Link>
                                )}
                                {transactions.current_page < transactions.last_page && (
                                    <Link
                                        href={route('transactions.index', { ...filters, page: transactions.current_page + 1 })}
                                        className="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                    >
                                        Next
                                    </Link>
                                )}
                            </div>
                            <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p className="text-sm text-gray-700 dark:text-gray-300">
                                        Showing <span className="font-medium">{transactions.from}</span> to{' '}
                                        <span className="font-medium">{transactions.to}</span> of{' '}
                                        <span className="font-medium">{transactions.total}</span> results
                                    </p>
                                </div>
                                <div>
                                    <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        {/* Pagination buttons would go here */}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppShell>
    );
}