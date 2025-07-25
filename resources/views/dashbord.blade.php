<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard RM Bismillah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* --- Gaya Sidebar dari harian.blade.php --- */
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

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
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

            <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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

                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2 card-hover">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Pendapatan Hari Ini</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalIncomeToday, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2 card-hover">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Pendapatan Bulan Ini</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalIncomeMonth, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2 card-hover">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Transaksi Hari Ini</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transactionCountToday }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2 card-hover">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Menu Terlaris (Hari Ini)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $topSellingMenuToday->menu->name ?? 'N/A' }} 
                                            @if($topSellingMenuToday) ({{ $topSellingMenuToday->total_quantity }}x) @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-utensils fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Pendapatan Per Bulan ({{ date('Y') }})</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                Tahun <span class="badge bg-primary">{{ date('Y') }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">{{ date('Y') }}</a></li>
                                <li><a class="dropdown-item" href="#">{{ date('Y') - 1 }}</a></li>
                                <li><a class="dropdown-item" href="#">{{ date('Y') - 2 }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Transaksi Terakhir</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Pelanggan Ke</th>
                                        <th>Total</th>
                                        <th>Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latestTransactions as $transaction)
                                        <tr>
                                            <td>{{ date('d M Y, H:i', strtotime($transaction->transaction_date)) }}</td>
                                            <td>{{ $transaction->customer_number ?? '-' }}</td>
                                            <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                            <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada transaksi terakhir.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) { // Pastikan tombol toggle ada
                sidebarToggle.addEventListener('click', function() {
                    document.querySelector('.sidebar').classList.toggle('active');
                });
            }

            // Income Chart (Sudah ada dari controller, tidak perlu hardcode data di sini)
            const ctx = document.getElementById('incomeChart').getContext('2d');
            const incomeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json(array_values($monthlyIncomeData)), // Data dari Controller
                        backgroundColor: 'rgba(78, 115, 223, 0.5)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
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
        });
    </script>
</body>
</html>