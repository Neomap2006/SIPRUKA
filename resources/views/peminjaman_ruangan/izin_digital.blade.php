<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin Digital - {{ $peminjaman->nomor_surat_izin ?? $peminjaman->nomor_pengajuan }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/Logo untuk Pov icon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&family=space-grotesk:600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #qrcode {
            width: 72px !important;
            height: 72px !important;
            max-width: 72px !important;
            max-height: 72px !important;
            margin: 0 auto !important;
            overflow: hidden !important;
            line-height: 0 !important;
            font-size: 0 !important;
        }
        #qrcode canvas, #qrcode img {
            width: 72px !important;
            height: 72px !important;
            max-width: 72px !important;
            max-height: 72px !important;
            margin: 0 auto !important;
            display: block !important;
            vertical-align: top !important;
        }
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }
            html, body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 !important;
                height: 100vh;
                overflow: hidden;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                width: 100% !important;
                padding: 24px 36px !important;
                margin: 0 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-[#0b1324] text-slate-800 font-sans min-h-screen py-8 px-4 antialiased">

    <!-- Action Toolbar (Hidden in Print) -->
    <div class="max-w-4xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-4 no-print">
        <a href="{{ route('peminjaman-ruangan.show', $peminjaman->id) }}" 
           class="inline-flex items-center gap-2 text-slate-300 hover:text-[#00E9FF] text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Detail Peminjaman
        </a>

        <div class="flex items-center gap-3">
            <button id="btn-download-pdf" onclick="downloadPDF()" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 via-teal-500 to-emerald-400 hover:from-emerald-500 hover:to-teal-300 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-[0_0_20px_rgba(16,185,129,0.4)] cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Unduh File PDF</span>
            </button>

            <button onclick="window.print()" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-[#004B87] to-[#00D1FF] hover:to-[#00E9FF] text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-[0_0_20px_rgba(0,209,255,0.4)] cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.656" />
                </svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Official Certificate Document Container -->
    <div id="surat-izin-document" class="print-container max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl p-6 sm:p-10 border border-slate-200 text-slate-800">
        
        <!-- Header / Kop Surat -->
        <div class="border-b-2 border-slate-800 pb-4 mb-4">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/Logo untuk Sidebar.png') }}" crossorigin="anonymous" alt="Logo SIPRUKA" class="w-16 h-16 object-contain shrink-0">
                    <div>
                        <h1 class="text-lg font-black uppercase tracking-wider text-slate-900 leading-tight">SISTEM INFORMASI PEMINJAMAN RUANGAN KAMPUS</h1>
                        <h2 class="text-sm font-bold text-slate-700 tracking-wide">SIPRUKA DIGITAL VERIFICATION</h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">Layanan Resmi Reservasi & Pengelolaan Fasilitas Ruangan Terpadu</p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 font-bold text-[11px] uppercase tracking-wider rounded-lg border border-emerald-300">
                        SURAT IZIN RESMI
                    </span>
                    <p class="text-[11px] font-mono text-slate-500 mt-1">ID: {{ $peminjaman->nomor_pengajuan }}</p>
                </div>
            </div>
        </div>

        <!-- Judul Surat & Nomor -->
        <div class="text-center my-4">
            <h3 class="text-base font-bold text-slate-900 uppercase tracking-wide underline underline-offset-4">
                SURAT IZIN PENGGUNAAN RUANGAN
            </h3>
            <p class="text-xs font-semibold text-slate-600 mt-1 font-mono">
                Nomor: {{ $peminjaman->nomor_surat_izin ?? ('SIP/' . date('Y/m/') . str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT)) }}
            </p>
        </div>

        <!-- Surat Body Content -->
        <div class="space-y-3 text-xs leading-relaxed text-slate-700">
            <p>
                Berdasarkan permohonan yang diajukan melalui Sistem Informasi Peminjaman Ruangan Kampus (SIPRUKA), dengan ini Pengelola Sarana & Prasarana memberikan <strong>IZIN PENGGUNAAN RUANGAN</strong> kepada:
            </p>

            <!-- Table Data Peminjam -->
            <table class="w-full text-xs border-collapse my-1">
                <tbody>
                    <tr class="border-b border-slate-100">
                        <td class="py-1 font-semibold text-slate-600 w-44">Nama Peminjam</td>
                        <td class="py-1 w-4">:</td>
                        <td class="py-1 font-bold text-slate-900">{{ $peminjaman->nama_peminjam }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-1 font-semibold text-slate-600">NIPD / NIM</td>
                        <td class="py-1">:</td>
                        <td class="py-1 font-mono">{{ $peminjaman->user->nipd ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-1 font-semibold text-slate-600">Program Studi</td>
                        <td class="py-1">:</td>
                        <td class="py-1">{{ $peminjaman->user->prodi ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-1 font-semibold text-slate-600">Kategori Peminjam</td>
                        <td class="py-1">:</td>
                        <td class="py-1 uppercase font-semibold text-[11px] tracking-wider">
                            {{ $peminjaman->jenis_peminjam === 'berorganisasi' ? 'Organisasi / Lembaga Mahasiswa (UKM)' : 'Individu / Mahasiswa' }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <p class="pt-1">
                Untuk melaksanakan kegiatan dengan rincian jadwal dan fasilitas sebagai berikut:
            </p>

            <!-- Table Data Fasilitas & Jadwal -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 my-1.5">
                <table class="w-full text-xs border-collapse">
                    <tbody>
                        <tr class="border-b border-slate-200">
                            <td class="py-1.5 font-semibold text-slate-600 w-40">Ruangan</td>
                            <td class="py-1.5 w-4">:</td>
                            <td class="py-1.5 font-bold text-slate-900">{{ $peminjaman->ruangan->nama_ruangan }} (Kapasitas: {{ $peminjaman->ruangan->kapasitas }} Orang)</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <td class="py-1.5 font-semibold text-slate-600">Lokasi / Gedung</td>
                            <td class="py-1.5">:</td>
                            <td class="py-1.5">{{ $peminjaman->ruangan->lokasi ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <td class="py-1.5 font-semibold text-slate-600">Tanggal Penggunaan</td>
                            <td class="py-1.5">:</td>
                            <td class="py-1.5 font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->translatedFormat('l, d F Y') }}
                                @if($peminjaman->tanggal_mulai !== $peminjaman->tanggal_selesai)
                                    s/d {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->translatedFormat('l, d F Y') }}
                                @endif
                            </td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <td class="py-1.5 font-semibold text-slate-600">Waktu / Jam</td>
                            <td class="py-1.5">:</td>
                            <td class="py-1.5 font-mono font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($peminjaman->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($peminjaman->waktu_selesai)->format('H:i') }} WIB (Durasi: {{ $peminjaman->durasi }} Menit)
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1.5 font-semibold text-slate-600 align-top">Keperluan / Agenda</td>
                            <td class="py-1.5 align-top">:</td>
                            <td class="py-1.5 leading-relaxed text-slate-800">{{ $peminjaman->keperluan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="text-[11px] text-slate-500 italic pt-0.5">
                * Peminjam wajib menjaga kebersihan, ketertiban, serta fasilitas ruangan selama dan sesudah peminjaman berlangsung sesuai tata tertib kampus yang berlaku.
            </p>
        </div>

        <!-- Signature & Verification QR Block -->
        <div class="mt-4 pt-3 border-t border-slate-200 flex items-end justify-between">
            <div class="w-52 text-center space-y-1.5" style="width: 200px; text-align: center;">
                <p class="text-[11px] font-semibold text-slate-600" style="text-align: center; margin: 0 auto 4px auto;">Verifikasi Keaslian Dokumen:</p>
                <div class="border border-slate-200 rounded-xl bg-white shadow-sm" 
                     style="width: 84px; height: 84px; margin: 4px auto; padding: 6px; display: block; box-sizing: border-box; text-align: center; overflow: hidden;">
                    <div id="qrcode" style="width: 72px; height: 72px; margin: 0 auto; display: block; text-align: center; overflow: hidden; line-height: 0; font-size: 0;"></div>
                    <img id="qr-fallback" src="https://api.qrserver.com/v1/create-qr-code/?size=144x144&data={{ urlencode(url()->current()) }}" 
                         crossorigin="anonymous"
                         alt="QR Code Verification" 
                         style="width: 72px; height: 72px; margin: 0 auto; display: none;" 
                         class="hidden">
                </div>
                <p class="text-[9px] text-slate-500 font-mono font-medium" style="text-align: center; margin: 4px auto 0 auto;">Status: RESMI & TERDAFTAR</p>
            </div>

            <div class="text-right space-y-0.5 w-64">
                <p class="text-[11px] text-slate-600">Ditetapkan pada {{ \Carbon\Carbon::parse($peminjaman->updated_at)->translatedFormat('d F Y') }}</p>
                <p class="text-[11px] font-semibold text-slate-700">Pengelola Fasilitas & Sarana Prasarana,</p>
                
                <div class="py-2">
                    <span class="inline-block px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-300 rounded text-[10px] font-bold tracking-wider font-mono">
                        ✓ DIGITALLY SIGNED & VERIFIED
                    </span>
                </div>

                <p class="text-xs font-bold text-slate-900 underline">{{ $peminjaman->admin->nama ?? 'Admin SIPRUKA' }}</p>
                <p class="text-[11px] text-slate-600">{{ $peminjaman->admin->jabatan ?? 'Pengelola Ruangan' }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-4 pt-2 border-t border-slate-100 text-center">
            <p class="text-[9px] text-slate-400">
                Dokumen ini diterbitkan secara elektronik oleh Sistem Informasi Peminjaman Ruangan Kampus (SIPRUKA). Keabsahan dokumen dapat divalidasi melalui sistem.
            </p>
        </div>

    </div>

    <!-- Scripts for QR Code & HTML to PDF Conversion -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            generateQRCode();
        });

        function generateQRCode() {
            const qrDiv = document.getElementById("qrcode");
            const fallbackImg = document.getElementById("qr-fallback");
            const targetUrl = "{{ url()->current() }}";

            if (typeof QRCode !== "undefined" && qrDiv) {
                qrDiv.innerHTML = "";
                try {
                    new QRCode(qrDiv, {
                        text: targetUrl,
                        width: 144,
                        height: 144,
                        colorDark: "#0b1324",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M
                    });

                    // Pastikan HANYA SATU elemen (canvas atau img) yang ada di DOM untuk hilangkan duplikasi & celah putih
                    const cleanQRElements = () => {
                        const img = qrDiv.querySelector("img");
                        const canvas = qrDiv.querySelector("canvas");

                        if (img && img.src && img.src.length > 50) {
                            img.style.width = "72px";
                            img.style.height = "72px";
                            img.style.display = "block";
                            img.style.margin = "0 auto";
                            if (canvas) {
                                canvas.remove(); // Hapus canvas agar tidak terjadi tumpang tindih elemen ke bawah
                            }
                            return true;
                        } else if (canvas) {
                            canvas.style.width = "72px";
                            canvas.style.height = "72px";
                            canvas.style.display = "block";
                            canvas.style.margin = "0 auto";
                            if (img) {
                                img.style.display = "none";
                            }
                        }
                        return false;
                    };

                    // Jalankan pembersihan segera dan pantau proses render asinkron
                    if (!cleanQRElements()) {
                        let attempts = 0;
                        const timer = setInterval(() => {
                            attempts++;
                            if (cleanQRElements() || attempts > 20) {
                                clearInterval(timer);
                            }
                        }, 40);
                    }
                    return;
                } catch (e) {
                    console.warn("QRCode JS initialization failed, using fallback img", e);
                }
            }

            if (fallbackImg) {
                fallbackImg.style.display = "block";
                fallbackImg.style.margin = "0 auto";
                fallbackImg.classList.remove("hidden");
            }
        }

        async function prepareImagesForPDF(container) {
            const images = container.querySelectorAll("img");
            const promises = Array.from(images).map(img => {
                return new Promise((resolve) => {
                    if (!img.src || img.src.startsWith("data:")) {
                        return resolve();
                    }
                    const tempImg = new Image();
                    tempImg.crossOrigin = "Anonymous";
                    tempImg.onload = function () {
                        try {
                            const canvas = document.createElement("canvas");
                            canvas.width = tempImg.naturalWidth || tempImg.width || 120;
                            canvas.height = tempImg.naturalHeight || tempImg.height || 120;
                            const ctx = canvas.getContext("2d");
                            ctx.drawImage(tempImg, 0, 0);
                            img.src = canvas.toDataURL("image/png");
                        } catch (err) {
                            console.warn("Unable to convert img to dataURL:", img.src, err);
                        }
                        resolve();
                    };
                    tempImg.onerror = function () {
                        resolve();
                    };
                    tempImg.src = img.src;
                });
            });
            await Promise.all(promises);
        }

        async function downloadPDF() {
            const element = document.getElementById('surat-izin-document');
            const btn = document.getElementById('btn-download-pdf');
            const originalHTML = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin w-4 h-4 text-white inline-block mr-1.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Memproses PDF...</span>`;

            try {
                // Pastikan QR Code bersih sebelum capturing PDF
                const qrDiv = element.querySelector('#qrcode');
                if (qrDiv) {
                    const img = qrDiv.querySelector('img');
                    const canvas = qrDiv.querySelector('canvas');
                    if (img && img.src && img.src.length > 50) {
                        img.style.width = '72px';
                        img.style.height = '72px';
                        img.style.display = 'block';
                        img.style.margin = '0 auto';
                        if (canvas) canvas.remove();
                    } else if (canvas) {
                        canvas.style.width = '72px';
                        canvas.style.height = '72px';
                        canvas.style.display = 'block';
                        canvas.style.margin = '0 auto';
                        if (img) img.style.display = 'none';
                    }
                }

                await prepareImagesForPDF(element);

                const opt = {
                    margin:       [8, 8, 8, 8],
                    filename:     'Surat_Izin_SIPRUKA_{{ str_replace(["/", "\\"], "_", $peminjaman->nomor_pengajuan) }}.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2, useCORS: true, allowTaint: true, logging: false },
                    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                await html2pdf().set(opt).from(element).save();
            } catch (err) {
                console.error("HTML2PDF error:", err);
                window.print();
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            }
        }
    </script>
</body>
</html>
