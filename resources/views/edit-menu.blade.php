<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafc; }
        .table thead th { background: #e5e7eb; }
        .table td, .table th { vertical-align: middle; }
        .img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; }
    </style>
</head>
<body>
<body class="bg-gray-50">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                <i class="fas fa-store me-2"></i> Aplikasi Admin
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
                            A
                        </div>
                        <span class="ms-2 d-none d-lg-inline">Admin</span> </a>
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
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-gray-800">Edit Menu</h2>
        <a href="/input-transaksi" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                <select class="form-select" id="filterKategori" style="width: 200px; display: inline-block;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
                </select>
                </div>
                <button class="btn btn-primary" id="tambahMenuBtn"><i class="fas fa-plus me-1"></i>Tambah Menu</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="menuTable">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Gambar</th>
                            <th style="width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data menu akan diisi oleh JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const categories = @json($categories);
    const menus = @json($menus); // Data menu dari controller

    // Fungsi untuk merender tabel menu
    function renderMenuTable() {
        const tbody = document.querySelector('#menuTable tbody');
        const filterId = document.getElementById('filterKategori').value;
        let filteredMenus = menus; // Gunakan variabel global 'menus'

        if (filterId) {
            filteredMenus = menus.filter(menu => menu.category_id == filterId);
        }

        tbody.innerHTML = ''; // Kosongkan tabel
        if (filteredMenus.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center">Tidak ada menu ditemukan.</td></tr>`;
        } else {
            filteredMenus.forEach(menu => {
                const categoryName = menu.category ? menu.category.name : 'Uncategorized';
                const imageUrl = menu.image ? `{{ asset('storage') }}/${menu.image}` : `{{ asset('images/default-menu.png') }}`; // Asumsi default-menu.png ada
                tbody.innerHTML += `
                    <tr>
                        <td>${categoryName}</td>
                        <td>${menu.name}</td>
                        <td>Rp ${parseFloat(menu.price).toLocaleString('id-ID')}</td>
                        <td><img src="${imageUrl}" class="img-thumb" alt="${menu.name}"></td>
                        <td>
                            <button class="btn btn-warning btn-sm me-1" onclick="editMenu(${menu.id})"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm" onclick="hapusMenu(${menu.id})"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `;
            });
        }
    }

    // Fungsi untuk mengisi modal Edit Menu dan menampilkannya
    window.editMenu = function(menuId) {
        const menu = menus.find(m => m.id === menuId);
        if (!menu) {
            alert('Menu tidak ditemukan!');
            return;
        }
        // Isi form modal edit
        document.getElementById('edit_menu_id').value = menu.id;
        document.getElementById('edit_category_id').value = menu.category_id;
        document.getElementById('edit_name').value = menu.name;
        document.getElementById('edit_price').value = menu.price;

        const currentImagePreview = document.getElementById('current_image_preview');
        if (menu.image) {
            currentImagePreview.src = `{{ asset('storage') }}/${menu.image}`;
            currentImagePreview.style.display = 'inline';
        } else {
            currentImagePreview.style.display = 'none';
            currentImagePreview.src = '';
        }

        // Set action form ke rute update yang benar
        const editMenuForm = document.getElementById('editMenuForm');
        editMenuForm.action = `{{ url('menu') }}/${menu.id}`; // Menggunakan url() untuk dynamic route

        const editMenuModal = new bootstrap.Modal(document.getElementById('editMenuModal'));
        editMenuModal.show();
    };

    // Fungsi untuk menghapus menu
    window.hapusMenu = function(menuId) {
        if (confirm('Anda yakin ingin menghapus menu ini? Tindakan ini tidak bisa dibatalkan.')) {
            // Buat form sementara untuk mengirim DELETE request
            const form = document.createElement('form');
            form.action = `{{ url('menu') }}/${menuId}`;
            form.method = 'POST';
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    };

    // Event listener saat dokumen selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // ... (kode JavaScript yang sudah ada)

    // --- Logic Pencarian Menu ---
    const menuSearchInput = document.getElementById('menuSearchInput');
    const searchButton = document.getElementById('searchButton');

    function filterMenusBySearch() {
        const searchTerm = menuSearchInput.value.toLowerCase();
        menuListContainers.forEach(section => {
            const menuItems = section.querySelectorAll('.menu-item');
            let sectionHasVisibleItems = false;
            menuItems.forEach(item => {
                const menuName = item.querySelector('.card-title').textContent.toLowerCase();
                if (menuName.includes(searchTerm)) {
                    item.style.display = 'block'; // Tampilkan item
                    sectionHasVisibleItems = true;
                } else {
                    item.style.display = 'none'; // Sembunyikan item
                }
            });
            // Sembunyikan/tampilkan seluruh bagian kategori jika tidak ada item yang cocok
            if (sectionHasVisibleItems || searchTerm === '') {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }

    // Event listener untuk input pencarian (real-time)
    menuSearchInput.addEventListener('keyup', filterMenusBySearch);

    // Event listener untuk tombol pencarian (mencegah submit form)
    searchButton.addEventListener('click', function(e) {
        e.preventDefault(); // Mencegah form submit
        filterMenusBySearch();
    });

        // renderKategoriDropdown(); // Sudah diisi oleh Blade, jadi tidak perlu lagi
        renderMenuTable();       // Render tabel menu

        // Event listener untuk filter kategori
        document.getElementById('filterKategori').addEventListener('change', renderMenuTable);

        // Event listener untuk tombol Tambah Menu (menampilkan modal)
        document.getElementById('tambahMenuBtn').addEventListener('click', function() {
            const tambahMenuModal = new bootstrap.Modal(document.getElementById('tambahMenuModal'));
            tambahMenuModal.show();
        });

        // Handle success message from session (setelah redirect dari controller)
        @if(session('success'))
            alert("{{ session('success') }}");
        @endif

        // Menangani error validasi dari session (jika ada)
        @if($errors->any())
            // Cek apakah error dari form tambah atau edit
            const hasAddErrors = ['category_id', 'name', 'price', 'image'].some(field => Object.keys(@json($errors->messages())).includes(field));

            if (hasAddErrors) {
                const tambahMenuModal = new bootstrap.Modal(document.getElementById('tambahMenuModal'));
                tambahMenuModal.show(); // Tampilkan modal tambah jika ada error
            } else {
                // Jika error bukan dari form tambah, mungkin dari form edit (perlu penanganan lebih lanjut jika ada error di form edit)
                alert('Terjadi kesalahan saat menyimpan perubahan. Silakan cek input Anda.');
            }
        @endif
    });
</script>
<div class="modal fade" id="tambahMenuModal" tabindex="-1" aria-labelledby="tambahMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="tambahMenuForm" method="POST" action="{{ route('menu.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMenuModalLabel">Tambah Menu Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            {{-- Kategori akan diisi oleh Blade dari Controller --}}
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Menu</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" required min="0">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar </label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editMenuForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT') <input type="hidden" name="menu_id" id="edit_menu_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMenuModalLabel">Edit Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="edit_category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Menu</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="edit_price" name="price" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Gambar (Kosongkan jika tidak diubah)</label>
                        <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                        <small class="text-muted mt-2 d-block">Gambar saat ini: <img id="current_image_preview" src="" class="img-thumb" alt="Current Image" style="display: none;"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
