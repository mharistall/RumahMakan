<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Pendapatan Bulanan - {{ $selectedYear }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f8fafc; }
        .card { border-radius: 0.75rem; }
        /* --- Gaya Sidebar (Konsisten) --- */
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
        .chart-container {
            position: relative;
            height: 60vh;
            width: 100%;
        }
        @media (min-width: 992px) {
            .chart-container {
                height: 70vh;
            }
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
                            <li class="breadcrumb-item"><a href="{{ route('rekap.bulanan') }}">Laporan Bulanan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Grafik Bulanan</li>
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
                    <a href="{{ route('rekap.bulanan', ['month' => date('n'), 'year' => $selectedYear]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Laporan Bulanan
                    </a>
                    <h2 class="h3 mb-0 text-center flex-grow-1">Grafik Pendapatan Tahun {{ $selectedYear }}</h2>
                    <div style="width: 150px;"></div> </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ route('rekap.bulanan.grafik') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="yearFilter" class="form-label">Pilih Tahun</label>
                                <select class="form-select" id="yearFilter" name="year">
                                    @for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
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

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Grafik Pendapatan Bulanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyIncomeChart"></canvas>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthlyIncomeData = @json(array_values($monthlyIncomeData));
            const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

            const ctx = document.getElementById('monthlyIncomeChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: monthlyIncomeData,
                        backgroundColor: 'rgba(0, 123, 255, 0.6)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Sidebar toggle (jika ada)
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