import React from 'react';

interface ChartData {
    month: string;
    income: number;
    expense: number;
}

interface SimpleChartProps {
    data: ChartData[];
    height?: number;
}

export function SimpleBarChart({ data, height = 300 }: SimpleChartProps) {
    const maxValue = Math.max(...data.flatMap(d => [d.income, d.expense]));
    const scale = (height - 40) / maxValue;

    return (
        <div className="flex items-end justify-between h-full px-4" style={{ height }}>
            {data.map((item, index) => (
                <div key={index} className="flex flex-col items-center space-y-2">
                    <div className="flex items-end space-x-1">
                        <div
                            className="bg-green-500 rounded-t"
                            style={{
                                height: Math.max(item.income * scale, 2),
                                width: '16px',
                            }}
                            title={`Income: ${formatCurrency(item.income)}`}
                        />
                        <div
                            className="bg-red-500 rounded-t"
                            style={{
                                height: Math.max(item.expense * scale, 2),
                                width: '16px',
                            }}
                            title={`Expense: ${formatCurrency(item.expense)}`}
                        />
                    </div>
                    <span className="text-xs text-gray-600 dark:text-gray-400 transform -rotate-45">
                        {item.month}
                    </span>
                </div>
            ))}
        </div>
    );
}

export function SimplePieChart({ data }: { data: Array<{ name: string; value: number; color: string }> }) {
    const total = data.reduce((sum, item) => sum + item.value, 0);
    let currentAngle = 0;

    return (
        <div className="flex items-center justify-center h-full">
            <div className="relative">
                <svg width="200" height="200" className="transform -rotate-90">
                    {data.map((item, index) => {
                        const percentage = item.value / total;
                        const angle = percentage * 360;
                        const startAngle = currentAngle;
                        currentAngle += angle;
                        
                        const x1 = 100 + 80 * Math.cos((startAngle * Math.PI) / 180);
                        const y1 = 100 + 80 * Math.sin((startAngle * Math.PI) / 180);
                        const x2 = 100 + 80 * Math.cos(((startAngle + angle) * Math.PI) / 180);
                        const y2 = 100 + 80 * Math.sin(((startAngle + angle) * Math.PI) / 180);
                        
                        const largeArcFlag = angle > 180 ? 1 : 0;
                        
                        return (
                            <path
                                key={index}
                                d={`M 100 100 L ${x1} ${y1} A 80 80 0 ${largeArcFlag} 1 ${x2} ${y2} Z`}
                                fill={item.color}
                                stroke="white"
                                strokeWidth="2"
                            />
                        );
                    })}
                </svg>
                <div className="absolute inset-0 flex items-center justify-center">
                    <div className="text-center">
                        <div className="text-lg font-bold text-gray-900 dark:text-white">
                            {formatCurrency(total)}
                        </div>
                        <div className="text-xs text-gray-500 dark:text-gray-400">Total</div>
                    </div>
                </div>
            </div>
            <div className="ml-6 space-y-2">
                {data.map((item, index) => (
                    <div key={index} className="flex items-center space-x-2">
                        <div
                            className="w-3 h-3 rounded-full"
                            style={{ backgroundColor: item.color }}
                        />
                        <span className="text-sm text-gray-700 dark:text-gray-300">
                            {item.name}
                        </span>
                    </div>
                ))}
            </div>
        </div>
    );
}

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};