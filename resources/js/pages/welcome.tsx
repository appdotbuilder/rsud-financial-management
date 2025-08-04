import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { SimpleBarChart, SimplePieChart } from '@/components/simple-chart';

interface Transaction {
    id: number;
    transaction_number: string;
    transaction_date: string;
    type: string;
    amount: string;
    description: string;
    status: string;
    bas_account: {
        name: string;
        code: string;
    };
    hospital_unit: {
        name: string;
        code: string;
    };
    creator: {
        name: string;
    };
}

interface FinancialSummary {
    totalCashBalance: number;
    monthlyIncome: number;
    monthlyExpense: number;
    totalBudget: number;
    budgetRealization: number;
    budgetUtilization: number;
}

interface MonthlyTrend {
    month: number;
    income: number;
    expense: number;
}

interface BudgetComparison {
    name: string;
    budget: number;
    realization: number;
}

interface Props {
    financialSummary: FinancialSummary;
    monthlyTrends: MonthlyTrend[];
    recentTransactions: Transaction[];
    budgetComparison: BudgetComparison[];
    [key: string]: unknown;
}

const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#8884D8'];

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

const getMonthName = (monthNumber: number) => {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return months[monthNumber - 1];
};

export default function Welcome({ 
    financialSummary, 
    monthlyTrends, 
    recentTransactions, 
    budgetComparison 
}: Props) {
    const { auth } = usePage<SharedData>().props;

    const chartData = monthlyTrends.map(trend => ({
        month: getMonthName(trend.month),
        income: trend.income,
        expense: trend.expense,
    }));

    const pieData = budgetComparison.slice(0, 5).map((item, index) => ({
        name: item.name,
        value: item.realization,
        color: COLORS[index % COLORS.length],
    }));

    return (
        <>
            <Head title="RSUD Financial Management System">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
                {/* Header */}
                <header className="bg-white shadow-sm dark:bg-gray-800">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center py-6">
                            <div className="flex items-center">
                                <div className="flex-shrink-0">
                                    <div className="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <span className="text-white text-xl font-bold">🏥</span>
                                    </div>
                                </div>
                                <div className="ml-4">
                                    <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
                                        RSUD Financial Management
                                    </h1>
                                    <p className="text-sm text-gray-600 dark:text-gray-300">
                                        SAP BLUD Compliant System
                                    </p>
                                </div>
                            </div>
                            <nav className="flex items-center space-x-4">
                                {auth.user ? (
                                    <Link
                                        href={route('dashboard')}
                                        className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <>
                                        <Link
                                            href={route('login')}
                                            className="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium dark:text-gray-300 dark:hover:text-blue-400"
                                        >
                                            Log in
                                        </Link>
                                        <Link
                                            href={route('register')}
                                            className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                                        >
                                            Register
                                        </Link>
                                    </>
                                )}
                            </nav>
                        </div>
                    </div>
                </header>

                {/* Hero Section */}
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div className="text-center">
                        <h2 className="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                            💰 Comprehensive Financial Management
                        </h2>
                        <p className="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
                            Advanced accrual-based financial system designed specifically for Regional General Hospitals, 
                            fully compliant with SAP BLUD standards and Permendagri 90 regulations.
                        </p>
                    </div>

                    {/* Financial Summary Cards */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                        <div className="bg-white rounded-lg shadow-md p-6 dark:bg-gray-800">
                            <div className="flex items-center">
                                <div className="flex-shrink-0">
                                    <div className="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center dark:bg-green-900">
                                        <span className="text-green-600 dark:text-green-400">💰</span>
                                    </div>
                                </div>
                                <div className="ml-4">
                                    <p className="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cash Balance</p>
                                    <p className="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {formatCurrency(financialSummary.totalCashBalance)}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white rounded-lg shadow-md p-6 dark:bg-gray-800">
                            <div className="flex items-center">
                                <div className="flex-shrink-0">
                                    <div className="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center dark:bg-blue-900">
                                        <span className="text-blue-600 dark:text-blue-400">📈</span>
                                    </div>
                                </div>
                                <div className="ml-4">
                                    <p className="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Income</p>
                                    <p className="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {formatCurrency(financialSummary.monthlyIncome)}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white rounded-lg shadow-md p-6 dark:bg-gray-800">
                            <div className="flex items-center">
                                <div className="flex-shrink-0">
                                    <div className="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center dark:bg-red-900">
                                        <span className="text-red-600 dark:text-red-400">📉</span>
                                    </div>
                                </div>
                                <div className="ml-4">
                                    <p className="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Expense</p>
                                    <p className="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {formatCurrency(financialSummary.monthlyExpense)}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white rounded-lg shadow-md p-6 dark:bg-gray-800">
                            <div className="flex items-center">
                                <div className="flex-shrink-0">
                                    <div className="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center dark:bg-purple-900">
                                        <span className="text-purple-600 dark:text-purple-400">🎯</span>
                                    </div>
                                </div>
                                <div className="ml-4">
                                    <p className="text-sm font-medium text-gray-500 dark:text-gray-400">Budget Utilization</p>
                                    <p className="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {financialSummary.budgetUtilization.toFixed(1)}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Charts Section */}
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        {/* Monthly Trends Chart */}
                        <div className="bg-white rounded-lg shadow-md p-6 dark:bg-gray-800">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                📊 Monthly Income vs Expense Trends
                            </h3>
                            <div className="h-80">
                                <SimpleBarChart data={chartData} height={300} />
                                <div className="flex justify-center mt-4 space-x-6">
                                    <div className="flex items-center space-x-2">
                                        <div className="w-4 h-4 bg-green-500 rounded"></div>
                                        <span className="text-sm text-gray-600 dark:text-gray-400">Income</span>
                                    </div>
                                    <div className="flex items-center space-x-2">
                                        <div className="w-4 h-4 bg-red-500 rounded"></div>
                                        <span className="text-sm text-gray-600 dark:text-gray-400">Expense</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Budget Realization Pie Chart */}
                        <div className="bg-white rounded-lg shadow-md p-6 dark:bg-gray-800">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                🥧 Budget Realization by Account
                            </h3>
                            <div className="h-80">
                                <SimplePieChart data={pieData} />
                            </div>
                        </div>
                    </div>

                    {/* Features Section */}
                    <div className="bg-white rounded-lg shadow-md p-8 mb-12 dark:bg-gray-800">
                        <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                            🚀 Key Features & Modules
                        </h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div className="text-center p-6 border rounded-lg dark:border-gray-700">
                                <div className="text-4xl mb-4">📊</div>
                                <h4 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Main Dashboard
                                </h4>
                                <p className="text-gray-600 dark:text-gray-300 text-sm">
                                    Real-time financial summaries, budget vs realization tracking, and graphical trend analysis
                                </p>
                            </div>

                            <div className="text-center p-6 border rounded-lg dark:border-gray-700">
                                <div className="text-4xl mb-4">💸</div>
                                <h4 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Transaction Input
                                </h4>
                                <p className="text-gray-600 dark:text-gray-300 text-sm">
                                    Comprehensive revenue & expenditure management with automatic journal generation
                                </p>
                            </div>

                            <div className="text-center p-6 border rounded-lg dark:border-gray-700">
                                <div className="text-4xl mb-4">💰</div>
                                <h4 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Debt & Receivables
                                </h4>
                                <p className="text-gray-600 dark:text-gray-300 text-sm">
                                    Patient billing, BPJS claims, supplier payments with automated settlement
                                </p>
                            </div>

                            <div className="text-center p-6 border rounded-lg dark:border-gray-700">
                                <div className="text-4xl mb-4">🧾</div>
                                <h4 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Tax Management
                                </h4>
                                <p className="text-gray-600 dark:text-gray-300 text-sm">
                                    PPN, PPh tracking, input/output monitoring with tax payment documentation
                                </p>
                            </div>

                            <div className="text-center p-6 border rounded-lg dark:border-gray-700">
                                <div className="text-4xl mb-4">🎯</div>
                                <h4 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Budget (RBA)
                                </h4>
                                <p className="text-gray-600 dark:text-gray-300 text-sm">
                                    Initial, shifting & revised budget management with historical change tracking
                                </p>
                            </div>

                            <div className="text-center p-6 border rounded-lg dark:border-gray-700">
                                <div className="text-4xl mb-4">📋</div>
                                <h4 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Financial Reports
                                </h4>
                                <p className="text-gray-600 dark:text-gray-300 text-sm">
                                    LRA, LO, LAK, LPE, Balance Sheet with Excel/PDF export capabilities
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Recent Transactions */}
                    <div className="bg-white rounded-lg shadow-md p-6 mb-12 dark:bg-gray-800">
                        <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            📝 Recent Transactions
                        </h3>
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
                                            Account
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Amount
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    {recentTransactions.slice(0, 5).map((transaction) => (
                                        <tr key={transaction.id}>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm font-medium text-gray-900 dark:text-white">
                                                    {transaction.transaction_number}
                                                </div>
                                                <div className="text-sm text-gray-500 dark:text-gray-400">
                                                    {transaction.description.length > 50 
                                                        ? transaction.description.substring(0, 50) + '...'
                                                        : transaction.description}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {new Date(transaction.transaction_date).toLocaleDateString('id-ID')}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900 dark:text-white">
                                                    {transaction.bas_account.name}
                                                </div>
                                                <div className="text-sm text-gray-500 dark:text-gray-400">
                                                    {transaction.hospital_unit.name}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <span className={transaction.type === 'income' ? 'text-green-600' : 'text-red-600'}>
                                                    {transaction.type === 'income' ? '+' : '-'}{formatCurrency(Number(transaction.amount))}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                                                    transaction.status === 'approved' 
                                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                                }`}>
                                                    {transaction.status}
                                                </span>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Call to Action */}
                    <div className="text-center">
                        <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            Ready to Streamline Your Hospital's Financial Management?
                        </h3>
                        <p className="text-lg text-gray-600 dark:text-gray-300 mb-8">
                            Join hundreds of hospitals already using our SAP BLUD compliant system
                        </p>
                        {!auth.user && (
                            <div className="space-x-4">
                                <Link
                                    href={route('register')}
                                    className="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-md text-lg font-medium transition-colors inline-block"
                                >
                                    Get Started Free
                                </Link>
                                <Link
                                    href={route('login')}
                                    className="border border-blue-600 text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-md text-lg font-medium transition-colors inline-block dark:text-blue-400 dark:border-blue-400 dark:hover:bg-blue-900"
                                >
                                    Sign In
                                </Link>
                            </div>
                        )}
                    </div>
                </div>

                {/* Footer */}
                <footer className="bg-gray-800 text-white py-8">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <p className="text-sm">
                            Built with ❤️ for Indonesian Healthcare • SAP BLUD Compliant • Permendagri 90 Standards
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}