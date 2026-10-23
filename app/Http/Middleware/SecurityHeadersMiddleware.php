<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and attach recommended security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sembunyikan header X-Powered-By pada level PHP
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }

        $response = $next($request);

        // Hapus X-Powered-By dari Symfony Response headers
        $response->headers->remove('X-Powered-By');

        // Mencegah clickjacking attack (hanya boleh di-frame oleh origin yang sama)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Mencegah MIME sniffing attack
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Mengontrol informasi referrer saat navigasi keluar
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Proteksi XSS untuk legacy browser
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Membatasi akses fitur sensitif peramban
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content Security Policy (CSP) tanpa 'unsafe-eval' (lolos audit SEC-HDR-CSP-02)
        // Mendukung skrip lokal ('unsafe-inline'), CDN https:, data:/blob: untuk gambar/QR, dan koneksi Vite (ws: / wss:)
        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https:",
            "style-src 'self' 'unsafe-inline' https:",
            "img-src 'self' data: blob: https:",
            "font-src 'self' data: https:",
            "connect-src 'self' https: ws: wss: http://localhost:* http://127.0.0.1:* http://[::1]:*",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ];

        $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));

        // Jika request menggunakan HTTPS, aktifkan HSTS
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
