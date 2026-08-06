<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PeminjamanRuanganController extends Controller
{
    // index
    public function index()
    {
        $data = PeminjamanRuangan::with('ruangan')->get();
        return view('peminjaman_ruangan.index', compact('data'));
    }

    // form create
    public function create()
    {
        $ruangan = Ruangan::all();
        return view('peminjaman_ruangan.create', compact('ruangan'));
    }

    // simpan
    public function store(Request $request)
    {
        $request->validate([
            'nama_peminjam'   => 'required|string|max:255',
            'ruangan_id'      => 'required|exists:ruangan,id', // sesuai nama tabelmu
            'tanggal_mulai'   => 'required|date',
            'waktu_mulai'     => 'required',
            'tanggal_selesai' => 'required|date',
            'waktu_selesai'   => 'required',
            'keperluan'       => 'nullable|string',
        ]);

        try {
            $mulai = Carbon::parse($request->tanggal_mulai . ' ' . $request->waktu_mulai);
            $selesai = Carbon::parse($request->tanggal_selesai . ' ' . $request->waktu_selesai);

            if ($selesai <= $mulai) {
                return back()->withErrors(['waktu_selesai' => 'Waktu selesai harus setelah waktu mulai.'])->withInput();
            }

            $durasiMenit = $mulai->diffInMinutes($selesai);

            // Cek bentrok jadwal
            $conflicts = PeminjamanRuangan::where('ruangan_id', $request->ruangan_id)
                ->whereIn('status', ['pending', 'disetujui'])
                ->get();

            foreach ($conflicts as $c) {
                $cMulai = Carbon::parse($c->tanggal_mulai . ' ' . $c->waktu_mulai);
                $cSelesai = Carbon::parse($c->tanggal_selesai . ' ' . $c->waktu_selesai);
                
                if ($mulai < $cSelesai && $selesai > $cMulai) {
                    return back()->withErrors(['waktu_mulai' => 'Ruangan sudah dipesan pada rentang waktu ini.'])->withInput();
                }
            }

            PeminjamanRuangan::create([
                'user_id'        => auth()->check() ? auth()->id() : null, // nullable
                'nama_peminjam'  => $request->nama_peminjam,
                'ruangan_id'     => $request->ruangan_id,
                'tanggal_mulai'  => $request->tanggal_mulai,
                'waktu_mulai'    => $request->waktu_mulai,
                'tanggal_selesai'=> $request->tanggal_selesai,
                'waktu_selesai'  => $request->waktu_selesai,
                'durasi'         => $durasiMenit,
                'keperluan'      => $request->keperluan,
                'status'         => 'pending',
            ]);

            return redirect()->route('peminjaman-ruangan.index')
                ->with('success', 'Peminjaman ruangan berhasil ditambahkan!');
        } catch (\Throwable $e) {
            Log::error('PeminjamanRuangan store error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['general' => 'Terjadi kesalahan saat menyimpan. Cek log.']);
        }
    }

    // form edit
    public function edit($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);
        $ruangan = Ruangan::all();
        return view('peminjaman_ruangan.edit', compact('peminjaman', 'ruangan'));
    }

    // update
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_peminjam'   => 'required|string|max:255',
            'ruangan_id'      => 'required|exists:ruangan,id',
            'tanggal_mulai'   => 'required|date',
            'waktu_mulai'     => 'required',
            'tanggal_selesai' => 'required|date',
            'waktu_selesai'   => 'required',
            'keperluan'       => 'nullable|string',
        ]);

        try {
            $mulai = Carbon::parse($request->tanggal_mulai . ' ' . $request->waktu_mulai);
            $selesai = Carbon::parse($request->tanggal_selesai . ' ' . $request->waktu_selesai);

            if ($selesai <= $mulai) {
                return back()->withErrors(['waktu_selesai' => 'Waktu selesai harus setelah waktu mulai.'])->withInput();
            }

            $durasiMenit = $mulai->diffInMinutes($selesai);

            // Cek bentrok jadwal (kecuali peminjaman ini sendiri)
            $conflicts = PeminjamanRuangan::where('ruangan_id', $request->ruangan_id)
                ->where('id', '!=', $id)
                ->whereIn('status', ['pending', 'disetujui'])
                ->get();

            foreach ($conflicts as $c) {
                $cMulai = Carbon::parse($c->tanggal_mulai . ' ' . $c->waktu_mulai);
                $cSelesai = Carbon::parse($c->tanggal_selesai . ' ' . $c->waktu_selesai);
                
                if ($mulai < $cSelesai && $selesai > $cMulai) {
                    return back()->withErrors(['waktu_mulai' => 'Ruangan sudah dipesan pada rentang waktu ini.'])->withInput();
                }
            }

            $peminjaman = PeminjamanRuangan::findOrFail($id);
            $peminjaman->update([
                'nama_peminjam'  => $request->nama_peminjam,
                'ruangan_id'     => $request->ruangan_id,
                'tanggal_mulai'  => $request->tanggal_mulai,
                'waktu_mulai'    => $request->waktu_mulai,
                'tanggal_selesai'=> $request->tanggal_selesai,
                'waktu_selesai'  => $request->waktu_selesai,
                'durasi'         => $durasiMenit,
                'keperluan'      => $request->keperluan,
            ]);

            return redirect()->route('peminjaman-ruangan.index')
                ->with('success', 'Peminjaman ruangan berhasil diperbarui!');
        } catch (\Throwable $e) {
            Log::error('PeminjamanRuangan update error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['general' => 'Terjadi kesalahan saat menyimpan. Cek log.']);
        }
    }

    // destroy
    public function destroy($id)
    {
        try {
            $peminjaman = PeminjamanRuangan::findOrFail($id);
            $peminjaman->delete();
            return redirect()->route('peminjaman-ruangan.index')
                ->with('success', 'Peminjaman ruangan berhasil dihapus!');
        } catch (\Throwable $e) {
            Log::error('PeminjamanRuangan destroy error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
    // approve
    public function approve($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);
        $peminjaman->update(['status' => 'disetujui']);
        return redirect()->back()->with('success', 'Peminjaman ruangan berhasil disetujui.');
    }

    // reject
    public function reject($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);
        $peminjaman->update(['status' => 'ditolak']);
        return redirect()->back()->with('success', 'Peminjaman ruangan telah ditolak.');
    }
}
