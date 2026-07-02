<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanBuku;
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanBukuController extends Controller
{
   public function index() {
    $data = PeminjamanBuku::with(['user', 'buku'])->get();
    return view('peminjaman_buku.index', compact('data'));
}

public function create() {
    $buku = Buku::all(); // optional: get list of books
    $users = User::all();
    return view('peminjaman_buku.create', compact('buku', 'users'));
}

public function store(Request $request) {
    $request->validate([
        'user_id' => 'required',
        'buku_id' => 'required',
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        'status' => 'required|in:pending,dipinjam,dikembalikan',
    ]);

    $buku = Buku::findOrFail($request->buku_id);
    if ($buku->stok <= 0) {
        return back()->withErrors(['buku_id' => 'Stok buku ini sedang habis.'])->withInput();
    }

    PeminjamanBuku::create($request->all());
    return redirect()->route('peminjaman-buku.index')->with('success', 'Data berhasil ditambahkan');
}

public function edit($id) {
    $peminjaman = PeminjamanBuku::findOrFail($id);
    $buku = Buku::all();
    $users = User::all();
    return view('peminjaman_buku.edit', compact('peminjaman', 'buku', 'users'));
}

public function update(Request $request, $id) {
    $request->validate([
        'user_id' => 'required',
        'buku_id' => 'required',
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        'status' => 'required|in:pending,dipinjam,dikembalikan',
    ]);

    $peminjaman = PeminjamanBuku::findOrFail($id);
    $peminjaman->update($request->all());
    return redirect()->route('peminjaman-buku.index')->with('success', 'Data berhasil diupdate');
}

public function destroy($id) {
    PeminjamanBuku::destroy($id);
    return redirect()->route('peminjaman-buku.index')->with('success', 'Data berhasil dihapus');
}

public function approve($id) {
    $peminjaman = PeminjamanBuku::with('buku')->findOrFail($id);
    
    if ($peminjaman->buku->stok <= 0) {
        return redirect()->back()->with('error', 'Gagal menyetujui, stok buku saat ini habis.');
    }

    $peminjaman->update(['status' => 'dipinjam']);
    
    // Kurangi stok
    $peminjaman->buku->decrement('stok');

    return redirect()->back()->with('success', 'Peminjaman buku disetujui. Stok dikurangi.');
}

public function reject($id) {
    $peminjaman = PeminjamanBuku::findOrFail($id);
    // Karena enum di database hanya pending, dipinjam, dikembalikan,
    // kita akan menghapus data permohonan yang ditolak agar tidak mengotori tabel.
    $peminjaman->delete();
    return redirect()->back()->with('success', 'Peminjaman buku telah ditolak (dihapus).');
}

public function returnBook($id) {
    $peminjaman = PeminjamanBuku::with('buku')->findOrFail($id);
    $peminjaman->update(['status' => 'dikembalikan']);
    
    // Tambah stok kembali
    $peminjaman->buku->increment('stok');

    return redirect()->back()->with('success', 'Buku telah dikembalikan. Stok bertambah.');
}

}
