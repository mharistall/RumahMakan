<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Financial Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Animation for report cards */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        /* Pagination button styles */
        #pagination-controls button:not(:disabled) {
            cursor: pointer;
            transition: all 0.2s;
        }
        
        #pagination-controls button:not(:disabled):hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container py-5">
        <!-- Tombol Kembali di kiri atas -->
        <div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Tombol Kembali -->
    <a href="/dashbord" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
    <!-- Judul di Tengah -->
    <h1 class="text-3xl font-bold text-gray-800 text-center flex-1">
        <i class="fas fa-file-invoice-dollar mr-3 text-blue-600"></i>
        Laporan Keuangan Harian
    </h1>
    <!-- Spacer agar tombol kembali dan judul tidak bertabrakan -->
    <div style="width: 90px;"></div>
</div>
<p class="text-gray-600 mt-2 text-center">Lacak transaksi dan pendapatan harian Anda</p>
        </div>

        <!-- Date Selection Card -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 fade-in no-print">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                Pilih Laporan Tanggal
            </h2>
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1">
                    <label for="report-date" class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanggal</label>
                    <input type="date" id="report-date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div class="flex gap-2">
                    <button id="today-btn" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition flex items-center">
                        <i class="fas fa-calendar-day mr-2"></i> Hari Ini
                    <button id="generate-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-search mr-2"></i> Dapatkan Laporan
                    </button>
                </div>
            </div>
        </div>

        <!-- Report Summary Section -->
        <div id="report-summary" class="hidden fade-in print-section">
            <div class="flex flex-col md:flex-row gap-6 mb-8">
                <!-- Date Display Card -->
                <div class="bg-white rounded-xl shadow-md p-6 flex-1">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-blue-500"></i>
                            Laporan Tanggal
                        </h2>
                        <span class="text-sm text-gray-500">Didapatkan: <span id="generated-time"></span></span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 mt-3" id="selected-date-display">-</p>
                </div>

                <!-- Income Summary Card -->
                <div class="bg-white rounded-xl shadow-md p-6 flex-1">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-coins mr-2 text-green-500"></i>
                        Pendapatan hari ini
                    </h2>
                    <div class="mt-3 flex items-end justify-between">
                        <p class="text-2xl font-bold text-green-600" id="daily-income">Rp 0</p>
                        <p class="text-sm text-gray-500" id="transaction-count">0 Transaksi</p>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8 print-section">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-receipt mr-2 text-blue-500"></i>
                        Detail Transaksi
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan Ke</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody id="transactions-body" class="bg-white divide-y divide-gray-200">
                            <!-- Transaction rows will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 text-sm text-gray-500" id="no-transactions">
                    tidak ada transaksi yang ditemukan untuk tanggal yang dipilih.
                </div>
                
                <!-- Pagination Controls -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between" id="pagination-controls">
                    <div class="text-sm text-gray-500" id="pagination-info">
                        Menunjukan 0 dari 0 transaksi
                    </div>
                    <div class="flex space-x-2">
                        <button id="prev-page" class="px-3 py-1 border rounded text-gray-700 disabled:opacity-50" disabled>
                            Sebelumnya
                        </button>
                        <button id="next-page" class="px-3 py-1 border rounded text-gray-700 disabled:opacity-50" disabled>
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="text-center py-12 fade-in">
            <div class="mx-auto w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-file-alt text-blue-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-800 mb-2">Tidak Ada Laporan</h3>
            <p class="text-gray-600 max-w-md mx-auto">Pilih tanggal dan klik "Dapatkan Laporan" Untuk melihat pendapatan hari ini.</p>
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div id="transaction-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi</h3>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Transaction ID</p>
                        <p class="font-medium" id="modal-transaction-id">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date & Time</p>
                        <p class="font-medium" id="modal-transaction-time">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Customer</p>
                        <p class="font-medium" id="modal-customer">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Payment Method</p>
                        <p class="font-medium" id="modal-payment-method">-</p>
                    </div>
                </div>
                
                <h4 class="text-md font-semibold mb-3">Items Purchased</h4>
                <div class="border rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modal-items-body" class="divide-y divide-gray-200">
                            <!-- Items will be inserted here -->
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-right font-medium">Total</td>
                                <td class="px-4 py-2 font-medium" id="modal-total">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
                <button id="close-modal-btn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        // Pagination variables
        let currentPage = 1;
        const itemsPerPage = 5;
        
        // Sample data - in a real Laravel app, this would come from the controller
        const sampleTransactions = {
            '2023-11-15': [
                {
                    id: 'TRX-001',
                    time: '2023-11-15 08:45:22',
                    customer: 'John Doe (Member)',
                    payment_method: 'Cash',
                    total: 125000,
                    items: [
                        { name: 'Nasi Goreng Special', qty: 2, price: 45000, subtotal: 90000 },
                        { name: 'Es Teh Manis', qty: 1, price: 10000, subtotal: 10000 },
                        { name: 'Kerupuk Udang', qty: 1, price: 25000, subtotal: 25000 }
                    ]
                },
                {
                    id: 'TRX-002',
                    time: '2023-11-15 12:30:15',
                    customer: 'Walk-in Customer',
                    payment_method: 'Debit Card',
                    total: 75000,
                    items: [
                        { name: 'Mie Goreng Jawa', qty: 1, price: 35000, subtotal: 35000 },
                        { name: 'Air Mineral', qty: 2, price: 10000, subtotal: 20000 },
                        { name: 'Sate Ayam (5 tusuk)', qty: 1, price: 20000, subtotal: 20000 }
                    ]
                },
                {
                    id: 'TRX-003',
                    time: '2023-11-15 18:20:45',
                    customer: 'Jane Smith (VIP)',
                    payment_method: 'Credit Card',
                    total: 210000,
                    items: [
                        { name: 'Paket Keluarga A', qty: 1, price: 150000, subtotal: 150000 },
                        { name: 'Jus Alpukat', qty: 2, price: 25000, subtotal: 50000 },
                        { name: 'Pisang Goreng', qty: 1, price: 10000, subtotal: 10000 }
                    ]
                }
            ],
            '2023-11-14': [
                {
                    id: 'TRX-004',
                    time: '2023-11-14 09:15:30',
                    customer: 'Walk-in Customer',
                    payment_method: 'Cash',
                    total: 50000,
                    items: [
                        { name: 'Nasi Uduk', qty: 1, price: 25000, subtotal: 25000 },
                        { name: 'Teh Tarik', qty: 1, price: 15000, subtotal: 15000 },
                        { name: 'Krupuk', qty: 1, price: 10000, subtotal: 10000 }
                    ]
                }
            ]
        };

        // Format date to localized string
        function formatDate(dateString) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        // Format time to HH:MM
        function formatTime(dateTimeString) {
            const date = new Date(dateTimeString);
            return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }

        // Format currency to IDR
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
        }

        // Generate report for a specific date
        function generateReport(date, page = 1) {
            currentPage = page;
            const reportSummary = document.getElementById('report-summary');
            const emptyState = document.getElementById('empty-state');
            const transactionsBody = document.getElementById('transactions-body');
            const noTransactions = document.getElementById('no-transactions');
            const selectedDateDisplay = document.getElementById('selected-date-display');
            const dailyIncome = document.getElementById('daily-income');
            const transactionCount = document.getElementById('transaction-count');
            const generatedTime = document.getElementById('generated-time');
            
            // Hide empty state and show report
            emptyState.classList.add('hidden');
            reportSummary.classList.remove('hidden');
            
            // Set the displayed date
            selectedDateDisplay.textContent = formatDate(date);
            
            // Get transactions for the selected date
            const transactions = sampleTransactions[date] || [];
            
            // Calculate total income and count transactions
            const totalIncome = transactions.reduce((sum, transaction) => sum + transaction.total, 0);
            const count = transactions.length;
            
            // Update summary cards
            dailyIncome.textContent = formatCurrency(totalIncome);
            transactionCount.textContent = `${count} transaction${count !== 1 ? 's' : ''}`;
            generatedTime.textContent = new Date().toLocaleString('id-ID');
            
            // Clear existing rows
            transactionsBody.innerHTML = '';
            
            if (transactions.length === 0) {
                noTransactions.classList.remove('hidden');
            } else {
                noTransactions.classList.add('hidden');
                
                // Calculate pagination
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const paginatedTransactions = transactions.slice(startIndex, endIndex);
                const totalPages = Math.ceil(transactions.length / itemsPerPage);
                
                // Update pagination info
                document.getElementById('pagination-info').textContent = 
                    `Showing ${startIndex + 1}-${Math.min(endIndex, transactions.length)} of ${transactions.length} transactions`;
                
                // Enable/disable pagination buttons
                document.getElementById('prev-page').disabled = currentPage <= 1;
                document.getElementById('next-page').disabled = currentPage >= totalPages;
                
                // Add transaction rows
                paginatedTransactions.forEach(transaction => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 transition';
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${formatTime(transaction.time)}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${transaction.customer}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                ${transaction.items.map(item => `${item.qty}x ${item.name}`).join(', ')}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${formatCurrency(transaction.total)}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium no-print">
                            <button class="text-blue-600 hover:text-blue-900 view-detail mr-3" data-id="${transaction.id}">
                                <i class="fas fa-eye mr-1"></i> View
                            </button>
                        </td>
                    `;
                    transactionsBody.appendChild(row);
                });
                
                // Add event listeners to view buttons
                document.querySelectorAll('.view-detail').forEach(button => {
                    button.addEventListener('click', () => {
                        const transactionId = button.getAttribute('data-id');
                        showTransactionDetail(transactionId, date);
                    });
                });
            }
        }

        // Show transaction detail in modal
        function showTransactionDetail(transactionId, date) {
            const modal = document.getElementById('transaction-modal');
            const transactions = sampleTransactions[date] || [];
            const transaction = transactions.find(t => t.id === transactionId);
            
            if (!transaction) return;
            
            // Set modal content
            document.getElementById('modal-transaction-id').textContent = transaction.id;
            document.getElementById('modal-transaction-time').textContent = new Date(transaction.time).toLocaleString('id-ID');
            document.getElementById('modal-customer').textContent = transaction.customer;
            document.getElementById('modal-payment-method').textContent = transaction.payment_method;
            document.getElementById('modal-total').textContent = formatCurrency(transaction.total);
            
            // Add items to modal
            const itemsBody = document.getElementById('modal-items-body');
            itemsBody.innerHTML = '';
            
            transaction.items.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-2">${item.name}</td>
                    <td class="px-4 py-2">${item.qty}</td>
                    <td class="px-4 py-2">${formatCurrency(item.price)}</td>
                    <td class="px-4 py-2">${formatCurrency(item.subtotal)}</td>
                `;
                itemsBody.appendChild(row);
            });
            
            // Show modal
            modal.classList.remove('hidden');
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', () => {
            // Set today's date as default
            const today = new Date();
            const todayStr = today.toISOString().split('T')[0];
            document.getElementById('report-date').value = todayStr;
            
            // Event listeners
            document.getElementById('today-btn').addEventListener('click', () => {
                document.getElementById('report-date').value = todayStr;
            });
            
            document.getElementById('generate-btn').addEventListener('click', () => {
                const selectedDate = document.getElementById('report-date').value;
                generateReport(selectedDate);
            });
            document.getElementById('close-modal').addEventListener('click', () => {
                document.getElementById('transaction-modal').classList.add('hidden');
            });
            
            document.getElementById('close-modal-btn').addEventListener('click', () => {
                document.getElementById('transaction-modal').classList.add('hidden');
            });
            // Pagination button events
            document.getElementById('prev-page').addEventListener('click', () => {
                const selectedDate = document.getElementById('report-date').value;
                generateReport(selectedDate, currentPage - 1);
            });
            
            document.getElementById('next-page').addEventListener('click', () => {
                const selectedDate = document.getElementById('report-date').value;
                generateReport(selectedDate, currentPage + 1);
            });
            
            // Generate report for today by default
            // generateReport(todayStr);
        });
    </script>
</body>
</html>