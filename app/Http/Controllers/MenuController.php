<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // Import Model Menu
use App\Models\Category; // Import Model Category
use Illuminate\Support\Facades\Storage; // Untuk mengelola penyimpanan file

class MenuController extends Controller
{
    // Method untuk menampilkan halaman kelola menu
    public function index()
    {
        // Mengambil semua kategori dari database
        $categories = Category::all();
        // Mengambil semua menu dengan relasi kategori
        $menus = Menu::with('category')->get(); // 'category' adalah nama relasi di Menu Model

        // Mengirim data ke view edit-menu.blade.php
        return view('edit-menu', compact('categories', 'menus'));
    }

    // Method untuk menyimpan menu baru ke database
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'category_id' => 'required|exists:kategorimenu,id', // category_id harus ada di tabel kategorimenu
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi file gambar
        ]);

        $imagePath = null;
        // Jika ada gambar yang diupload
        if ($request->hasFile('image')) {
            // Simpan gambar ke folder 'public/images/menus'
            $imagePath = $request->file('image')->store('images/menus', 'public');
        }

        // Buat menu baru di database
        Menu::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath, // Simpan path gambar di database
        ]);

        // Redirect kembali ke halaman edit menu dengan pesan sukses
        return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }
    public function update(Request $request, Menu $menu)
    {
        // Validasi input
        $request->validate([
            'category_id' => 'required|exists:kategorimenu,id',
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi file gambar baru
        ]);

        $imagePath = $menu->image; // Default menggunakan gambar lama

        // Jika ada gambar baru yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('images/menus', 'public');
        }

        // Perbarui data menu
        $menu->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    // Method untuk menghapus menu
    public function destroy(Menu $menu)
    {
        // Hapus gambar terkait jika ada
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        // Hapus record menu dari database
        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus!');
    }

    // --- Anda juga perlu method untuk Edit (update) dan Hapus (destroy) nantinya ---
    // public function edit(Menu $menu) { ... }
    // public function update(Request $request, Menu $menu) { ... }
    // public function destroy(Menu $menu) { ... }
}