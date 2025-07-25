<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafc; }
        .card { border-radius: 0.75rem; }
        .table th, .table td { vertical-align: middle; }
        .table thead th { background-color: #e9ecef; }
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
            background-color: #007bff;
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
<body class="bg-gray-100">
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0">
                <div class="position-sticky pt-3 p-3 text-white">
                    <div class="d-flex align-items-center mb-4">
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
                            <li class="breadcrumb-item active" aria-current="page">Laporan Bulanan</li>
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
                    <h2 class="h3 mb-0 text-center flex-grow-1">Laporan Bulanan Penjualan</h2>
                    <div style="width: 150px;"></div> </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ route('rekap.bulanan') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="monthFilter" class="form-label">Pilih Bulan</label>
                                <select class="form-select" id="monthFilter" name="month">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="yearFilter" class="form-label">Pilih Tahun</label>
                                <select class="form-select" id="yearFilter" name="year">
                                    @for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="perPage" class="form-label">Tampilkan per halaman</label>
                                <select name="per_page" id="perPage" class="form-select">
                                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="{{ $dailySummaries->total() }}" {{ $perPage == $dailySummaries->total() ? 'selected' : '' }}>Semua</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex justify-content-end align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><i class="fas fa-calendar-alt me-2"></i> Laporan Bulan:</h5>
                                <p class="card-text fs-4 fw-bold">{{ date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)) }}</p>
                                <p class="text-muted mb-0">Total Ringkasan Harian: {{ $dailySummaries->total() }} hari</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title text-success"><i class="fas fa-money-bill-wave me-2"></i> Total Pendapatan Bulan Ini:</h5>
                                <p class="card-text fs-4 fw-bold">Rp {{ number_format($totalMonthlyIncome, 0, ',', '.') }}</p>
                                <p class="text-muted mb-0">Didapatkan dari {{ $totalMonthlyTransactions }} transaksi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Ringkasan Pendapatan Harian Bulan Ini</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal Transaksi</th>
                                        <th>Total Pelanggan</th>
                                        <th>Total Uang Sehari</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dailySummaries as $summary)
                                        <tr>
                                            <td>{{ date('d F Y', strtotime($summary->transaction_date_only)) }}</td>
                                            <td>{{ $summary->total_customers_today }}</td>
                                            <td>Rp {{ number_format($summary->total_daily_income, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada ringkasan transaksi untuk bulan ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Menampilkan {{ $dailySummaries->firstItem() }} hingga {{ $dailySummaries->lastItem() }} dari {{ $dailySummaries->total() }} ringkasan harian
                            </small>
                            {{ $dailySummaries->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Menu Terlaris per Kategori (Bulan Ini)</h5>
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
                            <p class="text-center text-muted">Belum ada data menu terlaris untuk bulan ini.</p>
                        @endforelse
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Grafik Pendapatan Bulanan (Tahun Ini)</h5>
                        <a href="{{ route('rekap.bulanan.grafik', ['year' => $selectedYear]) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-line me-1"></i> Show Grafik
                        </a>
                    </div>
                <div class="card-body text-center text-muted">
                    <p>Klik "Show Grafik" untuk melihat grafik pendapatan bulanan secara detail.</p>
                </div>
            </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hapus semua Chart.js dan logic terkait grafik di sini
            // Karena grafik sudah di halaman terpisah (grafikbulanan.blade.php)

            // Sidebar toggle for mobile (jika ada)
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.querySelector('.sidebar').classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>