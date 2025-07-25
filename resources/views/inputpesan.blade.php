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
        /* Gaya untuk kartu menu */
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
        
        /* Gaya untuk ringkasan pesanan (struk) */
        .receipt {
            background: linear-gradient(to bottom, #ffffff, #f5f5f5);
            border-left: 4px solid #4CAF50;
        }
        
        /* Gaya untuk tombol kuantitas di keranjang */
        .quantity-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        /* Animasi untuk checkmark (modal sukses) */
        @keyframes animated-checkmark { /* Nama animation keyframe diperbaiki agar tidak bentrok */
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .animated-checkmark {
            animation: animated-checkmark 0.5s ease; /* Panggil keyframe yang diperbaiki */
        }

        /* Styles for Sticky Order Summary */
        .sticky-top {
            position: -webkit-sticky; /* For Safari */
            position: sticky;
            top: 1rem; /* Adjust as needed */
            align-self: flex-start; /* To ensure it sticks within its flex container */
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="">
                <i class="fas fa-store me-2"></i> RM Bismillah
            </a>

            <form class="d-flex ms-auto me-3" role="search">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Cari Menu..." aria-label="Search" id="menuSearchInput">
                    <button class="btn btn-outline-success" type="submit" id="searchButton"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: bold;">
                            {{ Auth::user()->name[0] ?? '?' }}
                        </div>
                        <span class="ms-2 d-none d-lg-inline">{{ Auth::user()->name ?? 'Pengguna' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container py-5">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold text-gray-800">Input Transaksi</h1>
            <p class="lead text-gray-600">Masukkan detail pesanan pelanggan</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="fw-bold text-gray-700 mb-3">
                                <i class="fas fa-user-circle me-2 text-primary"></i> Informasi Pelanggan
                            </h5>
                            <div class="row g-3 align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Pelanggan</label>
                                    <input type="text" class="form-control" id="customerNumber" name="nomor_pelanggan" value="{{ old('nomor_pelanggan', $nomor_pelanggan ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <a href="{{ route('menu.index') }}" class="btn btn-success">
                                        <i class="fas fa-edit me-1"></i> Edit Menu
                                    </a>
                                </div>
                            </div>
                        </div>

                        @php
                            // Pastikan $groupedMenus diasumsikan dikirim dari TransactionController::create()
                            // Atau, jika tidak, bisa digrouping di sini: $groupedMenus = $menus->groupBy('category_id');
                        @endphp

                        @forelse($categories as $category)
                            <div class="mb-4 menu-category-section" data-category-id="{{ $category->id }}">
                                <h5 class="fw-bold text-gray-700 mb-3">
                                    <i class="fas fa-utensils me-2 text-primary"></i> Pilih {{ $category->name }}
                                    @if($category->name === 'Nasi') + @endif
                                </h5>
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                    @php
                                        // Pastikan $groupedMenus dikirim dari controller
                                        $currentCategoryMenus = $groupedMenus->get($category->id) ?? collect();
                                    @endphp
                                    @forelse($currentCategoryMenus as $menu)
                                        <div class="col menu-item" data-category-id="{{ $menu->category_id }}" data-menu-id="{{ $menu->id }}" data-price="{{ $menu->price }}">
                                            <div class="card h-100 shadow-sm border-0 food-card">
                                                @if($menu->image)
                                                    <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top" alt="{{ $menu->name }}" style="height: 150px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('images/default-menu.png') }}" class="card-img-top" alt="No Image" style="height: 150px; object-fit: cover;">
                                                @endif
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title mb-1">{{ $menu->name }}</h5>
                                                    <p class="card-text fw-bold text-success mt-auto">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                                    <button class="btn btn-primary add-to-cart-btn" data-menu-id="{{ $menu->id }}">Tambah</button>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-center text-muted">Belum ada menu di kategori {{ $category->name }}.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center text-muted">Belum ada kategori menu yang tersedia.</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top">
                    <div class="card-body p-4 receipt">
                        <h5 class="fw-bold text-gray-700 mb-3">
                            <i class="fas fa-receipt me-2 text-primary"></i> Ringkasan Pesanan
                        </h5>

                        {{-- Filter Kategori di Ringkasan Pesanan (ini adalah filter display menu di sebelah kiri) --}}
                        <div class="mb-3">
                            <label for="filterKategori" class="form-label">Pilih Kategori Menu</label>
                            <select class="form-select" id="filterKategori">
                                <option value="">Tampilkan Semua Menu</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Tanggal:</span>
                                <span id="transactionDate" class="fw-bold">{{ date('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Waktu:</span>
                                <span id="transactionTime" class="fw-bold">{{ date('H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">No. Pelanggan:</span>
                                <span id="customerInfo" class="fw-bold">{{ $nomor_pelanggan ?? '-' }}</span>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <ul id="cartItems" class="list-group list-group-flush mb-3">
                            {{-- Item-item pesanan akan dirender di sini oleh JavaScript --}}
                            <p class="text-muted text-center my-4">Belum ada pesanan</p>
                        </ul>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 fw-bold text-primary">Total:</span>
                            <span id="totalAmount" class="h4 fw-bold text-primary">Rp 0</span>
                        </div>
                        
                        <form id="orderForm" method="POST" action="{{ route('transactions.store') }}">
                            @csrf
                            <input type="hidden" name="customer_number" id="formCustomerNumberInput">
                            <input type="hidden" name="order_data" id="orderDataInput">
                            <button id="submitOrder" class="btn btn-primary w-100 mt-4 py-2" type="submit">
                                <i class="fas fa-check-circle me-2"></i> Simpan Transaksi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        document.addEventListener('DOMContentLoaded', function() {
            const customerNumberInput = document.getElementById('customerNumber');
            const customerInfoSpan = document.getElementById('customerInfo');
            const transactionDateSpan = document.getElementById('transactionDate');
            const transactionTimeSpan = document.getElementById('transactionTime');

            const filterKategoriSelect = document.getElementById('filterKategori');
            const menuCategorySections = document.querySelectorAll('.menu-category-section');

            const orderItemsContainer = document.getElementById('cartItems');
            const totalAmountSpan = document.getElementById('totalAmount');
            const orderForm = document.getElementById('orderForm');
            const orderDataInput = document.getElementById('orderDataInput');
            const formCustomerNumberInput = document.getElementById('formCustomerNumberInput');

            let cart = [];

            // --- Inisialisasi Tanggal dan Waktu ---
            const now = new Date();
            transactionDateSpan.textContent = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            transactionTimeSpan.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            // --- Fungsi untuk memperbarui No. Pelanggan di Ringkasan ---
            customerNumberInput.addEventListener('input', function() {
                customerInfoSpan.textContent = this.value || '-';
                formCustomerNumberInput.value = this.value;
            });
            customerInfoSpan.textContent = customerNumberInput.value || '-';
            formCustomerNumberInput.value = customerNumberInput.value;

            // --- Fungsi Filter Kategori Menu (di bagian KIRI) ---
            filterKategoriSelect.addEventListener('change', function() {
                const selectedCategoryId = this.value;
                menuCategorySections.forEach(section => {
                    if (selectedCategoryId === '' || section.dataset.categoryId == selectedCategoryId) { // Gunakan == untuk perbandingan longgar
                        section.style.display = 'block';
                    } else {
                        section.style.display = 'none';
                    }
                });
            });

            // --- Fungsi Tambah ke Keranjang ---
            const allAddButtons = document.querySelectorAll('.add-to-cart-btn');
            allAddButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const menuId = this.dataset.menuId;
                    const menuItemCard = this.closest('.menu-item');
                    const menuName = menuItemCard.querySelector('.card-title').textContent;
                    const menuPrice = parseFloat(menuItemCard.dataset.price);

                    const existingItemIndex = cart.findIndex(item => item.menu_id == menuId);

                    if (existingItemIndex > -1) {
                        cart[existingItemIndex].quantity++;
                        cart[existingItemIndex].subtotal = cart[existingItemIndex].quantity * cart[existingItemIndex].price;
                    } else {
                        cart.push({
                            menu_id: parseInt(menuId),
                            name: menuName,
                            price: menuPrice,
                            quantity: 1,
                            subtotal: menuPrice
                        });
                    }
                    renderCart();
                });
            });

            // --- Fungsi Render Keranjang ---
            function renderCart() {
                orderItemsContainer.innerHTML = '';
                let totalAmount = 0;

                if (cart.length === 0) {
                    orderItemsContainer.innerHTML = '<p class="text-muted text-center my-4">Belum ada pesanan</p>';
                } else {
                    cart.forEach((item, index) => {
                        totalAmount += item.subtotal;
                        orderItemsContainer.innerHTML += `
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <div>
                                    <span class="fw-bold">${item.name}</span> <br>
                                    <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')} x ${item.quantity}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold me-2">Rp ${item.subtotal.toLocaleString('id-ID')}</span>
                                    <button class="btn btn-sm btn-outline-secondary me-1 quantity-btn" onclick="updateCartItemQuantity(${index}, -1)">-</button>
                                    <span class="fw-bold mx-1">${item.quantity}</span>
                                    <button class="btn btn-sm btn-outline-secondary me-1 quantity-btn" onclick="updateCartItemQuantity(${index}, 1)">+</button>
                                    <button class="btn btn-sm btn-danger ms-2" onclick="removeCartItem(${index})"><i class="fas fa-trash"></i></button>
                                </div>
                            </li>
                        `;
                    });
                }
                totalAmountSpan.textContent = `Rp ${totalAmount.toLocaleString('id-ID')}`;
                orderDataInput.value = JSON.stringify(cart);
            }

            // --- Fungsi Update Kuantitas dari Tombol di Keranjang ---
            window.updateCartItemQuantity = function(index, change) {
                cart[index].quantity += change;
                if (cart[index].quantity <= 0) {
                    removeCartItem(index);
                } else {
                    cart[index].subtotal = cart[index].quantity * cart[index].price;
                    renderCart();
                }
            };

            // --- Fungsi Hapus Item dari Keranjang ---
            window.removeCartItem = function(index) {
                cart.splice(index, 1);
                renderCart();
            };

            // --- Handle Submit Form Transaksi ---
            orderForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (cart.length === 0) {
                    alert('Keranjang belanja masih kosong!');
                    return;
                }

                if (!customerNumberInput.value) {
                    alert('Nomor pelanggan harus diisi!');
                    customerNumberInput.focus();
                    return;
                }

                formCustomerNumberInput.value = customerNumberInput.value;
                orderDataInput.value = JSON.stringify(cart);

                this.submit();
            });

            // --- Handle Success/Error dari Server ---
            @if(session('success'))
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            // <<< PERUBAHAN DI SINI: Aktifkan kembali event listener untuk reset form >>>
            successModal._element.addEventListener('hidden.bs.modal', function () {
                cart = []; // Kosongkan keranjang
                renderCart(); // Render keranjang yang kosong
                customerNumberInput.value = ''; // Kosongkan nomor pelanggan
                customerInfoSpan.textContent = '-'; // Reset info pelanggan di ringkasan
                window.location.reload(); // Reload halaman untuk mendapatkan nomor pelanggan baru dan reset form lainnya
            });
        @endif

            // --- Logic Pencarian Menu ---
            const menuSearchInput = document.getElementById('menuSearchInput');
            const searchButton = document.getElementById('searchButton');

            function filterMenusBySearch() {
                const searchTerm = menuSearchInput.value.toLowerCase();
                const menuCategorySections = document.querySelectorAll('.menu-category-section');

                menuCategorySections.forEach(section => {
                    let sectionHasVisibleItems = false;
                    const menuItemsInThisSection = section.querySelectorAll('.menu-item');

                    menuItemsInThisSection.forEach(item => {
                        const menuName = item.querySelector('.card-title').textContent.toLowerCase();
                        if (menuName.includes(searchTerm)) {
                            item.style.display = 'block';
                            sectionHasVisibleItems = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    if (sectionHasVisibleItems || searchTerm === '') {
                        section.style.display = 'block';
                    } else {
                        section.style.display = 'none';
                    }
                });
            }

            menuSearchInput.addEventListener('keyup', filterMenusBySearch);
            searchButton.addEventListener('click', function(e) {
                e.preventDefault();
                filterMenusBySearch();
            });

            // Inisialisasi awal
            renderCart();
        });
    </script>
</body>
</html>