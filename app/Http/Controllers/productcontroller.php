<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Fungsi untuk memproses data dari form Tambah Produk di Admin
    public function store(Request $request)
    {
        // 1. Validasi data dasar
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'vip_tier' => 'required|string',
        ]);

        // 2. Siapkan array untuk disimpan ke database
        $productData = [
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'vip_tier' => $request->vip_tier,
            'icon' => $request->icon ?? 'fa-tshirt',
        ];

        // 3. Proses Gambar 1 (Jika ada gambar Base64 dari Javascript)
        if ($request->filled('image1_base64')) {
            $productData['image1'] = $this->saveBase64Image($request->image1_base64);
        }

        // Simpan ke Database!
        Product::create($productData);

        // Kembalikan Admin ke halaman dashboard dengan pesan sukses
        return back()->with('success', 'Produk berhasil ditambahkan ke Database!');
    }

    // Fungsi bantuan untuk mengubah teks Base64 menjadi file gambar sungguhan
    private function saveBase64Image($base64String)
    {
        // Pisahkan info tipe file dan datanya
        @list($type, $file_data) = explode(';', $base64String);
        @list(, $file_data) = explode(',', $file_data); 
        
        // Buat nama file unik
        $imageName = Str::random(10) . '.jpg';
        
        // Simpan file ke folder public/storage/products
        Storage::disk('public')->put('products/' . $imageName, base64_decode($file_data));
        
        // Kembalikan URL gambar agar bisa ditampilkan di website
        return '/storage/products/' . $imageName;
    }
}