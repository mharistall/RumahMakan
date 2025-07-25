<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafc; }
        .card { border-radius: 0.75rem; }
        .table th, .table td { vertical-align: middle; }
        .table thead th { background-color: #e9ecef; } /* Light grey header */
        /* --- Gaya Sidebar dari dashbord.blade.php --- */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a8a, #1e40af);
            transition: all 0.3s;
        }
        .sidebar-link {
            transition: all 0.2s;
            color: white; /* Default link color */
            padding: 1rem; /* Padding for click area */
            display: flex;
            align-items: center;
            text-decoration: none; /* Remove underline */
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #fff;
            color: white; /* Keep color white on hover */
        }
        .active-link {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #fff;
            color: white; /* Keep color white when active */
        }
        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                z-index: 100;
                width: 250px;
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
        }
        /* --- End Gaya Sidebar --- */

        .rounded-circle-btn {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #007bff; /* Primary blue */
            color: white;
            font-weight: bold;
            text-decoration: none;
        }
        .rounded-circle-btn:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100"> <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0"> <div class="position-sticky pt-3 p-3 text-white"> <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-utensils fa-2x me-3"></i>
                        <h4 class="m-0">Rumah Makan Bissmillah</h4>
                    </div>
                    <hr class="bg-light">
                    <ul class="nav flex-column mt-4">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link sidebar-link p-3 {{ Request::routeIs('dashboard') ? 'active-link' : '' }}">
                                <i class="fas fa-tachometer-alt me-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('rekap.harian') }}" class="nav-link sidebar-link p-3 {{ Request::routeIs('rekap.harian') || Request::routeIs('rekap.harian.grafik') ? 'active-link' : '' }}">
                                <i class="fas fa-file-invoice-dollar me-3"></i>
                                <span>Laporan Harian</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('rekap.bulanan') }}" class="nav-link sidebar-link p-3 {{ Request::routeIs('rekap.bulanan') || Request::routeIs('rekap.bulanan.grafik') ? 'active-link' : '' }}">
                                <i class="fas fa-chart-bar me-3"></i>
                                <span>Laporan Bulanan</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Laporan Harian</li>
                        </ol>
                    </nav>

                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle-btn me-2">
                                {{ Auth::user()->name[0] ?? '?' }}
                            </div>
                            <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Pengguna' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                    </a>
                    <h2 class="h3 mb-0 text-center flex-grow-1">Laporan Harian Penjualan</h2>
                    <div style="width: 150px;"></div> </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ route('rekap.harian') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="dateFilter" class="form-label">Pilih Tanggal</label>
                                <input type="date" class="form-control" id="dateFilter" name="date" value="{{ $selectedDate }}">
                            </div>
                            <div class="col-md-3">
                                <label for="perPage" class="form-label">Tampilkan per halaman</label>
                                <select name="per_page" id="perPage" class="form-select">
                                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="{{ $transactions->total() }}" {{ $perPage == $transactions->total() ? 'selected' : '' }}>Semua</option>
                                </select>
                            </div>
                            <div class="col-md-5 d-flex justify-content-end align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter me-1"></i> Filter Laporan
                                </button>
                                <a href="{{ route('rekap.harian.grafik', ['year' => date('Y', strtotime($selectedDate)), 'month' => date('n', strtotime($selectedDate))]) }}" class="btn btn-info">
                                    <i class="fas fa-chart-line me-1"></i> Show Grafik
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><i class="fas fa-calendar-alt me-2"></i> Laporan Tanggal:</h5>
                                <p class="card-text fs-4 fw-bold">{{ date('d F Y', strtotime($selectedDate)) }}</p>
                                <p class="text-muted mb-0">Total Transaksi: {{ $transactionCount }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title text-success"><i class="fas fa-money-bill-wave me-2"></i> Total Pendapatan:</h5>
                                <p class="card-text fs-4 fw-bold">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                                <p class="text-muted mb-0">Didapatkan dari {{ $transactionCount }} transaksi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Detail Transaksi Harian</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Pelanggan</th>
                                        <th>Waktu</th>
                                        <th>Total</th>
                                        <th>Detail Item</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->customer_number ?? '-' }}</td>
                                            <td>{{ date('H:i', strtotime($transaction->transaction_date)) }}</td>
                                            <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                            <td>
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($transaction->details as $detail)
                                                        <li>{{ $detail->quantity }}x {{ $detail->menu->name ?? 'Menu tidak dikenal' }} (Rp {{ number_format($detail->subtotal, 0, ',', '.') }})</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada transaksi untuk tanggal ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Menampilkan {{ $transactions->firstItem() }} hingga {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                            </small>
                            {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }} </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Menu Terlaris per Kategori (Hari Ini)</h5>
                    </div>
                    <div class="card-body">
                        @forelse ($topSellingMenusPerCategory as $topMenu)
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div>
                                    <span class="fw-bold">{{ $topMenu['category_name'] }}:</span> {{ $topMenu['menu_name'] }}
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $topMenu['total_quantity'] }} Terjual</span>
                            </div>
                        @empty
                            <p class="text-center text-muted">Belum ada data menu terlaris untuk Hari ini.</p>
                        @endforelse
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set default tanggal filter ke tanggal hari ini saat pertama kali dimuat
            const dateFilterInput = document.getElementById('dateFilter');
            if (!dateFilterInput.value) { // Hanya set jika kosong (misalnya saat pertama load tanpa parameter)
                const today = new Date();
                const year = today.getFullYear();
                const month = (today.getMonth() + 1).toString().padStart(2, '0');
                const day = today.getDate().toString().padStart(2, '0');
                dateFilterInput.value = `${year}-${month}-${day}`;
            }
        });
    </script>
</body>
</html>