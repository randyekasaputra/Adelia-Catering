<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TrackWebsiteVisitor
{
    /**
     * Catat kunjungan pengunjung publik beserta informasi perangkat (Device, OS, Browser).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            // Hanya catat request GET halaman publik non-AJAX
            if (
                !$request->isMethod('GET') ||
                $request->ajax() ||
                $request->expectsJson() ||
                $request->is('admin*') ||
                $request->is('api*') ||
                $request->is('payment*') ||
                $request->is('_debugbar*') ||
                $request->is('storage*')
            ) {
                return $response;
            }

            $path = '/' . ltrim($request->path(), '/');

            // Abaikan request file statis
            if (preg_match('/\.(css|js|png|jpe?g|gif|webp|svg|ico|woff2?|ttf|map)$/i', $path)) {
                return $response;
            }

            $userAgent = (string) $request->userAgent();
            if ($this->isBot($userAgent)) {
                return $response;
            }

            if (!Schema::hasTable('visitor_logs')) {
                return $response;
            }

            $sessionId = $request->hasSession() ? $request->session()->getId() : md5(($request->ip() ?? '') . $userAgent);
            $today = now()->toDateString();

            // Hindari duplikasi berlebihan jika pengunjung me-refresh halaman yang sama di hari yang sama
            $alreadyLogged = VisitorLog::where('visited_date', $today)
                ->where('session_id', $sessionId)
                ->where('url_path', mb_substr($path, 0, 255))
                ->exists();

            if (!$alreadyLogged) {
                $parsed = $this->parseUserAgent($userAgent);

                VisitorLog::create([
                    'session_id'   => $sessionId,
                    'ip_address'   => $request->ip(),
                    'url_path'     => mb_substr($path, 0, 255),
                    'device_type'  => $parsed['device_type'],
                    'os'           => $parsed['os'],
                    'browser'      => $parsed['browser'],
                    'user_agent'   => mb_substr($userAgent, 0, 500),
                    'visited_date' => $today,
                ]);
            }
        } catch (\Throwable $e) {
            // Jangan biarkan error tracking mengganggu pengalaman pengguna
        }

        return $response;
    }

    private function isBot(string $ua): bool
    {
        if ($ua === '') {
            return false;
        }
        return (bool) preg_match('/bot|crawl|slurp|spider|mediapartners|headlesschrome|lighthouse|curl|wget|python-requests/i', $ua);
    }

    private function parseUserAgent(string $ua): array
    {
        $deviceType = 'Desktop';
        if (preg_match('/ipad|tablet|playbook|silk|(android(?!.*mobile))/i', $ua)) {
            $deviceType = 'Tablet';
        } elseif (preg_match('/mobile|iphone|ipod|android.*mobile|blackberry|opera mini|iemobile|windows phone|webos/i', $ua)) {
            $deviceType = 'Mobile';
        }

        $os = 'Lainnya';
        if (preg_match('/android/i', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $ua)) {
            $os = 'iOS';
        } elseif (preg_match('/windows nt|win64|win32/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/linux/i', $ua)) {
            $os = 'Linux';
        }

        $browser = 'Lainnya';
        if (preg_match('/edg\//i', $ua)) {
            $browser = 'Edge';
        } elseif (preg_match('/opr\/|opera/i', $ua)) {
            $browser = 'Opera';
        } elseif (preg_match('/samsungbrowser/i', $ua)) {
            $browser = 'Samsung Internet';
        } elseif (preg_match('/chrome|crios/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox|fxios/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $ua)) {
            $browser = 'Safari';
        }

        return [
            'device_type' => $deviceType,
            'os'          => $os,
            'browser'     => $browser,
        ];
    }
}
