<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .food-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .food-card.selected {
            border: 2px solid #4CAF50;
            background-color: #f8fff8;
        }
        .receipt {
            background: linear-gradient(to bottom, #ffffff, #f5f5f5);
            border-left: 4px solid #4CAF50;
        }
        .quantity-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .animated-checkmark {
            animation: checkmark 0.5s ease;
        }
        @keyframes checkmark {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        /* Card styling */
        .food-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .food-card.selected {
            border: 2px solid #4CAF50;
            background-color: #f8fff8;
        }
        

    </style>
</head>
<body class="bg-gray-50">
    <div class="container py-5">
        <!-- Tombol Kembali di kiri atas -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="/dashbord" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <div class="text-center">
                <h1 class="display-5 fw-bold text-gray-800">Input Transaksi</h1>
                <p class="lead text-gray-600">Masukkan detail pesanan pelanggan</p>
            </div>
            <div style="width: 100px;"></div> <!-- Spacer untuk balance -->
        </div>

        <div class="row g-4">
            <!-- Left Column - Order Form -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <!-- Customer Info -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-gray-700 mb-3">
                                <i class="fas fa-user-circle me-2 text-primary"></i> Informasi Pelanggan
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Pelanggan</label>
                                    <input type="text" class="form-control" id="customerNumber" name="nomor_pelanggan" value="{{ old('nomor_pelanggan', $nomor_pelanggan ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Nasi -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-gray-700 mb-3">
                                <i class="fas fa-drumstick-bite me-2 text-primary"></i> Pilih Nasi?
                            </h5>
                            <div class="row g-3" id="NasiContainer">
                                <!-- Main dishes will be added here by JavaScript -->
                            </div>
                        </div>
                        <!-- Main Dishes -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-gray-700 mb-3">
                                <i class="fas fa-drumstick-bite me-2 text-primary"></i> Pilih Lauk
                            </h5>
                            <div class="row g-3" id="LaukContainer">
                                <!-- Main dishes will be added here by JavaScript -->
                            </div>
                        </div>
                        <!-- Sayur -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-gray-700 mb-3">
                                <i class="fas fa-drumstick-bite me-2 text-primary"></i> Pilih Sayur
                            </h5>
                            <div class="row g-3" id="SayurContainer">
                                <!-- Sayur will be added here by JavaScript -->
                            </div>
                        </div>

                        <!-- Drinks -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-gray-700 mb-3">
                                <i class="fas fa-glass-water me-2 text-primary"></i> Pilih Minuman
                            </h5>
                            <div class="row g-3" id="drinksContainer">
                                <!-- Drinks will be added here by JavaScript -->
                            </div>
                        </div>

                        <!-- Snacks -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-gray-700 mb-3">
                                <i class="fas fa-cookie-bite me-2 text-primary"></i> Pilih Cemilan
                            </h5>
                            <div class="row g-3" id="snacksContainer">
                                <!-- Snacks will be added here by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-body p-4 receipt">
                        <h5 class="fw-bold text-gray-700 mb-3">
                            <i class="fas fa-receipt me-2 text-primary"></i> Ringkasan Pesanan
                        </h5>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Tanggal:</span>
                                <span id="transactionDate" class="fw-bold">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Waktu:</span>
                                <span id="transactionTime" class="fw-bold">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">No. Pelanggan:</span>
                                <span id="customerInfo" class="fw-bold">-</span>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div id="orderItems" class="mb-3">
                            <p class="text-muted text-center my-4">Belum ada pesanan</p>
                        </div>
                    
                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 fw-bold text-primary">Total:</span>
                            <span id="total" class="h4 fw-bold text-primary">Rp 0</span>
                        </div>
                        
                        <!-- Tambahkan form -->
                        <form id="orderForm" method="POST" action="{{ route('transactions.store') }}">
                            @csrf
                            <!-- ...input nomor pelanggan dan input pesanan lain... -->
                            <!-- Pastikan input pesanan dikirimkan dalam bentuk array atau sesuai kebutuhan backend -->
                            <input type="hidden" name="order_data" id="orderDataInput">
                            <!-- Tombol simpan -->
                            <button id="submitOrder" class="btn btn-primary w-100 mt-4 py-2" type="submit">
                            <i class="fas fa-check-circle me-2"></i> Simpan Transaksi
                        </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width: 80px; height: 80px;">
                            <i class="fas fa-check-circle text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h5 class="modal-title mb-3">Transaksi Berhasil!</h5>
                    <p class="text-muted mb-4">Pesanan telah berhasil disimpan ke sistem.</p>
                    <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sample data for menu items
        const menuItems = {

            Nasi: [
                { id: 95, name: "Nasi Putih", price: 5000},
                { id: 96, name: "Nasi+Lauk", price: 5000},
                { id: 97, name: "Nasi+Daging", price: 5000},
                { id: 98, name: "Ayam Rendang", price: 5000},
                { id: 99, name: "Ikan Patin Tempoyak", price: 5000},
                
            ],
            Lauk: [
                { id: 1, name: "Ayam Goreng", price: 5000, image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSiLkI1un0iLRlrhTKzxWbgU1mv5PaEzxfvVg&s" },
                { id: 2, name: "Ikan Nila Bakar", price: 5000, image: "" },
                { id: 3, name: "Ayam Rendang", price: 5000, image: "" },
                { id: 4, name: "Ikan Patin Tempoyak", price: 5000, image: "" },
                { id: 5, name: "Ayam Gulai", price: 5000, image: "" },
                { id: 6, name: "Ayam Kecap", price: 28000, image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8AmkfFbf1aDrpJqTh8DVSNZ5Krx-yIVOcvA&s" },
                { id: 7, name: "Ayam Rica", price: 28000, image: "https://asset.kompas.com/crops/6_Qs_cD9xt9sCiCrqZXVr0zPh8U=/0x276:667x721/1200x800/data/photo/2022/04/17/625b7bdcaf58a.jpeg" },
                { id: 8, name: "Telur Ayam Sambal", price: 28000, image: ""},
                { id: 9, name: "Ikan Goreng", price: 28000, image: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSiLkI1un0iLRlrhTKzxWbgU1mv5PaEzxfvVg&s" },
                { id: 10, name: "Ayam Sambal", price: 28000, image: "" },
                
            ],
            Sayur: [
                { id: 51, name: "Ayam Goreng", price: 5000, image: "" },
                { id: 52, name: "Ikan Nila Bakar", price: 5000, image: "" },
                { id: 53, name: "Ayam Rendang", price: 35000, image: "" },
                { id: 54, name: "Sate Ayam", price: 28000, image: "" },
                { id: 55, name: "Sate kambing", price: 28000, image: "" },
                { id: 56, name: "Sate Ayam", price: 28000, image: "" },
                { id: 57, name: "Sate Ayam", price: 28000, image: "" },
                { id: 58, name: "Sate Ayam", price: 28000, image: "" },
                { id: 59, name: "Sate Ayam", price: 28000, image: "" },
                { id: 60, name: "Sate Ayam", price: 28000, image: "" },
            ],
            drinks: [
                { id: 21, name: "Es Teh", price: 8000, image: "" },
                { id: 22, name: "Jus Jeruk", price: 15000, image: "" },
                { id: 23, name: "Es Kopi", price: 12000, image: "" },
                { id: 24, name: "Air Mineral", price: 5000, image: "" },
            ],
            snacks: [
                { id: 41, name: "Telur Asin", price: 10000, image: "/images/telur_asin.jpg" }
            ]
        };

        // Current order
        let currentOrder = {
            customerNumber: "",
            withRice: false,
            items: [],
            total: 0,
            dateTime: ""
        };

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Render menu items
            renderMenuItems();
            
            // Update date and time
            updateDateTime();
            
            // Set up event listeners
            setupEventListeners();

            // Set up form submission
            document.getElementById('orderForm').addEventListener('submit', function(e) {
                // Masukkan data pesanan ke input hidden
                document.getElementById('orderDataInput').value = JSON.stringify(currentOrder.items);
                // Form akan submit ke backend
            });
        });

        // Render menu items
        function renderMenuItems() {
            // Main Dishes
            const NasiContainer = document.getElementById('NasiContainer');
            NasiContainer.innerHTML = '';
            menuItems.Nasi.forEach(item => {
                NasiContainer.appendChild(createMenuItemCard(item));
            });
            const LaukContainer = document.getElementById('LaukContainer');
            LaukContainer.innerHTML = '';
            menuItems.Lauk.forEach(item => {
                LaukContainer.appendChild(createMenuItemCard(item));
            });
            // Sayur
            const SayurContainer = document.getElementById('SayurContainer');
            SayurContainer.innerHTML = '';
            menuItems.Sayur.forEach(item => {
                SayurContainer.appendChild(createMenuItemCard(item));
            });
            
            // Drinks
            const drinksContainer = document.getElementById('drinksContainer');
            drinksContainer.innerHTML = '';
            menuItems.drinks.forEach(item => {
                drinksContainer.appendChild(createMenuItemCard(item));
            });
            
            // Snacks
            const snacksContainer = document.getElementById('snacksContainer');
            snacksContainer.innerHTML = '';
            menuItems.snacks.forEach(item => {
                snacksContainer.appendChild(createMenuItemCard(item));
            });
        }

        // Create menu item card element
        function createMenuItemCard(item) {
            const col = document.createElement('div');
            col.className = 'col-md-6 col-lg-4';
            col.innerHTML = `
                <div class="card food-card h-100" data-id="${item.id}">
                    <img src="${item.image}" class="card-img-top" alt="${item.name}" style="height: 150px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">${item.name}</h6>
                        <p class="card-text text-success fw-bold">Rp ${item.price.toLocaleString()}</p>
                        <button class="btn btn-primary add-item mt-auto" data-id="${item.id}">
                            <i class="fas fa-plus me-2"></i>Tambah
                        </button>
                    </div>
                </div>
            `;
            return col;
        }

        // Set up event listeners
        function setupEventListeners() {
            // Customer info change
            document.getElementById('customerNumber').addEventListener('input', function() {
                currentOrder.customerNumber = this.value;
                updateOrderSummary();
            });
            
            // Add item button
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-item') || e.target.closest('.add-item')) {
                    const button = e.target.classList.contains('add-item') ? e.target : e.target.closest('.add-item');
                    const itemId = parseInt(button.dataset.id);
                    console.log('Add item clicked, itemId:', itemId);
                    addItemToOrder(itemId);
                }
                
                // Remove item button
                if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
                    const button = e.target.classList.contains('remove-item') ? e.target : e.target.closest('.remove-item');
                    const itemId = parseInt(button.dataset.id);
                    removeItemFromOrder(itemId);
                }
                
                // Quantity decrease
                if (e.target.classList.contains('decrease-quantity') || e.target.closest('.decrease-quantity')) {
                    const button = e.target.classList.contains('decrease-quantity') ? e.target : e.target.closest('.decrease-quantity');
                    const itemId = parseInt(button.dataset.id);
                    decreaseQuantity(itemId);
                }
                
                // Quantity increase
                if (e.target.classList.contains('increase-quantity') || e.target.closest('.increase-quantity')) {
                    const button = e.target.classList.contains('increase-quantity') ? e.target : e.target.closest('.increase-quantity');
                    const itemId = parseInt(button.dataset.id);
                    increaseQuantity(itemId);
                }
            });
            
            // Submit order
            // document.getElementById('submitOrder').addEventListener('click', function() {
            //     submitOrder();
            // });
        }

        // Add item to order
        function addItemToOrder(itemId) {
            console.log('addItemToOrder called with itemId:', itemId);
            
            // Find the item in menu
            let item = null;
            for (const category in menuItems) {
                const foundItem = menuItems[category].find(i => i.id === itemId);
                if (foundItem) {
                    item = foundItem;
                    break;
                }
            }
            
            if (!item) {
                console.log('Item not found for itemId:', itemId);
                return;
            }
            
            console.log('Found item:', item);
            
            // Check if item already exists in order
            const existingItemIndex = currentOrder.items.findIndex(i => i.id === itemId);
            
            if (existingItemIndex !== -1) {
                // Increase quantity if item exists
                currentOrder.items[existingItemIndex].quantity += 1;
                console.log('Increased quantity for existing item:', currentOrder.items[existingItemIndex]);
            } else {
                // Add new item to order
                const newItem = {
                    id: item.id,
                    name: item.name,
                    price: item.price,
                    quantity: 1,
                    image: item.image
                };
                currentOrder.items.push(newItem);
                console.log('Added new item to order:', newItem);
            }
            
            console.log('Current order items:', currentOrder.items);
            
            // Update order summary
            updateOrderSummary();
            
            // Show animation
            const card = document.querySelector(`.food-card[data-id="${itemId}"]`);
            if (card) {
                card.classList.add('selected');
                setTimeout(() => {
                    card.classList.remove('selected');
                }, 500);
            }
            console.log('Item added successfully!');
        }

        // Remove item from order
        function removeItemFromOrder(itemId) {
            currentOrder.items = currentOrder.items.filter(item => item.id !== itemId);
            updateOrderSummary();
        }

        // Decrease quantity
        function decreaseQuantity(itemId) {
            const itemIndex = currentOrder.items.findIndex(item => item.id === itemId);
            
            if (itemIndex !== -1) {
                if (currentOrder.items[itemIndex].quantity > 1) {
                    currentOrder.items[itemIndex].quantity -= 1;
                } else {
                    // Remove item if quantity is 1
                    currentOrder.items.splice(itemIndex, 1);
                }
                
                updateOrderSummary();
            }
        }

        // Increase quantity
        function increaseQuantity(itemId) {
            const itemIndex = currentOrder.items.findIndex(item => item.id === itemId);
            
            if (itemIndex !== -1) {
                currentOrder.items[itemIndex].quantity += 1;
                updateOrderSummary();
            }
        }

        // Update order summary
        function updateOrderSummary() {
            console.log('updateOrderSummary called');
            console.log('Current items:', currentOrder.items);
            
            // Calculate total directly from items
            let total = 0;
            
            // Add items
            currentOrder.items.forEach(item => {
                total += item.price * item.quantity;
            });
            
            // Update current order
            currentOrder.total = total;
            console.log('Total calculated:', total);
            
            // Update UI
            updateDateTime();
            updateCustomerInfo();
            updateOrderItems();
            updateOrderTotals();
            
            // Enable/disable submit button
            document.getElementById('submitOrder').disabled = currentOrder.items.length === 0;
            console.log('Order summary updated successfully');
        }

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            
            document.getElementById('transactionDate').textContent = dateStr;
            document.getElementById('transactionTime').textContent = timeStr;
            
            currentOrder.dateTime = `${dateStr} ${timeStr}`;
        }

        // Update customer info
        function updateCustomerInfo() {
            const customerNumber = currentOrder.customerNumber || '-';
            document.getElementById('customerInfo').textContent = `#${customerNumber}`;
        }

        // Update order items
        function updateOrderItems() {
            console.log('updateOrderItems called');
            const orderItemsContainer = document.getElementById('orderItems');
            
            if (currentOrder.items.length === 0) {
                orderItemsContainer.innerHTML = '<p class="text-muted text-center my-4">Belum ada pesanan</p>';
                console.log('No items, showing empty message');
                return;
            }
            
            let html = '';
            
            // Add other items
            currentOrder.items.forEach(item => {
                html += `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <div class="quantity-control d-flex align-items-center me-2">
                                <button class="btn btn-sm btn-outline-secondary decrease-quantity p-0 quantity-btn" data-id="${item.id}">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="mx-2">${item.quantity}x</span>
                                <button class="btn btn-sm btn-outline-secondary increase-quantity p-0 quantity-btn" data-id="${item.id}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <span>${item.name}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="fw-bold me-2">Rp ${(item.price * item.quantity).toLocaleString()}</span>
                            <button class="btn btn-sm btn-outline-danger remove-item p-0 quantity-btn" data-id="${item.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            orderItemsContainer.innerHTML = html;
            console.log('Order items HTML updated:', html);
        }

        // Update order totals
        function updateOrderTotals() {
            document.getElementById('total').textContent = `Rp ${currentOrder.total.toLocaleString()}`;
        }

        // Submit order
        function submitOrder() {
            // In a real application, you would send this data to your backend
            console.log('Order submitted:', currentOrder);
            
            // Show success modal
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Reset form after submission
            setTimeout(() => {
                resetForm();
                successModal.hide();
            }, 3000);
        }

        // Reset form
        function resetForm() {
            currentOrder = {
                customerNumber: document.getElementById('customerNumber').value,
                withRice: false,
                items: [],
                total: 0,
                dateTime: ""
            };
            
            // Reset UI
            updateOrderSummary();
        }
        

    </script>
</body>
</html>