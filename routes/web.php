<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem; 
use App\Models\User;
use App\Models\Cart;
use App\Models\Review; 
use App\Models\WebSetting;
use App\Models\Category;
use App\Models\Voucher;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

/*
|--------------------------------------------------------------------------
| 1. AREA PUBLIK (BERANDA & KATALOG)
|--------------------------------------------------------------------------
*/
Route::get('/', function (Request $request) {
    if ($request->has('search') && $request->search != '') {
        return redirect('/katalog/semua?search=' . urlencode($request->search));
    }

    $flashSale = FlashSale::with('items.product')->first();
    if ($flashSale && $flashSale->is_active && Carbon::parse($flashSale->end_time)->isFuture()) {
        $flashSaleProducts = $flashSale->items()->take(4)->get();
    } else {
        $flashSaleProducts = collect();
    }

    $featuredProducts = Product::where('is_featured', 1)->where('status', 'Tersedia')->latest()->take(8)->get();
    
    // TESTIMONI OTOMATIS: Ambil ulasan bintang 4 & 5 terbaru
    $testimonials = Review::with(['user', 'product'])->where('rating', '>=', 4)->latest()->take(6)->get();

    return view('welcome', compact('flashSale', 'flashSaleProducts', 'featuredProducts', 'testimonials'));
})->name('home');

Route::get('/katalog/{kategori?}', function (Request $request, $kategori = null) {
    $query = Product::query();
    $title = 'Semua Produk'; 
    $nama_kategori = 'Semua Produk';

    // 1. Pencarian
    if ($request->has('search') && $request->search != '') {
        $cari = $request->search;
        $query->where(function($q) use ($cari) {
            $q->where('nama_produk', 'like', '%' . $cari . '%')
              ->orWhere('deskripsi', 'like', '%' . $cari . '%')
              ->orWhere('kategori', 'like', '%' . $cari . '%');
        });
        $title = 'Pencarian: ' . $cari;
        $nama_kategori = 'Hasil Pencarian: "' . $cari . '"';
    } else {
        // 2. Filter Kategori
        if ($kategori && $kategori !== 'semua') {
            $keyword = str_replace('-', ' ', $kategori);
            $query->where('kategori', 'like', '%' . $keyword . '%');
            $nama_kategori = ucwords($keyword);
            $title = 'Katalog ' . $nama_kategori;
        }
        $query->where('is_featured', 0);
    }
    
    // Pastikan hanya yang tersedia
    $query->where('status', 'Tersedia');

    // 3. LOGIKA BARU: Pengurutan Harga (Sort)
    if ($request->has('sort')) {
        if ($request->sort == 'termurah') {
            $query->orderBy('harga', 'asc'); // Harga terendah ke tertinggi
        } elseif ($request->sort == 'termahal') {
            $query->orderBy('harga', 'desc'); // Harga tertinggi ke terendah
        } else {
            $query->latest(); // Default: Terbaru
        }
    } else {
        $query->latest(); // Default: Terbaru jika tidak ada filter
    }
    
    $products = $query->paginate(12)->withQueryString();
    
    return view('katalog', compact('products', 'kategori', 'title', 'nama_kategori'));
});

Route::get('/flash-sale', function () {
    $flashSale = FlashSale::with('items.product')->first();
    $flashSaleEnd = $flashSale ? $flashSale->end_time : Carbon::now()->addHours(24)->toDateTimeString();
    
    if ($flashSale && $flashSale->is_active && Carbon::parse($flashSale->end_time)->isFuture()) {
        $products = $flashSale->items;
    } else {
        $products = collect();
    }
    
    return view('flash-sale', [
        'flashSaleEnd' => $flashSaleEnd, 
        'products' => $products, 
        'title' => $flashSale->nama_kampanye ?? 'Flash Sale'
    ]);
});

Route::get('/cara-pemesanan', function () { return view('cara-pemesanan', ['title' => 'Cara Pemesanan']); });
Route::get('/panduan-ukuran', function () { return view('panduan-ukuran', ['title' => 'Panduan Ukuran']); });
Route::get('/kebijakan-pengembalian', function () { return view('kebijakan-pengembalian', ['title' => 'Kebijakan Pengembalian']); });
Route::get('/syarat-ketentuan', function () { return view('syarat-ketentuan', ['title' => 'Syarat & Ketentuan']); });
Route::get('/bantuan-faq', function () { return view('bantuan', ['title' => 'Bantuan & FAQ']); });

/*
|--------------------------------------------------------------------------
| 2. SISTEM LOGIN & REGISTER
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/');
    }
    return view('login', ['title' => 'Masuk']);
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Cek role: admin diarahkan ke dashboard admin
        if (Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard')->with('success', 'Selamat Datang, Admin!');
        }

        // User biasa diarahkan ke beranda
        return redirect('/')->with('success', 'Selamat Datang, ' . Auth::user()->name . '!');
    }
    return redirect('/login')->with('error', 'Email atau password salah.');
})->name('login.process');

Route::get('/admin/login', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/')->with('error', 'Anda sudah masuk sebagai pelanggan.');
    }
    return view('admin.login', ['title' => 'Login Admin']);
})->name('admin.login');

Route::post('/admin/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard')->with('success', 'Selamat Datang kembali, Admin!');
        }

        // Jika bukan admin, logout segera dan beri tahu error
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login')->with('error', 'Akses ditolak. Halaman ini hanya untuk Administrator.');
    }
    return redirect('/admin/login')->with('error', 'Email atau password salah.');
})->name('admin.login.process');

Route::post('/register', function (Request $request) {
    // Validasi input registrasi
    $request->validate([
        'name'                  => 'required|string|min:3|max:100',
        'email'                 => 'required|email|unique:users,email',
        'password'              => 'required|min:8|confirmed',
        'password_confirmation' => 'required',
    ], [
        'name.required'              => 'Nama lengkap wajib diisi.',
        'name.min'                   => 'Nama minimal 3 karakter.',
        'email.required'             => 'Email wajib diisi.',
        'email.email'                => 'Format email tidak valid.',
        'email.unique'               => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
        'password.required'          => 'Password wajib diisi.',
        'password.min'               => 'Password minimal 8 karakter.',
        'password.confirmed'         => 'Konfirmasi password tidak cocok.',
        'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
    ]);

    User::create([
        'name'      => $request->name,
        'email'     => $request->email,
        'password'  => bcrypt($request->password),
        'saldo'     => 0,
        'vip_paket' => 'REGULER',
        'role'      => 'user',
    ]);
    return redirect('/login')->with('success', 'Registrasi berhasil! Silakan masuk.');
})->name('register.process');

Route::get('/logout', function (Request $request) {
    $isAdmin = Auth::check() && Auth::user()->role === 'admin';
    Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
    if ($isAdmin) {
        return redirect('/admin/login')->with('success', 'Anda telah berhasil keluar dari Panel Admin.');
    }
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| 3. PROFIL, SALDO & MEMBERSHIP
|--------------------------------------------------------------------------
*/
// ====== ROUTE YANG BUTUH LOGIN (MIDDLEWARE auth.user) ======
Route::middleware('auth.user')->group(function () {

    Route::get('/profile', function () { return view('profile', ['title' => 'Profil Saya']); })->name('profile');
    Route::get('/ubah-profil', function () { return view('ubah-profil', ['title' => 'Ubah Profil']); });

    Route::post('/ubah-profil', function (Request $request) {
        $user = Auth::user();
        $user->update($request->except('image_base64', 'password'));
        if ($request->filled('password')) $user->password = bcrypt($request->password);
        if ($request->filled('image_base64')) {
            $folderPath = public_path('uploads/profile/');
            if (!File::isDirectory($folderPath)) File::makeDirectory($folderPath, 0777, true, true);
            $image_parts = explode(";base64,", $request->image_base64);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'profile_' . $user->id . '_' . time() . '.png';
            File::put($folderPath . $fileName, $image_base64);
            $user->foto = $fileName;
        }
        $user->save();
        return redirect('/profile')->with('success', 'Profil berhasil diperbarui!');
    });

    Route::get('/saldo-erna-pay', function () { return view('saldo', ['title' => 'Saldo ERNA Pay']); });
    Route::post('/saldo-erna-pay', function (Request $request) {
        $user = Auth::user();
        $nominal = (int) preg_replace('/[^0-9]/', '', $request->nominal);
        if ($nominal < 10000)  return redirect()->back()->with('error', 'Nominal top-up minimal Rp 10.000.');
        if ($nominal > 10000000) return redirect()->back()->with('error', 'Nominal top-up maksimal Rp 10.000.000.');
        $user->increment('saldo', $nominal);
        return redirect('/profile')->with('success', 'Top Up Berhasil! Saldo bertambah Rp ' . number_format($nominal, 0, ',', '.'));
    });

    Route::get('/membership-vip', function () { return view('membership-vip', ['title' => 'Membership VIP']); });
    Route::get('/beli-membership/{paket}', function ($paket) {
        $user = Auth::user();
        $harga = (strtolower($paket) == 'gold') ? 200000 : 50000;
        if ($user->saldo >= $harga) {
            $duration = (strtolower($paket) == 'gold') ? 6 : 1;
            $start = ($user->vip_paket == strtoupper($paket) && $user->member_until && Carbon::parse($user->member_until)->isFuture()) 
                ? Carbon::parse($user->member_until) 
                : Carbon::now();
            $memberUntil = $start->addMonths($duration);

            $user->decrement('saldo', $harga);
            $user->update([
                'vip_paket' => strtoupper($paket),
                'member_until' => $memberUntil->toDateString()
            ]);
            return redirect('/profile')->with('success', 'Berhasil Upgrade ke ' . strtoupper($paket));
        }
        return redirect('/saldo-erna-pay')->with('error', 'Saldo tidak mencukupi.');
    });

}); // end middleware auth.user (profil & membership)

/*
|--------------------------------------------------------------------------
| 4. KERANJANG & CHECKOUT
|--------------------------------------------------------------------------
*/
Route::middleware('auth.user')->group(function () {

Route::get('/keranjang', function () { 
    $user = Auth::user();
    $carts = Cart::with('product')->where('user_id', $user->id)->latest()->get();
    return view('keranjang', ['title' => 'Keranjang Belanja', 'carts' => $carts]); 
})->name('keranjang');

Route::post('/keranjang/tambah/{product_id}', function (Request $request, $product_id) {
    $user = Auth::user();
    if (!$user) return response()->json(['status' => 'login_required']);
    
    $variantId = $request->variant_id ?? null;
    
    $query = Cart::where('user_id', $user->id)->where('product_id', $product_id);
    if ($variantId) {
        $query->where('product_variant_id', $variantId);
    } else {
        $query->whereNull('product_variant_id');
    }
    
    $cart = $query->first();
    if ($cart) { 
        $cart->increment('jumlah'); 
    } else { 
        Cart::create(['user_id' => $user->id, 'product_id' => $product_id, 'product_variant_id' => $variantId, 'jumlah' => 1]); 
    }

    $totalIsiKeranjang = Cart::where('user_id', $user->id)->sum('jumlah');

    return response()->json([
        'status' => 'success',
        'message' => 'Produk berhasil ditambahkan ke keranjang!',
        'cart_count' => $totalIsiKeranjang
    ]);
});

Route::get('/keranjang/hapus/{id}', function ($id) {
    Cart::destroy($id);
    return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
});

Route::get('/beli-sekarang/{product_id}', function (Request $request, $product_id) {
    $user = Auth::user();
    if (!$user) return redirect('/login')->with('error', 'Silakan masuk (login) dulu untuk membeli.');
    
    $variantId = $request->variant_id ?? null;
    
    $query = Cart::where('user_id', $user->id)->where('product_id', $product_id);
    if ($variantId) {
        $query->where('product_variant_id', $variantId);
    } else {
        $query->whereNull('product_variant_id');
    }
    
    $cart = $query->first();
    if ($cart) { $cart->increment('jumlah'); } else { Cart::create(['user_id' => $user->id, 'product_id' => $product_id, 'product_variant_id' => $variantId, 'jumlah' => 1]); }
    return redirect('/checkout');
});

Route::get('/checkout', function () {
    $user = Auth::user(); if (!$user) return redirect('/login');
    $carts = Cart::with('product')->where('user_id', $user->id)->get();
    if ($carts->count() == 0) return redirect('/keranjang');
    return view('checkout', ['title' => 'Checkout Pesanan', 'carts' => $carts]);
});

Route::post('/checkout/proses', function (Request $request) {
    $user = Auth::user();
    $carts = Cart::with('product')->where('user_id', $user->id)->get();
    if ($carts->count() == 0) return redirect('/keranjang');

    $totalHarga = 0;
    $fs = FlashSale::where('is_active', true)->where('end_time', '>', Carbon::now())->first();
    
    foreach ($carts as $cart) { 
        $itemHarga = $cart->product->harga;
        
        // Cek Flash Sale
        if ($fs) {
            $fsItem = \App\Models\FlashSaleItem::where('flash_sale_id', $fs->id)->where('product_id', $cart->product_id)->first();
            if ($fsItem && $fsItem->kuota_stok > 0) {
                $itemHarga = $fsItem->harga_diskon;
            }
        }

        if ($cart->product_variant_id) {
            $variant = \App\Models\ProductVariant::find($cart->product_variant_id);
            if (!$variant || $variant->stok < $cart->jumlah) {
                return redirect()->back()->with('error', 'Stok varian tidak mencukupi.');
            }
        } else {
            if ($cart->product->stok < $cart->jumlah) {
                return redirect()->back()->with('error', 'Stok ' . $cart->product->nama_produk . ' tidak mencukupi.');
            }
        }
        $totalHarga += ($itemHarga * $cart->jumlah); 
    }

    $potonganVoucher = 0;
    $appliedVoucher = null;
    if ($request->filled('kode_voucher_input')) {
        $v = Voucher::where('code', $request->kode_voucher_input)
                    ->where('valid_until', '>=', Carbon::now())
                    ->where(function($q) { $q->where('limit', '>', 0)->orWhere('limit', -1); })
                    ->first();
        
        if ($v) { 
            if ($totalHarga >= $v->min_spend) {
                if ($v->type == 'percent') {
                    $potonganVoucher = $totalHarga * ($v->reward_amount / 100);
                } else {
                    $potonganVoucher = $v->reward_amount;
                }
                $appliedVoucher = $v;
            } else {
                return redirect()->back()->with('error', 'Minimal belanja untuk voucher ini adalah Rp ' . number_format($v->min_spend, 0, ',', '.'));
            }
        } else {
            return redirect()->back()->with('error', 'Voucher tidak valid atau sudah kadaluarsa.');
        }
    }

    $diskonVip = ($user->vip_paket == 'GOLD') ? $totalHarga * 0.05 : 0;
    $ongkir = ($user->vip_paket == 'GOLD' || $user->vip_paket == 'SILVER') ? 0 : 20000;
    $totalBayar = ($totalHarga - $diskonVip - $potonganVoucher) + $ongkir;
    if ($totalBayar < 0) $totalBayar = 0;

    $metode = $request->metode_pembayaran ?? 'ERNA Pay';
    $status = ($metode === 'ERNA Pay') ? 'Dikemas' : 'Menunggu Pembayaran';

    if ($metode === 'ERNA Pay') {
        if ($user->saldo < $totalBayar) return redirect()->back()->with('error', 'Saldo tidak mencukupi.');
        $user->decrement('saldo', $totalBayar);
    }

    $order = Order::create([
        'user_id' => $user->id, 'invoice' => 'INV/' . date('Ymd') . '/' . rand(100, 999),
        'total_harga' => $totalHarga, 'diskon' => $diskonVip + $potonganVoucher, 
        'ongkir' => $ongkir, 'total_bayar' => $totalBayar,
        'alamat_pengiriman' => $user->alamat . ' No.' . $user->no_rumah, 
        'status' => $status,
        'metode_pembayaran' => $metode,
        'catatan' => $request->catatan
    ]);

    foreach ($carts as $cart) {
        $hargaSatuan = $cart->product->harga;
        
        if ($fs) {
            $fsItem = \App\Models\FlashSaleItem::where('flash_sale_id', $fs->id)->where('product_id', $cart->product_id)->first();
            if ($fsItem && $fsItem->kuota_stok > 0) {
                $hargaSatuan = $fsItem->harga_diskon;
                $fsItem->decrement('kuota_stok', $cart->jumlah);
            }
        }

        OrderItem::create(['order_id' => $order->id, 'product_id' => $cart->product_id, 'product_variant_id' => $cart->product_variant_id, 'jumlah' => $cart->jumlah, 'harga_satuan' => $hargaSatuan]);
        
        if ($cart->product_variant_id) {
            \App\Models\ProductVariant::where('id', $cart->product_variant_id)->decrement('stok', $cart->jumlah);
        }
        $cart->product->decrement('stok', $cart->jumlah);
    }
    Cart::where('user_id', $user->id)->delete();
    
    if ($appliedVoucher && $appliedVoucher->limit > 0) {
        $appliedVoucher->decrement('limit');
    }
    return redirect('/checkout/success/' . $order->id)->with('success', 'Pesanan berhasil dibuat!');
});

Route::get('/checkout/success/{id}', function ($id) {
    if (!Auth::check()) return redirect('/login');
    $order = Order::where('user_id', Auth::id())->findOrFail($id);
    return view('checkout-success', ['order' => $order, 'title' => 'Pesanan Berhasil']);
});

// ---- MIDTRANS: Buat Snap Token via AJAX ----
Route::post('/checkout/midtrans-token', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['message' => 'Silakan login terlebih dahulu.'], 401);
    }
    $user = Auth::user();
    $carts = Cart::with('product')->where('user_id', $user->id)->get();
    if ($carts->count() == 0) {
        return response()->json(['message' => 'Keranjang kosong.'], 422);
    }

    $totalHarga = 0;
    $fs = FlashSale::where('is_active', true)->where('end_time', '>', Carbon::now())->first();
    $itemDetails = [];

    foreach ($carts as $cart) {
        $itemHarga = $cart->product->harga;
        if ($fs) {
            $fsItem = \App\Models\FlashSaleItem::where('flash_sale_id', $fs->id)->where('product_id', $cart->product_id)->first();
            if ($fsItem && $fsItem->kuota_stok > 0) {
                $itemHarga = $fsItem->harga_diskon;
            }
        }
        if ($cart->product_variant_id) {
            $variant = \App\Models\ProductVariant::find($cart->product_variant_id);
            if (!$variant || $variant->stok < $cart->jumlah) {
                return response()->json(['message' => 'Stok varian tidak mencukupi.'], 422);
            }
        } else {
            if ($cart->product->stok < $cart->jumlah) {
                return response()->json(['message' => 'Stok ' . $cart->product->nama_produk . ' tidak mencukupi.'], 422);
            }
        }
        $totalHarga += $itemHarga * $cart->jumlah;
        $itemDetails[] = [
            'id'       => $cart->product_id,
            'price'    => (int) $itemHarga,
            'quantity' => $cart->jumlah,
            'name'     => substr($cart->product->nama_produk, 0, 50),
        ];
    }

    $potonganVoucher = 0;
    $appliedVoucher  = null;
    if ($request->filled('kode_voucher_input')) {
        $v = Voucher::where('code', $request->kode_voucher_input)
                    ->where('valid_until', '>=', Carbon::now())
                    ->where(function($q) { $q->where('limit', '>', 0)->orWhere('limit', -1); })
                    ->first();
        if ($v && $totalHarga >= $v->min_spend) {
            $potonganVoucher = ($v->type == 'percent')
                ? $totalHarga * ($v->reward_amount / 100)
                : $v->reward_amount;
            $appliedVoucher = $v;
        } elseif ($v) {
            return response()->json(['message' => 'Minimal belanja tidak terpenuhi untuk voucher ini.'], 422);
        } else {
            return response()->json(['message' => 'Voucher tidak valid atau sudah kadaluarsa.'], 422);
        }
    }

    $diskonVip = ($user->vip_paket == 'GOLD') ? $totalHarga * 0.05 : 0;
    $ongkir    = ($user->vip_paket == 'GOLD' || $user->vip_paket == 'SILVER') ? 0 : 20000;
    $totalBayar = max(0, ($totalHarga - $diskonVip - $potonganVoucher) + $ongkir);

    // Tambahkan ongkir ke item detail Midtrans
    if ($ongkir > 0) {
        $itemDetails[] = ['id' => 'ONGKIR', 'price' => (int) $ongkir, 'quantity' => 1, 'name' => 'Biaya Pengiriman'];
    }
    // Kurangi diskon dari item detail Midtrans
    $totalDiskon = $diskonVip + $potonganVoucher;
    if ($totalDiskon > 0) {
        $itemDetails[] = ['id' => 'DISKON', 'price' => -(int) $totalDiskon, 'quantity' => 1, 'name' => 'Diskon'];
    }

    // Buat order dulu dengan status Menunggu Pembayaran
    $invoice = 'INV/' . date('Ymd') . '/' . rand(100, 999);
    $order = Order::create([
        'user_id'            => $user->id,
        'invoice'            => $invoice,
        'total_harga'        => $totalHarga,
        'diskon'             => $totalDiskon,
        'ongkir'             => $ongkir,
        'total_bayar'        => $totalBayar,
        'alamat_pengiriman'  => ($user->alamat ?? '') . ' No.' . ($user->no_rumah ?? ''),
        'status'             => 'Menunggu Pembayaran',
        'metode_pembayaran'  => 'Midtrans',
        'midtrans_order_id'  => $invoice,
        'catatan'            => $request->catatan,
    ]);

    // Simpan order items & kurangi stok
    foreach ($carts as $cart) {
        $hargaSatuan = $cart->product->harga;
        if ($fs) {
            $fsItem = \App\Models\FlashSaleItem::where('flash_sale_id', $fs->id)->where('product_id', $cart->product_id)->first();
            if ($fsItem && $fsItem->kuota_stok > 0) {
                $hargaSatuan = $fsItem->harga_diskon;
                $fsItem->decrement('kuota_stok', $cart->jumlah);
            }
        }
        OrderItem::create([
            'order_id'           => $order->id,
            'product_id'         => $cart->product_id,
            'product_variant_id' => $cart->product_variant_id,
            'jumlah'             => $cart->jumlah,
            'harga_satuan'       => $hargaSatuan,
        ]);
        if ($cart->product_variant_id) {
            \App\Models\ProductVariant::where('id', $cart->product_variant_id)->decrement('stok', $cart->jumlah);
        }
        $cart->product->decrement('stok', $cart->jumlah);
    }
    Cart::where('user_id', $user->id)->delete();
    if ($appliedVoucher && $appliedVoucher->limit > 0) {
        $appliedVoucher->decrement('limit');
    }

    // Panggil Midtrans Snap API
    \Midtrans\Config::$serverKey    = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    \Midtrans\Config::$isSanitized  = true;
    \Midtrans\Config::$is3ds        = true;

    $params = [
        'transaction_details' => [
            'order_id'     => $invoice,
            'gross_amount' => (int) $totalBayar,
        ],
        'customer_details' => [
            'first_name' => $user->name,
            'email'      => $user->email,
            'phone'      => $user->no_hp ?? '',
        ],
        'item_details' => $itemDetails,
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $order->update(['snap_token' => $snapToken]);
        return response()->json(['snap_token' => $snapToken, 'order_id' => $order->id]);
    } catch (\Exception $e) {
        // Rollback order jika gagal dapat token
        $order->delete();
        // Kembalikan stok (sederhana)
        return response()->json(['message' => 'Gagal menghubungi Midtrans: ' . $e->getMessage()], 500);
    }
});

// ---- MIDTRANS: Webhook / Notification Handler ----
Route::post('/midtrans/webhook', function (Request $request) {
    \Midtrans\Config::$serverKey    = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');

    $notif = new \Midtrans\Notification();

    $transactionStatus = $notif->transaction_status;
    $fraudStatus       = $notif->fraud_status;
    $orderId           = $notif->order_id; // ini = invoice kita

    $order = Order::where('midtrans_order_id', $orderId)->first();
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    if ($transactionStatus == 'capture') {
        $newStatus = ($fraudStatus == 'accept') ? 'Dikemas' : 'Menunggu Pembayaran';
    } elseif ($transactionStatus == 'settlement') {
        $newStatus = 'Dikemas';
    } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
        $newStatus = 'Dibatalkan';
        // Kembalikan stok jika dibatalkan
        if ($order->status !== 'Dibatalkan') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stok', $item->jumlah);
                }
                if ($item->product_variant_id) {
                    \App\Models\ProductVariant::where('id', $item->product_variant_id)->increment('stok', $item->jumlah);
                }
            }
        }
    } elseif ($transactionStatus == 'pending') {
        $newStatus = 'Menunggu Pembayaran';
    } else {
        $newStatus = $order->status;
    }

    $order->update([
        'status'             => $newStatus,
        'metode_pembayaran'  => $notif->payment_type ?? 'Midtrans',
    ]);

    // Kirim notifikasi ke user jika status berubah ke Dikemas (pembayaran diterima)
    if ($newStatus === 'Dikemas' && $order->status !== 'Dikemas') {
        Notification::kirim($order->user_id, [
            'type'    => 'order',
            'title'   => 'Pembayaran Diterima',
            'message' => "Pembayaran untuk pesanan {$order->invoice} telah dikonfirmasi. Pesanan sedang dikemas.",
            'url'     => url('/pesanan/lacak/' . $order->id),
            'icon'    => 'fa-check-circle',
            'color'   => '#2ECC71',
        ]);
    }

    return response()->json(['message' => 'OK']);
});

Route::get('/riwayat-pesanan', function () {
    return view('riwayat-pesanan', ['orders' => Order::where('user_id', Auth::id())->latest()->get(), 'title' => 'Riwayat Pesanan']);
})->name('riwayat.pesanan');

Route::post('/pesanan/diterima/{id}', function ($id) {
    $order = Order::findOrFail($id);
    if ($order->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Akses ditolak.');
    }
    $order->update(['status' => 'Selesai']);
    return redirect()->back()->with('success', 'Terima kasih! Pesanan telah selesai.');
});

Route::post('/pesanan/retur/{id}', function (Request $request, $id) {
    $order = Order::findOrFail($id);
    if ($order->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
    }
    
    $data = [
        'alasan_retur' => $request->alasan_retur,
        'status_retur' => 'Diajukan'
    ];

    if ($request->hasFile('bukti_retur')) {
        $file = $request->file('bukti_retur');
        $nama_file = time() . '_retur_' . rand(100, 999) . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/retur'), $nama_file);
        $data['bukti_retur'] = 'uploads/retur/' . $nama_file;
    }

    $order->update($data);
    
    \App\Models\Notification::create([
        'user_id' => $order->user_id, 
        'title' => 'Retur Diajukan', 
        'message' => "Retur untuk {$order->invoice} sedang diproses.",
        'type' => 'retur',
        'url' => url('/riwayat-pesanan'),
        'icon' => 'fa-undo',
        'color' => '#9B59B6'
    ]);

    return response()->json(['success' => true]);
});

    // --- NOTIFIKASI USER ---
    Route::post('/notifikasi/baca/{id}', function ($id) {
        $notif = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['is_read' => true]);
        return response()->json(['status' => 'success']);
    });

    Route::post('/notifikasi/baca-semua', function () {
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        return response()->json(['status' => 'success']);
    });

    Route::get('/notifikasi', function () {
        $notifications = Notification::where('user_id', Auth::id())->latest()->paginate(20);
        return view('notifikasi', ['title' => 'Notifikasi Saya', 'notifications' => $notifications]);
    });

}); // end middleware auth.user (keranjang & checkout)

/*
|--------------------------------------------------------------------------
| 5. AREA ADMIN PANEL (DILINDUNGI MIDDLEWARE ADMIN)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('admin')->group(function () {
    
    Route::get('/dashboard', function (Request $request) {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        // 1. Data Statistik Utama (Filtered by Date)
        $queryOrderSelesai = Order::where('status', 'LIKE', '%elesai%');
        if($request->has('start_date') && $request->has('end_date')) {
            $queryOrderSelesai->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalPendapatan = (clone $queryOrderSelesai)->sum('total_bayar');
        $orderSelesaiCount = (clone $queryOrderSelesai)->count();
        $aov = $orderSelesaiCount > 0 ? $totalPendapatan / $orderSelesaiCount : 0;

        $pesananAktif = Order::whereIn('status', ['Dikemas', 'Dikirim', 'Menunggu Pembayaran', 'Tertunda'])->count();
        $totalProduk = Product::count();
        $totalWishlist = Wishlist::count();
        $orders = Order::with('user')->latest()->take(5)->get();

        // --- HITUNG GROWTH (BULAN INI VS BULAN LALU) ---
        $thisMonthRevenue = Order::where('status', 'LIKE', '%elesai%')
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('total_bayar');
        $lastMonthRevenue = Order::where('status', 'LIKE', '%elesai%')
            ->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
            ->sum('total_bayar');
        
        $revenueGrowth = 0;
        if($lastMonthRevenue > 0) {
            $revenueGrowth = round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100);
        } elseif($thisMonthRevenue > 0) {
            $revenueGrowth = 100;
        }

        // 2. Data Grafik Penjualan (Trend) - Dynamic Range
        $chartLabels = [];
        $chartData = [];
        $daysDiff = $startDate->diffInDays($endDate);
        
        for ($i = $daysDiff; $i >= 0; $i--) {
            $date = (clone $endDate)->subDays($i);
            $chartLabels[] = $date->format('d M');
            $dayIncome = Order::whereDate('created_at', $date->toDateString())
                              ->where('status', 'LIKE', '%elesai%')
                              ->sum('total_bayar');
            $chartData[] = (int)$dayIncome;
        }

        // 3. Produk Terlaris (Top 5)
        $terlaris = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'LIKE', '%elesai%')
            ->select('products.nama_produk', DB::raw('SUM(order_items.jumlah) as total_terjual'))
            ->groupBy('products.nama_produk', 'order_items.product_id')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        $topProductsLabels = $terlaris->pluck('nama_produk')->toArray();
        $topProductsData = $terlaris->pluck('total_terjual')->toArray();
        if(empty($topProductsLabels)) { $topProductsLabels = ['Belum ada data']; $topProductsData = [0]; }

        // --- SALES BY CATEGORY ---
        $categorySales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'LIKE', '%elesai%')
            ->select('products.kategori', DB::raw('SUM(order_items.jumlah) as total_qty'))
            ->groupBy('products.kategori')
            ->get();
        $catLabels = $categorySales->pluck('kategori')->toArray();
        $catData = $categorySales->pluck('total_qty')->toArray();

        // --- ORDER STATUS DISTRIBUTION ---
        $statusDist = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        $statusLabels = $statusDist->pluck('status')->toArray();
        $statusData = $statusDist->pluck('count')->toArray();

        // --- TOP CUSTOMERS ---
        $topCustomers = User::where('role', 'user')
            ->withCount(['orders' => function($q) { $q->where('status', 'LIKE', '%elesai%'); }])
            ->withSum(['orders' => function($q) { $q->where('status', 'LIKE', '%elesai%'); }], 'total_bayar')
            ->orderByDesc('orders_sum_total_bayar')
            ->take(5)
            ->get();

        // 4. Peringatan Stok Menipis
        $lowStockProducts = Product::where('stok', '<', 5)->get();
        $lowStockVariants = \App\Models\ProductVariant::with('product')->where('stok', '<', 5)->get();

        $notifications = collect();
        $newOrders = Order::whereIn('status', ['Menunggu Pembayaran', 'Tertunda'])->latest()->take(3)->get();
        foreach($newOrders as $no) {
            $notifications->push(['type' => 'order', 'title' => 'Pesanan Baru', 'desc' => "Invoice {$no->invoice} menunggu konfirmasi.", 'time' => $no->created_at, 'icon' => 'fa-shopping-cart', 'color' => '#007BFF', 'url' => url('/admin/pesanan?search=' . $no->invoice)]);
        }
        $newReviews = Review::whereNull('balasan_admin')->latest()->take(3)->get();
        foreach($newReviews as $nr) {
            $notifications->push(['type' => 'review', 'title' => 'Ulasan Baru', 'desc' => "{$nr->user->name} memberikan rating {$nr->rating}.", 'time' => $nr->created_at, 'icon' => 'fa-star', 'color' => '#FFC107', 'url' => url('/admin/ulasan')]);
        }
        foreach($lowStockProducts->take(3) as $lp) {
            $notifications->push(['type' => 'stock', 'title' => 'Stok Menipis', 'desc' => "Produk '{$lp->nama_produk}' sisa {$lp->stok}.", 'time' => $lp->updated_at, 'icon' => 'fa-exclamation-triangle', 'color' => '#E84C3D', 'url' => url('/admin/produk?search=' . $lp->nama_produk)]);
        }
        
        $notifications = $notifications->sortByDesc('time')->take(10);

        return view('admin.dashboard', compact(
            'totalPendapatan', 'orderSelesaiCount', 'aov', 'pesananAktif', 'totalProduk', 'totalWishlist', 'orders',
            'topProductsLabels', 'topProductsData', 'chartLabels', 'chartData', 
            'lowStockProducts', 'lowStockVariants', 'notifications',
            'revenueGrowth', 'catLabels', 'catData', 'topCustomers', 'statusLabels', 'statusData', 'startDate', 'endDate'
        ));
    });

    Route::get('/pendapatan/print', function (Request $request) {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $orders = Order::where('status', 'LIKE', '%elesai%')
                       ->whereBetween('created_at', [$startDate, $endDate])
                       ->with('user')
                       ->get();
                       
        $totalPendapatan = $orders->sum('total_bayar');

        return view('admin.print-pendapatan', compact('orders', 'totalPendapatan', 'startDate', 'endDate'));
    })->name('admin.pendapatan.print');

    Route::get('/pendapatan/excel', function (Request $request) {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $orders = Order::where('status', 'LIKE', '%elesai%')
                       ->whereBetween('created_at', [$startDate, $endDate])
                       ->with('user')
                       ->get();

        $csvFileName = 'laporan_pendapatan_' . date('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($orders, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Laporan Pendapatan ERNA Thrifting']);
            fputcsv($file, ['Periode', $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')]);
            fputcsv($file, []);
            fputcsv($file, ['Tanggal', 'Invoice', 'Pelanggan', 'Total Bayar']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->created_at->format('d/m/Y H:i'),
                    $order->invoice,
                    $order->user->name ?? 'Guest',
                    $order->total_bayar
                ]);
            }
            fputcsv($file, []);
            fputcsv($file, ['TOTAL PENDAPATAN', '', '', $orders->sum('total_bayar')]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    })->name('admin.pendapatan.excel');

    Route::get('/wishlist', function() {
        if (!Auth::check()) return redirect('/login');
        $wishlists = Wishlist::with('product')->where('user_id', Auth::id())->latest()->get();
        return view('wishlist', ['wishlists' => $wishlists, 'title' => 'Wishlist Saya']);
    });

    Route::get('/produk', function(Request $request) { 
        $query = Product::latest();
        $trashedCount = Product::onlyTrashed()->count();
        
        if ($request->show_deleted == 'true') {
            $query->onlyTrashed();
        }
        if ($request->has('filter_kategori') && $request->filter_kategori != '') { $query->where('kategori', $request->filter_kategori); }
        return view('admin.produk', [
            'products' => $query->get(), 
            'categories' => Category::latest()->get(),
            'trashedCount' => $trashedCount
        ]); 
    });
    Route::post('/produk/simpan', function(Request $request) {
        $data = $request->except(['gambar_tambahan', 'variant_warna', 'variant_ukuran', 'variant_stok', '_token']);
        $data['status'] = 'Tersedia';
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar'); $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/produk'), $nama_file); $data['gambar'] = 'uploads/produk/' . $nama_file;
        }
        $product = Product::create($data); 

        if ($request->hasFile('gambar_tambahan')) {
            foreach ($request->file('gambar_tambahan') as $file) {
                $nama_file = time() . '_' . rand(100, 999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/produk'), $nama_file);
                \App\Models\ProductImage::create(['product_id' => $product->id, 'image_path' => 'uploads/produk/' . $nama_file]);
            }
        }
        
        if ($request->has('variant_stok')) {
            $warna = $request->variant_warna;
            $ukuran = $request->variant_ukuran;
            $stok = $request->variant_stok;
            
            $totalStok = 0;
            for ($i = 0; $i < count($stok); $i++) {
                if ($stok[$i] !== null && $stok[$i] !== '') {
                    \App\Models\ProductVariant::create([
                        'product_id' => $product->id,
                        'warna' => $warna[$i] ?? null,
                        'ukuran' => $ukuran[$i] ?? null,
                        'stok' => $stok[$i]
                    ]);
                    $totalStok += $stok[$i];
                }
            }
            if ($totalStok > 0) {
                $product->update(['stok' => $totalStok]);
            }
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambah!');
    });
    Route::get('/produk/edit/{id}', function($id) {
        $product = Product::findOrFail($id);
        $categories = Category::latest()->get();
        return view('admin.produk-edit', compact('product', 'categories'));
    });
    Route::post('/produk/update/{id}', function(Request $request, $id) {
        $product = Product::findOrFail($id);
        $data = $request->all(); $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar'); $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/produk'), $nama_file); $data['gambar'] = 'uploads/produk/' . $nama_file;
        }
        $product->update($data); 

        if ($request->hasFile('gambar_tambahan')) {
            foreach ($request->file('gambar_tambahan') as $file) {
                $nama_file = time() . '_' . rand(100, 999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/produk'), $nama_file);
                \App\Models\ProductImage::create(['product_id' => $product->id, 'image_path' => 'uploads/produk/' . $nama_file]);
            }
        }

        if ($request->has('delete_variant')) {
            \App\Models\ProductVariant::whereIn('id', $request->delete_variant)->delete();
        }

        if ($request->has('variant_id')) {
            $ids = $request->variant_id;
            $warna = $request->variant_warna;
            $ukuran = $request->variant_ukuran;
            $stok = $request->variant_stok;
            
            for ($i = 0; $i < count($ids); $i++) {
                if ($stok[$i] !== null && $stok[$i] !== '') {
                    if ($ids[$i] === 'new') {
                        \App\Models\ProductVariant::create([
                            'product_id' => $product->id,
                            'warna' => $warna[$i] ?? null,
                            'ukuran' => $ukuran[$i] ?? null,
                            'stok' => $stok[$i]
                        ]);
                    } else {
                        // Jangan update jika akan dihapus
                        if (!$request->has('delete_variant') || !in_array($ids[$i], $request->delete_variant)) {
                            \App\Models\ProductVariant::where('id', $ids[$i])->update([
                                'warna' => $warna[$i] ?? null,
                                'ukuran' => $ukuran[$i] ?? null,
                                'stok' => $stok[$i]
                            ]);
                        }
                    }
                }
            }
            
            // Update total stok
            $totalStok = \App\Models\ProductVariant::where('product_id', $product->id)->sum('stok');
            if ($totalStok > 0) {
                $product->update(['stok' => $totalStok]);
            }
        }

        return redirect('/admin/produk')->with('success', 'Data produk berhasil diperbarui!');
    });
    Route::get('/produk/hapus/{id}', function($id) {
        Product::destroy($id); return redirect()->back()->with('success', 'Produk berhasil dipindahkan ke tempat sampah.');
    });
    Route::get('/produk/restore/{id}', function($id) {
        Product::withTrashed()->find($id)->restore();
        return redirect()->back()->with('success', 'Produk berhasil dipulihkan!');
    });
    Route::get('/produk/hapus-permanen/{id}', function($id) {
        Product::withTrashed()->find($id)->forceDelete();
        return redirect()->back()->with('success', 'Produk dihapus secara permanen.');
    });


    Route::get('/kategori', function() { 
        return view('admin.kategori', ['categories' => Category::latest()->get()]); 
    });
    Route::post('/kategori/simpan', function(Request $request) {
        Category::create(['name' => $request->name, 'slug' => \Illuminate\Support\Str::slug($request->name)]);
        return redirect()->back()->with('success', 'Kategori berhasil ditambah!');
    });
    Route::get('/kategori/hapus/{id}', function($id) {
        Category::destroy($id); return redirect()->back()->with('success', 'Kategori dihapus!');
    });

    Route::get('/pelanggan', function() { 
        return view('admin.pelanggan', ['users' => User::latest()->get()]); 
    });

    Route::get('/flash-sale', function() { 
        return view('admin.flash-sale', [
            'flashSale' => FlashSale::first(),
            'products' => Product::all(),
            'flashSaleItems' => FlashSaleItem::with('product')->get()
        ]); 
    });
    Route::post('/flash-sale/simpan', function(Request $request) {
        $flashSale = FlashSale::first();
        if(!$flashSale) {
            FlashSale::create([
                'nama_kampanye' => $request->nama_kampanye,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_active' => true
            ]);
        } else {
            $flashSale->update([
                'nama_kampanye' => $request->nama_kampanye,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_active' => true
            ]);
        }
        return redirect()->back()->with('success', 'Waktu Flash Sale berhasil diperbarui!');
    });
    Route::get('/flash-sale/reset', function() {
        $fs = FlashSale::first(); if($fs) { $fs->update(['is_active' => false]); FlashSaleItem::truncate(); }
        return redirect()->back()->with('success', 'Flash Sale dihentikan!');
    });
    Route::post('/flash-sale/tambah-produk', function(Request $request) {
        $fs = FlashSale::first();
        if(!$fs) return redirect()->back()->with('error', 'Buat kampanye Flash Sale terlebih dahulu.');
        FlashSaleItem::create([
            'flash_sale_id' => $fs->id,
            'product_id' => $request->product_id,
            'harga_diskon' => $request->harga_diskon,
            'kuota_stok' => $request->kuota_stok
        ]);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke Flash Sale!');
    });
    Route::get('/flash-sale/hapus-produk/{id}', function($id) {
        FlashSaleItem::destroy($id);
        return redirect()->back()->with('success', 'Produk dihapus dari Flash Sale!');
    });

    Route::get('/ulasan', function() { 
        return view('admin.ulasan', ['reviews' => \App\Models\Review::with(['user', 'product'])->latest()->get()]); 
    });
    Route::post('/ulasan/balas', function(Request $request) {
        $request->validate(['review_id' => 'required', 'balasan' => 'required']);
        $review = \App\Models\Review::findOrFail($request->review_id);
        $review->update(['balasan_admin' => $request->balasan]);
        return redirect()->back()->with('success', 'Balasan ulasan berhasil disimpan!');
    });

    Route::get('/voucher', function() { 
        return view('admin.voucher', ['vouchers' => Voucher::latest()->get()]); 
    });
    Route::post('/voucher/simpan', function(Request $request) {
        Voucher::create([
            'code' => strtoupper($request->code),
            'type' => $request->type ?? 'fixed',
            'reward_amount' => $request->reward_amount,
            'min_spend' => $request->min_spend ?? 0,
            'limit' => $request->limit ?? -1,
            'expiry_date' => $request->expiry_date ?? 'Semua Pengguna',
            'valid_until' => $request->valid_until ?? Carbon::now()->addDays(30)
        ]);
        return redirect()->back()->with('success', 'Voucher berhasil diterbitkan!');
    });
    Route::get('/voucher/hapus/{id}', function($id) {
        Voucher::destroy($id); return redirect()->back()->with('success', 'Voucher telah dihapus.');
    });

    Route::get('/pengaturan', function() { 
        return view('admin.pengaturan', ['setting' => WebSetting::first()]); 
    });
    Route::post('/pengaturan/simpan', function(Request $request) {
        $setting = WebSetting::first();
        if(!$setting) WebSetting::create($request->all());
        else $setting->update($request->all());
        return redirect()->back()->with('success', 'Pengaturan Website berhasil diperbarui!');
    });

    Route::get('/pesanan', function(Request $request) { 
        $query = Order::with('user')->latest();
        if ($request->has('cari_pelanggan') && $request->cari_pelanggan != '') {
            $cari = $request->cari_pelanggan;
            $query->whereHas('user', function($q) use ($cari) { $q->where('name', 'like', '%' . $cari . '%'); });
        }
        return view('admin.pesanan', ['orders' => $query->get()]); 
    });
    Route::post('/pesanan/update/{id}', function(Request $request, $id) {
        $order = Order::with('items.product')->findOrFail($id);
        $oldStatus = $order->status;
        $oldReturStatus = $order->status_retur;
        
        $data = ['status' => $request->status];
        if ($request->has('no_resi')) {
            $data['no_resi'] = $request->no_resi;
        }
        if ($request->has('status_retur')) {
            $data['status_retur'] = $request->status_retur;
        }
        
        $order->update($data);

        // --- GENERATE NOTIFIKASI OTOMATIS UNTUK USER ---
        
        // 1. Notif Perubahan Status Pesanan
        if ($request->status != $oldStatus) {
            $notifData = [
                'type' => 'order',
                'url' => url('/pesanan/lacak/' . $order->id),
                'icon' => 'fa-box',
                'color' => '#D4AF37'
            ];

            if ($request->status == 'Dikemas') {
                $notifData['title'] = 'Pesanan Dikemas';
                $notifData['message'] = "Pesanan {$order->invoice} sedang disiapkan dan dikemas.";
            } elseif ($request->status == 'Dikirim') {
                $notifData['title'] = 'Pesanan Dikirim';
                $notifData['message'] = "Pesanan {$order->invoice} telah dikirimkan" . ($request->no_resi ? " dengan resi {$request->no_resi}." : ".");
                $notifData['icon'] = 'fa-shipping-fast';
                $notifData['color'] = '#3498DB';
            } elseif ($request->status == 'Selesai') {
                $notifData['title'] = 'Pesanan Selesai';
                $notifData['message'] = "Pesanan {$order->invoice} telah sampai dan selesai. Terima kasih!";
                $notifData['icon'] = 'fa-check-circle';
                $notifData['color'] = '#2ECC71';
            } elseif ($request->status == 'Dibatalkan') {
                $notifData['title'] = 'Pesanan Dibatalkan';
                $notifData['message'] = "Pesanan {$order->invoice} telah dibatalkan.";
                $notifData['icon'] = 'fa-times-circle';
                $notifData['color'] = '#E74C3C';
            }

            if (isset($notifData['title'])) {
                Notification::kirim($order->user_id, $notifData);
            }
        }

        // 2. Notif Perubahan Status Retur
        if ($request->has('status_retur') && $request->status_retur != $oldReturStatus) {
            $returNotif = [
                'type' => 'retur',
                'url' => url('/riwayat-pesanan'),
                'icon' => 'fa-undo',
                'color' => '#9B59B6'
            ];

            if ($request->status_retur == 'Disetujui') {
                $returNotif['title'] = 'Retur Disetujui';
                $returNotif['message'] = "Pengajuan retur untuk pesanan {$order->invoice} telah disetujui.";
            } elseif ($request->status_retur == 'Ditolak') {
                $returNotif['title'] = 'Retur Ditolak';
                $returNotif['message'] = "Pengajuan retur untuk pesanan {$order->invoice} ditolak.";
                $returNotif['color'] = '#E74C3C';
            }

            if (isset($returNotif['title'])) {
                Notification::kirim($order->user_id, $returNotif);
            }
        }

        if ($request->status == 'Dibatalkan' && $oldStatus != 'Dibatalkan') {
            foreach($order->items as $item) {
                if($item->product) {
                    $item->product->increment('stok', $item->jumlah);
                }
            }
        }
        
        return redirect()->back()->with('success', 'Status diperbarui dan notifikasi dikirim!');
    });

    Route::get('/pesanan/cetak-resi/{id}', function($id) {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.cetak-resi', compact('order'));
    });

    Route::get('/pesanan/cetak-invoice/{id}', function($id) {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.cetak-invoice', compact('order'));
    });
    Route::get('/laporan/cetak', function(Request $request) {
        $query = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'LIKE', '%elesai%');
            
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
        }

        $laporanProduk = $query->select(
                'products.nama_produk',
                'products.kategori',
                'order_items.harga_satuan',
                DB::raw('SUM(order_items.jumlah) as total_terjual'),
                DB::raw('SUM(order_items.jumlah * order_items.harga_satuan) as subtotal')
            )
            ->groupBy('products.nama_produk', 'products.kategori', 'order_items.product_id', 'order_items.harga_satuan')
            ->orderByDesc('total_terjual')
            ->get();
            
        $qStats = Order::where('status', 'LIKE', '%elesai%');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $qStats->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $totalPendapatan = (clone $qStats)->sum('total_bayar');
        $totalPesanan = (clone $qStats)->count();
        $avgOrderValue = $totalPesanan > 0 ? $totalPendapatan / $totalPesanan : 0;
        
        $laporanKategori = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'LIKE', '%elesai%')
            ->select('products.kategori', DB::raw('SUM(order_items.jumlah) as total_pcs'), DB::raw('SUM(order_items.jumlah * order_items.harga_satuan) as total_omzet'))
            ->groupBy('products.kategori')
            ->get();

        return view('admin.laporan-pdf', compact('laporanProduk', 'totalPendapatan', 'totalPesanan', 'avgOrderValue', 'laporanKategori'));
    });

    // KELOLA ULASAN (TAMBAHAN BARU)
    Route::get('/ulasan', function(Request $request) { 
        $query = \App\Models\Review::with(['user', 'product']);

        if ($request->filter == 'belum_dibalas') {
            $query->whereNull('balasan_admin');
        } elseif ($request->filter == 'sudah_dibalas') {
            $query->whereNotNull('balasan_admin');
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->get();
        return view('admin.ulasan', ['reviews' => $reviews]); 
    });

    Route::get('/ulasan/hapus/{id}', function($id) {
        \App\Models\Review::destroy($id); 
        return redirect()->back()->with('success', 'Ulasan berhasil dihapus!');
    });

});

/*
|--------------------------------------------------------------------------
| 6. DETAIL PRODUK, WISHLIST, & ULASAN
|--------------------------------------------------------------------------
*/
Route::get('/produk/detail/{id}', function ($id) {
    $p = Product::with(['reviews.user', 'images', 'variants'])->findOrFail($id);
    
    // Algoritma Produk Terkait: Kategori yang sama, acak, limit 4
    $relatedProducts = Product::where('kategori', $p->kategori)
        ->where('id', '!=', $p->id)
        ->where('status', 'Tersedia')
        ->inRandomOrder()
        ->take(4)
        ->get();

    // Fallback: Jika produk terkait < 4, ambil dari produk unggulan (Featured)
    if ($relatedProducts->count() < 4) {
        $needed = 4 - $relatedProducts->count();
        $featured = Product::where('is_featured', 1)
            ->where('id', '!=', $p->id)
            ->whereNotIn('id', $relatedProducts->pluck('id'))
            ->where('status', 'Tersedia')
            ->inRandomOrder()
            ->take($needed)
            ->get();
        $relatedProducts = $relatedProducts->concat($featured);
    }

    $flashSaleItem = null;
    $fs = FlashSale::where('is_active', true)->where('end_time', '>', Carbon::now())->first();
    if ($fs) {
        $flashSaleItem = \App\Models\FlashSaleItem::where('flash_sale_id', $fs->id)->where('product_id', $id)->first();
    }

    return view('detail-produk', [
        'product' => $p, 
        'relatedProducts' => $relatedProducts, 
        'title' => $p->nama_produk,
        'flashSaleItem' => $flashSaleItem,
        'flashSale' => $fs
    ]); 
});

Route::post('/wishlist/tambah/{product_id}', function ($product_id) {
    if (!Auth::check()) return response()->json(['status' => 'login_required']);
    
    $user = Auth::user();
    $w = Wishlist::where('user_id', $user->id)->where('product_id', $product_id)->first();
    
    if ($w) { 
        $w->delete(); 
        return response()->json([
            'status' => 'removed',
            'message' => 'Produk dihapus dari wishlist.'
        ]); 
    }
    
    Wishlist::create(['user_id' => $user->id, 'product_id' => $product_id]);
    
    return response()->json([
        'status' => 'added',
        'message' => 'Produk berhasil disimpan ke wishlist!'
    ]);
});

Route::get('/wishlist', function () {
    $user = Auth::user();
    if (!$user) return redirect('/login');
    $wishlists = Wishlist::with('product')->where('user_id', $user->id)->latest()->get();
    return view('wishlist', ['title' => 'Wishlist Saya', 'wishlists' => $wishlists]);
})->name('wishlist');

Route::post('/submit-ulasan', function(Request $request) {
    $data = $request->all();
    $data['user_id'] = Auth::id();
    
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $nama_file = time() . '_' . rand(100, 999) . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/ulasan'), $nama_file);
        $data['foto'] = 'uploads/ulasan/' . $nama_file;
    }

    Review::create($data);
    return response()->json(['success' => true]);
});

Route::get('/vouchers', function() {
    $vouchers = Voucher::where('valid_until', '>=', Carbon::now())
                       ->where(function($q) { $q->where('limit', '>', 0)->orWhere('limit', 0); })
                       ->latest()->get();
    return view('vouchers', ['vouchers' => $vouchers, 'title' => 'Pusat Voucher']);
});

Route::get('/pesanan/lacak/{id}', function($id) {
    if (!Auth::check()) return redirect('/login');
    $order = Order::with(['user', 'items.product'])->where('user_id', Auth::id())->findOrFail($id);
    return view('lacak-pesanan', ['order' => $order, 'title' => 'Lacak Pesanan #' . $order->invoice]);
});

/*
|--------------------------------------------------------------------------
| 7. SISTEM NOTIFIKASI USER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/notifikasi', function () {
        $notifications = \App\Models\Notification::where('user_id', Auth::id())->latest()->paginate(10);
        return view('notifikasi', ['notifications' => $notifications, 'title' => 'Pusat Notifikasi']);
    });

    Route::post('/notifikasi/mark-all-read', function () {
        \App\Models\Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    });

    // Harmonisasi dengan JS di notif-bell.blade.php
    Route::post('/notifikasi/baca/{id}', function ($id) {
        $notif = \App\Models\Notification::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });

    Route::post('/notifikasi/baca-semua', function () {
        \App\Models\Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });

    Route::post('/pesanan/upload-bukti/{id}', function (Illuminate\Http\Request $request, $id) {
    $request->validate([
        'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $order = Order::where('user_id', Auth::id())->findOrFail($id);
    
    if ($request->hasFile('bukti_pembayaran')) {
        $file = $request->file('bukti_pembayaran');
        $filename = 'bukti_' . $order->invoice . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/bukti'), $filename);
        
        $order->update([
            'bukti_pembayaran' => 'uploads/bukti/' . $filename,
            'status' => 'Tertunda' // Berubah dari Menunggu Pembayaran -> Tertunda (Siap Verifikasi Admin)
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah. Admin akan segera memverifikasi pesanan Anda.');
    }

    return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
});

Route::get('/api/notifications/unread-count', function () {
        $count = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
        return response()->json(['count' => $count]);
    });

    Route::get('/api/notifications/latest', function () {
        $notifications = \App\Models\Notification::where('user_id', Auth::id())->latest()->take(5)->get();
        return response()->json(['notifications' => $notifications]);
    });

    Route::post('/api/notifications/mark-as-read/{id}', function ($id) {
        $notif = \App\Models\Notification::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });
});

Route::get('/api/produk/search', function (Illuminate\Http\Request $request) {
    $q = $request->q;
    if (!$q || strlen($q) < 2) return response()->json([]);
    
    $products = App\Models\Product::where('nama_produk', 'LIKE', "%{$q}%")
        ->where('status', 'Tersedia')
        ->take(5)
        ->get()
        ->map(function($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama_produk,
                'harga' => 'Rp ' . number_format($p->harga, 0, ',', '.'),
                'gambar' => asset($p->gambar),
                'url' => url('/produk/detail/' . $p->id)
            ];
        });
        
    return response()->json($products);
});
