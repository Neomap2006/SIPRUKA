<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PeminjamanRuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = PeminjamanRuangan::with(['ruangan', 'user', 'admin']);
        
        if (auth()->check() && auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }
        
        $data = $query->orderBy('created_at', 'desc')->get();
        
        // Sinkronkan status otomatis berdasarkan waktu riil (Asia/Jakarta)
        foreach ($data as $item) {
            $item->syncLifecycleStatus();
        }

        return view('peminjaman_ruangan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ruangan = Ruangan::all();
        $user = auth()->user();
        return view('peminjaman_ruangan.create', compact('ruangan', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_peminjam'   => 'required|string|max:255',
            'jenis_peminjam'  => 'required|in:individu,berorganisasi',
            'ruangan_id'      => 'required|exists:ruangan,id',
            'tanggal_mulai'   => 'required|date',
            'waktu_mulai'     => 'required',
            'tanggal_selesai' => 'required|date',
            'waktu_selesai'   => 'required',
            'keperluan'        => 'required|string|max:1000',
            'catatan_peminjam' => 'nullable|string|max:1000',
            'file_dokumen'     => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_sp'         => 'nullable|required_if:jenis_peminjam,berorganisasi|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'file_dokumen.required' => 'Dokumen identitas / NIPD wajib diunggah.',
            'file_sp.required_if'   => 'Surat Permohonan (SP) wajib diunggah untuk peminjaman berorganisasi.',
            'keperluan.required'    => 'Keperluan peminjaman wajib diisi.',
        ]);

        try {
            $mulai = Carbon::parse($request->tanggal_mulai . ' ' . $request->waktu_mulai);
            $selesai = Carbon::parse($request->tanggal_selesai . ' ' . $request->waktu_selesai);

            if ($selesai <= $mulai) {
                return back()->withErrors(['waktu_selesai' => 'Waktu selesai harus setelah waktu mulai.'])->withInput();
            }

            $durasiMenit = $mulai->diffInMinutes($selesai);
            $conflictError = null;

            // Handle file uploads
            $dokumenPath = null;
            if ($request->hasFile('file_dokumen')) {
                $dokumenPath = $request->file('file_dokumen')->store('dokumen_peminjaman', 'public');
            }

            $spPath = null;
            if ($request->hasFile('file_sp')) {
                $spPath = $request->file('file_sp')->store('dokumen_peminjaman', 'public');
            }

            DB::transaction(function () use ($request, $mulai, $selesai, $durasiMenit, $dokumenPath, $spPath, &$conflictError) {
                // Cek bentrok jadwal dengan DB Locking
                $conflicts = PeminjamanRuangan::with('ruangan')
                    ->where('ruangan_id', $request->ruangan_id)
                    ->whereIn('status', ['pending', 'disetujui', 'berlangsung'])
                    ->lockForUpdate()
                    ->get();

                foreach ($conflicts as $c) {
                    $c->syncLifecycleStatus();
                    
                    if (in_array($c->computed_status, ['selesai', 'ditolak', 'dibatalkan'])) {
                        continue;
                    }

                    $cMulai = Carbon::parse($c->tanggal_mulai . ' ' . $c->waktu_mulai);
                    $cSelesai = Carbon::parse($c->tanggal_selesai . ' ' . $c->waktu_selesai);
                    
                    if ($mulai < $cSelesai && $selesai > $cMulai) {
                        $tglStr = Carbon::parse($c->tanggal_mulai)->translatedFormat('d F Y');
                        if ($c->tanggal_mulai !== $c->tanggal_selesai) {
                            $tglStr .= ' s/d ' . Carbon::parse($c->tanggal_selesai)->translatedFormat('d F Y');
                        }
                        $waktuMulaiStr = Carbon::parse($c->waktu_mulai)->format('H:i');
                        $waktuSelesaiStr = Carbon::parse($c->waktu_selesai)->format('H:i');
                        $namaRuangan = $c->ruangan ? $c->ruangan->nama_ruangan : 'Ruangan';
                        $statusStr = ucfirst($c->computed_status);

                        $conflictError = "Jadwal bentrok. Ruangan {$namaRuangan} sudah memiliki peminjaman pada {$tglStr} pukul {$waktuMulaiStr} - {$waktuSelesaiStr} (Status: {$statusStr}). Silakan pilih waktu atau ruangan lain.";
                        return;
                    }
                }

                $newPeminjaman = PeminjamanRuangan::create([
                    'user_id'        => auth()->check() ? auth()->id() : null,
                    'nama_peminjam'  => $request->nama_peminjam,
                    'jenis_peminjam' => $request->jenis_peminjam,
                    'ruangan_id'     => $request->ruangan_id,
                    'tanggal_mulai'  => $request->tanggal_mulai,
                    'waktu_mulai'    => $request->waktu_mulai,
                    'tanggal_selesai'=> $request->tanggal_selesai,
                    'waktu_selesai'  => $request->waktu_selesai,
                    'durasi'         => $durasiMenit,
                    'keperluan'        => $request->keperluan,
                    'catatan_peminjam' => $request->catatan_peminjam,
                    'file_dokumen'     => $dokumenPath,
                    'file_sp'        => $spPath,
                    'status'         => 'pending',
                ]);

                // Kirim In-App Notification ke seluruh Admin
                $admins = \App\Models\User::where('role', 'admin')->get();
                $tglStr = Carbon::parse($request->tanggal_mulai)->translatedFormat('d F Y');
                foreach ($admins as $admin) {
                    \App\Models\Notification::create([
                        'user_id' => $admin->id,
                        'title'   => '📋 Pengajuan Peminjaman Baru',
                        'message' => "{$request->nama_peminjam} mengajukan peminjaman ruangan untuk tanggal {$tglStr}.",
                        'type'    => 'pengajuan_baru',
                        'link'    => route('peminjaman-ruangan.index', [], false),
                    ]);
                }

                // Kirim Notifikasi WhatsApp Instan ke Admin via Fonnte Gateway
                try {
                    $waService = app(\App\Services\WhatsAppService::class);
                    $recipientsStr = \App\Models\AppConfig::getValue('WhatsApp_Recipients', '');
                    $recipients = array_filter(array_map('trim', explode(',', $recipientsStr)));

                    if (empty($recipients)) {
                        foreach ($admins as $ad) {
                            if (!empty($ad->no_hp)) {
                                $recipients[] = $ad->no_hp;
                            }
                        }
                    }

                    if (!empty($recipients)) {
                        $ruanganModel = \App\Models\Ruangan::find($request->ruangan_id);
                        $namaRuangan = $ruanganModel ? $ruanganModel->nama_ruangan : 'Ruangan';
                        $tglMulaiStr = Carbon::parse($request->tanggal_mulai)->translatedFormat('d F Y');
                        $waktuMulai = substr($request->waktu_mulai, 0, 5);
                        $waktuSelesai = substr($request->waktu_selesai, 0, 5);

                        $waMsg = "📌 *PENGAJUAN RUANGAN BARU*\n";
                        $waMsg .= "Nomor: *{$newPeminjaman->nomor_pengajuan}*\n";
                        $waMsg .= "Peminjam: *{$request->nama_peminjam}*\n";
                        $waMsg .= "Ruangan: *{$namaRuangan}*\n";
                        $waMsg .= "Waktu: {$tglMulaiStr} ({$waktuMulai} - {$waktuSelesai} WIB)\n";
                        $waMsg .= "Keperluan: {$request->keperluan}\n";

                        if (!empty($request->catatan_peminjam)) {
                            $waMsg .= "\n💬 *Pesan Khusus Mahasiswa:*\n\"{$request->catatan_peminjam}\"\n";
                        }

                        $waMsg .= "\nMohon buka sistem SIPRUKA untuk verifikasi pengajuan ini.";

                        foreach (array_unique($recipients) as $targetPhone) {
                            $waService->sendMessage($targetPhone, $waMsg, 'NEW_BOOKING_ALERT');
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('Gagal mengirim WhatsApp alert booking baru: ' . $e->getMessage());
                }
            });

            if ($conflictError) {
                // Delete uploaded files on conflict
                if ($dokumenPath) Storage::disk('public')->delete($dokumenPath);
                if ($spPath) Storage::disk('public')->delete($spPath);

                $recommended = $this->getRecommendedRuangan($request->ruangan_id, $mulai, $selesai);

                $redirect = back()->withErrors(['overlap_error' => $conflictError])->withInput();

                if (!empty($recommended)) {
                    $redirect->with('recommended_ruangan', $recommended);
                } else {
                    $redirect->with('no_recommendation', true);
                }

                return $redirect;
            }

            return redirect()->route('peminjaman-ruangan.index')
                ->with('success', 'Pengajuan peminjaman ruangan berhasil dikirim dan menunggu verifikasi Admin.');
        } catch (\Throwable $e) {
            Log::error('PeminjamanRuangan store error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['general' => 'Terjadi kesalahan saat menyimpan. Cek log: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $peminjaman = PeminjamanRuangan::with(['ruangan', 'user', 'admin'])->findOrFail($id);

        // RBAC Authorization Backend: Mahasiswa hanya boleh melihat peminjaman miliknya sendiri
        if (auth()->check() && auth()->user()->role !== 'admin' && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat detail peminjaman ini.');
        }

        // Sinkronkan status otomatis
        $peminjaman->syncLifecycleStatus();

        // Pengecekan notifikasi selesai anti-duplikasi
        $showCompletedNotification = false;
        if ($peminjaman->computed_status === 'selesai' && auth()->check() && $peminjaman->user_id === auth()->id()) {
            if (is_null($peminjaman->notified_completed_at)) {
                $peminjaman->notified_completed_at = now();
                $peminjaman->save();
                $showCompletedNotification = true;
            }
        }

        return view('peminjaman_ruangan.show', compact('peminjaman', 'showCompletedNotification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);

        if (auth()->check() && auth()->user()->role !== 'admin' && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah peminjaman ini.');
        }

        $ruangan = Ruangan::all();
        return view('peminjaman_ruangan.edit', compact('peminjaman', 'ruangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);

        if (auth()->check() && auth()->user()->role !== 'admin' && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengubah peminjaman ini.');
        }

        $request->validate([
            'nama_peminjam'   => 'required|string|max:255',
            'jenis_peminjam'  => 'required|in:individu,berorganisasi',
            'ruangan_id'      => 'required|exists:ruangan,id',
            'tanggal_mulai'   => 'required|date',
            'waktu_mulai'     => 'required',
            'tanggal_selesai' => 'required|date',
            'waktu_selesai'   => 'required',
            'keperluan'       => 'required|string|max:1000',
            'file_dokumen'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_sp'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            $mulai = Carbon::parse($request->tanggal_mulai . ' ' . $request->waktu_mulai);
            $selesai = Carbon::parse($request->tanggal_selesai . ' ' . $request->waktu_selesai);

            if ($selesai <= $mulai) {
                return back()->withErrors(['waktu_selesai' => 'Waktu selesai harus setelah waktu mulai.'])->withInput();
            }

            $durasiMenit = $mulai->diffInMinutes($selesai);
            $conflictError = null;

            // Upload files if updated
            $dokumenPath = $peminjaman->file_dokumen;
            if ($request->hasFile('file_dokumen')) {
                if ($dokumenPath && Storage::disk('public')->exists($dokumenPath)) {
                    Storage::disk('public')->delete($dokumenPath);
                }
                $dokumenPath = $request->file('file_dokumen')->store('dokumen_peminjaman', 'public');
            }

            $spPath = $peminjaman->file_sp;
            if ($request->hasFile('file_sp')) {
                if ($spPath && Storage::disk('public')->exists($spPath)) {
                    Storage::disk('public')->delete($spPath);
                }
                $spPath = $request->file('file_sp')->store('dokumen_peminjaman', 'public');
            }

            DB::transaction(function () use ($request, $id, $peminjaman, $mulai, $selesai, $durasiMenit, $dokumenPath, $spPath, &$conflictError) {
                // Cek bentrok jadwal (kecuali peminjaman ini sendiri)
                $conflicts = PeminjamanRuangan::with('ruangan')
                    ->where('ruangan_id', $request->ruangan_id)
                    ->where('id', '!=', $id)
                    ->whereIn('status', ['pending', 'disetujui', 'berlangsung'])
                    ->lockForUpdate()
                    ->get();

                foreach ($conflicts as $c) {
                    $c->syncLifecycleStatus();

                    if (in_array($c->computed_status, ['selesai', 'ditolak', 'dibatalkan'])) {
                        continue;
                    }

                    $cMulai = Carbon::parse($c->tanggal_mulai . ' ' . $c->waktu_mulai);
                    $cSelesai = Carbon::parse($c->tanggal_selesai . ' ' . $c->waktu_selesai);
                    
                    if ($mulai < $cSelesai && $selesai > $cMulai) {
                        $tglStr = Carbon::parse($c->tanggal_mulai)->translatedFormat('d F Y');
                        if ($c->tanggal_mulai !== $c->tanggal_selesai) {
                            $tglStr .= ' s/d ' . Carbon::parse($c->tanggal_selesai)->translatedFormat('d F Y');
                        }
                        $waktuMulaiStr = Carbon::parse($c->waktu_mulai)->format('H:i');
                        $waktuSelesaiStr = Carbon::parse($c->waktu_selesai)->format('H:i');
                        $namaRuangan = $c->ruangan ? $c->ruangan->nama_ruangan : 'Ruangan';
                        $statusStr = ucfirst($c->computed_status);

                        $conflictError = "Jadwal bentrok. Ruangan {$namaRuangan} sudah memiliki peminjaman pada {$tglStr} pukul {$waktuMulaiStr} - {$waktuSelesaiStr} (Status: {$statusStr}). Silakan pilih waktu atau ruangan lain.";
                        return;
                    }
                }

                $peminjaman->update([
                    'nama_peminjam'   => $request->nama_peminjam,
                    'jenis_peminjam'  => $request->jenis_peminjam,
                    'ruangan_id'      => $request->ruangan_id,
                    'tanggal_mulai'   => $request->tanggal_mulai,
                    'waktu_mulai'     => $request->waktu_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'waktu_selesai'   => $request->waktu_selesai,
                    'durasi'          => $durasiMenit,
                    'keperluan'       => $request->keperluan,
                    'file_dokumen'    => $dokumenPath,
                    'file_sp'         => $spPath,
                ]);
            });

            if ($conflictError) {
                $recommended = $this->getRecommendedRuangan($request->ruangan_id, $mulai, $selesai, $id);

                $redirect = back()->withErrors(['overlap_error' => $conflictError])->withInput();

                if (!empty($recommended)) {
                    $redirect->with('recommended_ruangan', $recommended);
                } else {
                    $redirect->with('no_recommendation', true);
                }

                return $redirect;
            }

            return redirect()->route('peminjaman-ruangan.index')
                ->with('success', 'Peminjaman ruangan berhasil diperbarui!');
        } catch (\Throwable $e) {
            Log::error('PeminjamanRuangan update error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['general' => 'Terjadi kesalahan saat menyimpan. Cek log.']);
        }
    }

    /**
     * Ajukan Kembali / Perbaikan Berkas (Flowchart Sistem Revisi & Activity Diagram)
     */
    public function ajukanKembali(Request $request, $id)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);

        if (auth()->check() && auth()->user()->role !== 'admin' && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengajukan kembali peminjaman ini.');
        }

        $request->validate([
            'keperluan'    => 'required|string|max:1000',
            'file_dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_sp'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $dokumenPath = $peminjaman->file_dokumen;
        if ($request->hasFile('file_dokumen')) {
            if ($dokumenPath && Storage::disk('public')->exists($dokumenPath)) {
                Storage::disk('public')->delete($dokumenPath);
            }
            $dokumenPath = $request->file('file_dokumen')->store('dokumen_peminjaman', 'public');
        }

        $spPath = $peminjaman->file_sp;
        if ($request->hasFile('file_sp')) {
            if ($spPath && Storage::disk('public')->exists($spPath)) {
                Storage::disk('public')->delete($spPath);
            }
            $spPath = $request->file('file_sp')->store('dokumen_peminjaman', 'public');
        }

        $peminjaman->update([
            'status'           => 'pending',
            'alasan_penolakan' => null,
            'keperluan'        => $request->keperluan,
            'file_dokumen'     => $dokumenPath,
            'file_sp'          => $spPath,
        ]);

        // Kirim In-App Notification ke seluruh Admin saat ada perbaikan pengajuan
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'title'   => '🔄 Perbaikan Pengajuan Peminjaman',
                'message' => "{$peminjaman->nama_peminjam} telah memperbarui berkas untuk pengajuan ({$peminjaman->nomor_pengajuan}).",
                'type'    => 'pengajuan_baru',
                'link'    => route('peminjaman-ruangan.show', $peminjaman->id, false),
            ]);
        }

        return redirect()->route('peminjaman-ruangan.show', $id)
            ->with('success', 'Berkas berhasil diperbaiki dan pengajuan telah diajukan kembali ke Admin untuk diverifikasi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $peminjaman = PeminjamanRuangan::findOrFail($id);
            
            // Otorisasi: Admin atau pemilik data yang boleh menghapus
            if (auth()->check() && auth()->user()->role !== 'admin' && $peminjaman->user_id !== auth()->id()) {
                abort(403, 'Anda tidak berhak menghapus peminjaman ini.');
            }

            if ($peminjaman->file_dokumen && Storage::disk('public')->exists($peminjaman->file_dokumen)) {
                Storage::disk('public')->delete($peminjaman->file_dokumen);
            }
            if ($peminjaman->file_sp && Storage::disk('public')->exists($peminjaman->file_sp)) {
                Storage::disk('public')->delete($peminjaman->file_sp);
            }

            $peminjaman->delete();
            return redirect()->route('peminjaman-ruangan.index')
                ->with('success', 'Peminjaman ruangan berhasil dihapus!');
        } catch (\Throwable $e) {
            Log::error('PeminjamanRuangan destroy error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    /**
     * Approve & Terbitkan Izin Digital (Class Diagram, Sequence Diagram, Activity Diagram)
     */
    public function approve($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang berhak menyetujui peminjaman.');
        }

        $peminjaman = PeminjamanRuangan::with(['ruangan', 'user'])->findOrFail($id);
        
        // Terbitkan Izin Digital
        $nomorSurat = $peminjaman->terbitkanIzinDigital(auth()->user());

        // Kirim In-App Notification ke Pemohon
        if ($peminjaman->user_id) {
            \App\Models\Notification::create([
                'user_id' => $peminjaman->user_id,
                'title'   => '✅ Peminjaman Disetujui',
                'message' => "Pengajuan peminjaman ruangan {$peminjaman->ruangan->nama_ruangan} ({$peminjaman->nomor_pengajuan}) telah DISETUJUI. Nomor Surat Izin: {$nomorSurat}.",
                'type'    => 'disetujui',
                'link'    => route('peminjaman-ruangan.show', $peminjaman->id, false),
            ]);
        }

        // Kirim Notifikasi WhatsApp via Fonnte
        try {
            $waService = app(\App\Services\WhatsAppService::class);
            $userPhone = $peminjaman->user ? ($peminjaman->user->no_hp ?? null) : null;
            if (!empty($userPhone)) {
                $waMessage = "Halo {$peminjaman->nama_peminjam}, pengajuan peminjaman ruangan {$peminjaman->ruangan->nama_ruangan} (ID: {$peminjaman->nomor_pengajuan}) telah DISETUJUI oleh Admin. Nomor Surat Izin: {$nomorSurat}. Anda dapat mengunduh Surat Izin Digital di sistem SIPRUKA.";
                $waService->sendMessage($userPhone, $waMessage, 'APPROVAL');
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim WhatsApp approval: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', "Peminjaman berhasil disetujui & Surat Izin Digital resmi ({$nomorSurat}) telah diterbitkan.");
    }

    /**
     * Reject Pengajuan (Wajib Alasan Penolakan)
     */
    public function reject(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang berhak menolak peminjaman.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi untuk panduan perbaikan berkas pemohon.',
        ]);

        $peminjaman = PeminjamanRuangan::with(['ruangan', 'user'])->findOrFail($id);
        $peminjaman->update([
            'status'           => 'ditolak',
            'admin_id'         => auth()->id(),
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        // Kirim In-App Notification ke Pemohon memuat Alasan Penolakan persis yang diisi Admin
        if ($peminjaman->user_id) {
            $namaRuangan = $peminjaman->ruangan ? $peminjaman->ruangan->nama_ruangan : 'Ruangan';
            \App\Models\Notification::create([
                'user_id' => $peminjaman->user_id,
                'title'   => '❌ Pengajuan Ditolak',
                'message' => "Pengajuan peminjaman ruangan {$namaRuangan} ({$peminjaman->nomor_pengajuan}) DITOLAK. Alasan: {$request->alasan_penolakan}",
                'type'    => 'ditolak',
                'link'    => route('peminjaman-ruangan.show', $peminjaman->id, false),
            ]);
        }

        // Kirim Notifikasi WhatsApp via Fonnte
        try {
            $waService = app(\App\Services\WhatsAppService::class);
            $userPhone = $peminjaman->user ? ($peminjaman->user->no_hp ?? null) : null;
            if (!empty($userPhone)) {
                $waMessage = "Halo {$peminjaman->nama_peminjam}, pengajuan peminjaman ruangan {$peminjaman->ruangan->nama_ruangan} (ID: {$peminjaman->nomor_pengajuan}) DITOLAK. Alasan: {$request->alasan_penolakan}. Silakan masuk ke SIPRUKA untuk melakukan perbaikan berkas dan mengajukan kembali.";
                $waService->sendMessage($userPhone, $waMessage, 'REJECTION');
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim WhatsApp rejection: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pengajuan peminjaman telah ditolak dan alasan penolakan telah disimpan.');
    }

    /**
     * Unduh / Tampilkan Surat Izin Digital Resmi (Use Case: Unduh Izin Digital)
     */
    public function unduhIzinDigital($id)
    {
        $peminjaman = PeminjamanRuangan::with(['ruangan', 'user', 'admin'])->findOrFail($id);

        // RBAC: Hanya pemohon pemilik atau Admin yang bisa mengakses surat izin
        if (auth()->check() && auth()->user()->role !== 'admin' && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat Surat Izin Digital ini.');
        }

        // Surat izin hanya boleh diakses jika status disetujui, berlangsung, atau selesai
        $computed = $peminjaman->computed_status;
        if (!in_array($peminjaman->status, ['disetujui', 'berlangsung', 'selesai']) && !in_array($computed, ['disetujui', 'berlangsung', 'selesai'])) {
            return redirect()->route('peminjaman-ruangan.show', $id)
                ->with('error', 'Surat Izin Digital belum dapat diunduh karena pengajuan belum disetujui oleh Admin.');
        }

        // Pastikan nomor surat sudah ada jika disetujui manual sebelumnya
        if (empty($peminjaman->nomor_surat_izin)) {
            $peminjaman->terbitkanIzinDigital($peminjaman->admin ?? auth()->user());
        }

        return view('peminjaman_ruangan.izin_digital', compact('peminjaman'));
    }

    /**
     * Cetak & Rekapitulasi Laporan Peminjaman (Use Case: Cetak Laporan - Admin)
     */
    public function laporan(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang berhak mengakses menu Laporan.');
        }

        $ruanganList = Ruangan::orderBy('nama_ruangan')->get();

        $query = PeminjamanRuangan::with(['ruangan', 'user', 'admin']);

        // Filter Rentang Tanggal
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_mulai', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_selesai', '<=', $request->sampai_tanggal);
        }

        // Filter Ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Jenis Peminjam
        if ($request->filled('jenis_peminjam')) {
            $query->where('jenis_peminjam', $request->jenis_peminjam);
        }

        $data = $query->orderBy('tanggal_mulai', 'desc')->get();

        // Hitung metrik laporan
        $stats = [
            'total'       => $data->count(),
            'disetujui'   => $data->whereIn('status', ['disetujui', 'berlangsung', 'selesai'])->count(),
            'pending'     => $data->where('status', 'pending')->count(),
            'ditolak'     => $data->where('status', 'ditolak')->count(),
            'total_jam'   => round($data->whereIn('status', ['disetujui', 'berlangsung', 'selesai'])->sum('durasi') / 60, 1),
        ];

        $isPrint = $request->boolean('print');

        return view('peminjaman_ruangan.laporan', compact('data', 'ruanganList', 'stats', 'isPrint'));
    }

    /**
     * Helper privat untuk memfilter data laporan sesuai kriteria request
     */
    private function getFilteredLaporanData(Request $request)
    {
        $query = PeminjamanRuangan::with(['ruangan', 'user', 'admin']);

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_mulai', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_selesai', '<=', $request->sampai_tanggal);
        }
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_peminjam')) {
            $query->where('jenis_peminjam', $request->jenis_peminjam);
        }

        $data = $query->orderBy('tanggal_mulai', 'desc')->get();
        foreach ($data as $item) {
            $item->syncLifecycleStatus();
        }

        return $data;
    }

    /**
     * Ekspor Laporan Peminjaman Ruangan ke Format Excel (.xls)
     */
    public function exportExcel(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang berhak melakukan ekspor data.');
        }

        $data = $this->getFilteredLaporanData($request);
        $filename = 'Laporan_Peminjaman_Ruangan_' . date('Ymd_His') . '.xls';

        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($data) {
            $output = fopen('php://output', 'w');
            fwrite($output, "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><style>th{background-color:#004B87;color:#ffffff;font-weight:bold;border:1px solid #000;} td{border:1px solid #ccc;}</style></head><body>");
            fwrite($output, "<h2>LAPORAN REKAPITULASI PEMINJAMAN RUANGAN - SIPRUKA</h2>");
            fwrite($output, "<p>Dicetak Tanggal: " . date('d F Y H:i') . " WIB | Pemohon/Admin: " . htmlspecialchars(auth()->user()->nama) . "</p>");
            fwrite($output, "<table border='1' cellspacing='0' cellpadding='5'>");
            fwrite($output, "<thead><tr>
                <th>No</th>
                <th>Nomor Pengajuan</th>
                <th>Tanggal Pengajuan</th>
                <th>Nama Peminjam</th>
                <th>Jenis Peminjam</th>
                <th>Nama Ruangan</th>
                <th>Lokasi</th>
                <th>Tanggal Mulai</th>
                <th>Waktu Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Waktu Selesai</th>
                <th>Durasi (Menit)</th>
                <th>Keperluan</th>
                <th>Status</th>
                <th>Nomor Surat Izin</th>
                <th>Admin Verifikator</th>
            </tr></thead><tbody>");

            $no = 1;
            foreach ($data as $row) {
                $status = strtoupper($row->computed_status ?? $row->status);
                fwrite($output, "<tr>
                    <td align='center'>{$no}</td>
                    <td>" . htmlspecialchars($row->nomor_pengajuan) . "</td>
                    <td>" . ($row->created_at ? $row->created_at->format('Y-m-d H:i') : '-') . "</td>
                    <td>" . htmlspecialchars($row->nama_peminjam) . "</td>
                    <td>" . ucfirst($row->jenis_peminjam ?? 'individu') . "</td>
                    <td>" . htmlspecialchars($row->ruangan->nama_ruangan ?? '-') . "</td>
                    <td>" . htmlspecialchars($row->ruangan->lokasi ?? '-') . "</td>
                    <td>" . htmlspecialchars($row->tanggal_mulai) . "</td>
                    <td>" . htmlspecialchars($row->waktu_mulai) . "</td>
                    <td>" . htmlspecialchars($row->tanggal_selesai) . "</td>
                    <td>" . htmlspecialchars($row->waktu_selesai) . "</td>
                    <td align='center'>" . ($row->durasi ?? 0) . "</td>
                    <td>" . htmlspecialchars($row->keperluan ?? '-') . "</td>
                    <td align='center'><b>{$status}</b></td>
                    <td>" . htmlspecialchars($row->nomor_surat_izin ?? '-') . "</td>
                    <td>" . htmlspecialchars($row->admin->nama ?? '-') . "</td>
                </tr>");
                $no++;
            }

            fwrite($output, "</tbody></table></body></html>");
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Ekspor Laporan Peminjaman Ruangan ke Format CSV (.csv)
     */
    public function exportCsv(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang berhak melakukan ekspor data.');
        }

        $data = $this->getFilteredLaporanData($request);
        $filename = 'Laporan_Peminjaman_Ruangan_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            // Header UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'NO',
                'NOMOR PENGAJUAN',
                'TANGGAL PENGAJUAN',
                'NAMA PEMINJAM',
                'JENIS PEMINJAM',
                'RUANGAN',
                'LOKASI',
                'TANGGAL MULAI',
                'WAKTU MULAI',
                'TANGGAL SELESAI',
                'WAKTU SELESAI',
                'DURASI (MENIT)',
                'KEPERLUAN',
                'STATUS',
                'NOMOR SURAT IZIN',
                'ADMIN VERIFIKATOR'
            ]);

            $no = 1;
            foreach ($data as $row) {
                fputcsv($file, [
                    $no++,
                    $row->nomor_pengajuan,
                    $row->created_at ? $row->created_at->format('Y-m-d H:i') : '-',
                    $row->nama_peminjam,
                    ucfirst($row->jenis_peminjam ?? 'individu'),
                    $row->ruangan->nama_ruangan ?? '-',
                    $row->ruangan->lokasi ?? '-',
                    $row->tanggal_mulai,
                    $row->waktu_mulai,
                    $row->tanggal_selesai,
                    $row->waktu_selesai,
                    $row->durasi ?? 0,
                    $row->keperluan ?? '-',
                    strtoupper($row->computed_status ?? $row->status),
                    $row->nomor_surat_izin ?? '-',
                    $row->admin->nama ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Cari rekomendasi ruangan alternatif yang kosong untuk jadwal yang diminta (Flowchart Sistem Revisi)
     */
    private function getRecommendedRuangan($excludeRuanganId, $mulai, $selesai, $excludePeminjamanId = null)
    {
        $otherRooms = Ruangan::where('id', '!=', $excludeRuanganId)->get();
        $recommendedRooms = [];

        foreach ($otherRooms as $room) {
            $query = PeminjamanRuangan::where('ruangan_id', $room->id)
                ->whereIn('status', ['pending', 'disetujui', 'berlangsung']);

            if ($excludePeminjamanId) {
                $query->where('id', '!=', $excludePeminjamanId);
            }

            $roomConflicts = $query->get();
            $isOverlap = false;

            foreach ($roomConflicts as $rc) {
                $rc->syncLifecycleStatus();
                if (in_array($rc->computed_status, ['selesai', 'ditolak', 'dibatalkan'])) {
                    continue;
                }
                $rcMulai = Carbon::parse($rc->tanggal_mulai . ' ' . $rc->waktu_mulai);
                $rcSelesai = Carbon::parse($rc->tanggal_selesai . ' ' . $rc->waktu_selesai);
                if ($mulai < $rcSelesai && $selesai > $rcMulai) {
                    $isOverlap = true;
                    break;
                }
            }

            if (!$isOverlap) {
                $recommendedRooms[] = $room;
            }
        }

        return $recommendedRooms;
    }

    /**
     * Tampilkan/Stream dokumen verifikasi pemohonan (file_dokumen atau file_sp) secara aman.
     */
    public function lihatDokumen($id, $type)
    {
        $peminjaman = PeminjamanRuangan::findOrFail($id);

        $user = auth()->user();
        if ($user->role !== 'admin' && $peminjaman->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        $filePath = match ($type) {
            'dokumen' => $peminjaman->file_dokumen,
            'sp' => $peminjaman->file_sp,
            default => null,
        };

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $fullPath = Storage::disk('public')->path($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        ]);
    }
}
