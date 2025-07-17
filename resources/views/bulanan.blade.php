<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Financial Recap</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#10b981',
                        danger: '#ef4444',
                        warning: '#f59e0b',
                    }
                }
            }
        }
    </script>
    <style>
        .chart-container {
            height: 300px;
        }
        @media (max-width: 640px) {
            .responsive-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Monthly Financial Recap</h1>
                <p class="text-gray-600">Track your income and expenses by month</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center space-x-2">
                    <button id="prev-month" class="p-2 rounded-full bg-white border border-gray-200 hover:bg-gray-100">
                        <i class="fas fa-chevron-left text-gray-600"></i>
                    </button>
                    <div class="relative">
                        <select id="month-select" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="relative">
                        <select id="year-select" class="appearance-none bg-white border border-gray-300 rounded-md px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <button id="next-month" class="p-2 rounded-full bg-white border border-gray-200 hover:bg-gray-100">
                        <i class="fas fa-chevron-right text-gray-600"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Total Income</p>
                        <h2 class="text-3xl font-bold text-primary" id="total-income">$12,450</h2>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 text-primary">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-500 flex items-center">
                        <span class="text-green-500 mr-1"><i class="fas fa-arrow-up"></i> 12%</span>
                        vs last month
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Transactions</p>
                        <h2 class="text-3xl font-bold text-secondary" id="total-transactions">287</h2>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 text-secondary">
                        <i class="fas fa-exchange-alt text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-500 flex items-center">
                        <span class="text-green-500 mr-1"><i class="fas fa-arrow-up"></i> 5%</span>
                        vs last month
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Average Daily</p>
                        <h2 class="text-3xl font-bold text-warning" id="average-daily">$415</h2>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 text-warning">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-500 flex items-center">
                        <span class="text-red-500 mr-1"><i class="fas fa-arrow-down"></i> 2%</span>
                        vs last month
                    </p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Income</h3>
                <div class="chart-container">
                    <canvas id="daily-income-chart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Income by Category</h3>
                <div class="chart-container">
                    <canvas id="category-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Daily Transactions Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Daily Transactions</h3>
            </div>
            <div class="responsive-table">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Income</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="daily-transactions">
                        <!-- Data will be inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">31</span> days
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200">Previous</button>
                    <button class="px-3 py-1 rounded-md bg-primary text-white hover:bg-blue-600">Next</button>
                </div>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Category Breakdown</h3>
            </div>
            <div class="divide-y divide-gray-200">
                <!-- Category items will be inserted here by JavaScript -->
                <div id="category-breakdown"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Set current month and year
        const currentDate = new Date();
        document.getElementById('month-select').value = currentDate.getMonth() + 1;
        document.getElementById('year-select').value = currentDate.getFullYear();

        // Sample data - in a real app, this would come from your API
        const sampleData = {
            month: currentDate.getMonth() + 1,
            year: currentDate.getFullYear(),
            totalIncome: 12450,
            totalTransactions: 287,
            averageDaily: 415,
            dailyIncome: [
                { day: 1, income: 320, transactions: 8, status: 'normal' },
                { day: 2, income: 410, transactions: 10, status: 'normal' },
                { day: 3, income: 280, transactions: 7, status: 'low' },
                { day: 4, income: 520, transactions: 14, status: 'high' },
                { day: 5, income: 390, transactions: 9, status: 'normal' },
                { day: 6, income: 610, transactions: 16, status: 'high' },
                { day: 7, income: 480, transactions: 12, status: 'normal' },
                { day: 8, income: 370, transactions: 9, status: 'normal' },
                { day: 9, income: 420, transactions: 11, status: 'normal' },
                { day: 10, income: 290, transactions: 7, status: 'low' },
                { day: 11, income: 510, transactions: 13, status: 'high' },
                { day: 12, income: 450, transactions: 12, status: 'normal' },
                { day: 13, income: 380, transactions: 10, status: 'normal' },
                { day: 14, income: 540, transactions: 14, status: 'high' },
                { day: 15, income: 490, transactions: 13, status: 'normal' },
                { day: 16, income: 320, transactions: 8, status: 'normal' },
                { day: 17, income: 410, transactions: 10, status: 'normal' },
                { day: 18, income: 280, transactions: 7, status: 'low' },
                { day: 19, income: 520, transactions: 14, status: 'high' },
                { day: 20, income: 390, transactions: 9, status: 'normal' },
                { day: 21, income: 610, transactions: 16, status: 'high' },
                { day: 22, income: 480, transactions: 12, status: 'normal' },
                { day: 23, income: 370, transactions: 9, status: 'normal' },
                { day: 24, income: 420, transactions: 11, status: 'normal' },
                { day: 25, income: 290, transactions: 7, status: 'low' },
                { day: 26, income: 510, transactions: 13, status: 'high' },
                { day: 27, income: 450, transactions: 12, status: 'normal' },
                { day: 28, income: 380, transactions: 10, status: 'normal' },
                { day: 29, income: 540, transactions: 14, status: 'high' },
                { day: 30, income: 490, transactions: 13, status: 'normal' },
                { day: 31, income: 320, transactions: 8, status: 'normal' }
            ],
            categories: [
                { name: 'Food', income: 6500, percentage: 52, trend: 'up' },
                { name: 'Beverages', income: 3200, percentage: 26, trend: 'up' },
                { name: 'Desserts', income: 1800, percentage: 14, trend: 'down' },
                { name: 'Others', income: 950, percentage: 8, trend: 'stable' }
            ]
        };

        // Update UI with sample data
        function updateUI(data) {
            // Update summary cards
            document.getElementById('total-income').textContent = `$${data.totalIncome.toLocaleString()}`;
            document.getElementById('total-transactions').textContent = data.totalTransactions.toLocaleString();
            document.getElementById('average-daily').textContent = `$${data.averageDaily.toLocaleString()}`;

            // Update daily transactions table
            const dailyTransactionsTable = document.getElementById('daily-transactions');
            dailyTransactionsTable.innerHTML = '';
            
            data.dailyIncome.forEach(day => {
                const statusColor = day.status === 'high' ? 'bg-green-100 text-green-800' : 
                                  day.status === 'low' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800';
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${data.month}/${day.day}/${data.year}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${day.transactions}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$${day.income}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColor}">
                            ${day.status.charAt(0).toUpperCase() + day.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button class="text-primary hover:text-blue-700">Details</button>
                    </td>
                `;
                dailyTransactionsTable.appendChild(row);
            });

            // Update category breakdown
            const categoryBreakdown = document.getElementById('category-breakdown');
            categoryBreakdown.innerHTML = '';
            
            data.categories.forEach(category => {
                const trendIcon = category.trend === 'up' ? 'fa-arrow-up text-green-500' : 
                                category.trend === 'down' ? 'fa-arrow-down text-red-500' : 'fa-minus text-gray-500';
                
                const item = document.createElement('div');
                item.className = 'px-6 py-4 flex items-center justify-between';
                item.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-utensils text-indigo-600"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">${category.name}</h4>
                            <p class="text-sm text-gray-500">${category.percentage}% of total</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm font-semibold text-gray-900 mr-2">$${category.income.toLocaleString()}</span>
                        <i class="fas ${trendIcon}"></i>
                    </div>
                `;
                categoryBreakdown.appendChild(item);
            });

            // Update charts
            updateCharts(data);
        }

        // Initialize and update charts
        let dailyIncomeChart, categoryChart;

        function updateCharts(data) {
            // Daily Income Chart
            const dailyIncomeCtx = document.getElementById('daily-income-chart').getContext('2d');
            const days = data.dailyIncome.map(day => day.day);
            const incomes = data.dailyIncome.map(day => day.income);
            
            if (dailyIncomeChart) {
                dailyIncomeChart.destroy();
            }
            
            dailyIncomeChart = new Chart(dailyIncomeCtx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Daily Income',
                        data: incomes,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('category-chart').getContext('2d');
            const categoryNames = data.categories.map(cat => cat.name);
            const categoryIncomes = data.categories.map(cat => cat.income);
            const backgroundColors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];
            
            if (categoryChart) {
                categoryChart.destroy();
            }
            
            categoryChart = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryNames,
                    datasets: [{
                        data: categoryIncomes,
                        backgroundColor: backgroundColors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    },
                    cutout: '70%'
                }
            });
        }

        // Event listeners for month/year navigation
        document.getElementById('prev-month').addEventListener('click', () => {
            let month = parseInt(document.getElementById('month-select').value);
            let year = parseInt(document.getElementById('year-select').value);
            
            if (month === 1) {
                month = 12;
                year--;
            } else {
                month--;
            }
            
            document.getElementById('month-select').value = month;
            document.getElementById('year-select').value = year;
            loadData(month, year);
        });

        document.getElementById('next-month').addEventListener('click', () => {
            let month = parseInt(document.getElementById('month-select').value);
            let year = parseInt(document.getElementById('year-select').value);
            
            if (month === 12) {
                month = 1;
                year++;
            } else {
                month++;
            }
            
            document.getElementById('month-select').value = month;
            document.getElementById('year-select').value = year;
            loadData(month, year);
        });

        document.getElementById('month-select').addEventListener('change', () => {
            const month = parseInt(document.getElementById('month-select').value);
            const year = parseInt(document.getElementById('year-select').value);
            loadData(month, year);
        });

        document.getElementById('year-select').addEventListener('change', () => {
            const month = parseInt(document.getElementById('month-select').value);
            const year = parseInt(document.getElementById('year-select').value);
            loadData(month, year);
        });

        // Simulate loading data from API
        function loadData(month, year) {
            // In a real app, you would fetch data from your API here
            console.log(`Loading data for ${month}/${year}`);
            
            // For demo purposes, we'll just modify the sample data
            const modifiedData = JSON.parse(JSON.stringify(sampleData));
            modifiedData.month = month;
            modifiedData.year = year;
            
            // Randomize some data to show changes
            modifiedData.totalIncome = Math.floor(10000 + Math.random() * 10000);
            modifiedData.totalTransactions = Math.floor(200 + Math.random() * 200);
            modifiedData.averageDaily = Math.floor(modifiedData.totalIncome / 30);
            
            modifiedData.dailyIncome.forEach(day => {
                day.income = Math.floor(200 + Math.random() * 500);
                day.transactions = Math.floor(5 + Math.random() * 15);
                day.status = ['low', 'normal', 'high'][Math.floor(Math.random() * 3)];
            });
            
            modifiedData.categories.forEach(cat => {
                cat.income = Math.floor(modifiedData.totalIncome * (cat.percentage / 100));
                cat.trend = ['up', 'down', 'stable'][Math.floor(Math.random() * 3)];
            });
            
            updateUI(modifiedData);
        }

        // Initialize with current month data
        updateUI(sampleData);
    </script>
</body>
</html>