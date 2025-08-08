<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityLoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log authentication attempts
        $this->logAuthAttempts($request, $response);
        
        // Log suspicious activities
        $this->logSuspiciousActivity($request, $response);

        return $response;
    }

    /**
     * Log authentication attempts
     */
    private function logAuthAttempts(Request $request, Response $response): void
    {
        // Log login attempts
        if ($request->is('login') && $request->isMethod('POST')) {
            $email = $request->input('email');
            $success = $response->isRedirection() && !$response->isRedirect('/login');
            
            Log::channel('security')->info('Login attempt', [
                'email' => $email,
                'success' => $success,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);

            if (!$success) {
                Log::channel('security')->warning('Failed login attempt', [
                    'email' => $email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now(),
                ]);
            }
        }

        // Log registration attempts
        if ($request->is('register') && $request->isMethod('POST')) {
            $email = $request->input('email');
            $success = $response->isRedirection() && $response->isRedirect('/dashboard');
            
            Log::channel('security')->info('Registration attempt', [
                'email' => $email,
                'success' => $success,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
        }

        // Log logout events
        if ($request->is('logout') && $request->isMethod('POST')) {
            Log::channel('security')->info('User logout', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()?->email,
                'ip' => $request->ip(),
                'timestamp' => now(),
            ]);
        }
    }

    /**
     * Log suspicious activities
     */
    private function logSuspiciousActivity(Request $request, Response $response): void
    {
        // Log rate limiting hits (429 responses)
        if ($response->getStatusCode() === 429) {
            Log::channel('security')->warning('Rate limit exceeded', [
                'route' => $request->path(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
        }

        // Log potential brute force attempts
        if ($request->is('login') && $request->isMethod('POST') && $response->getStatusCode() === 422) {
            $failedAttempts = cache()->get('login_attempts_' . $request->ip(), 0);
            
            if ($failedAttempts >= 3) {
                Log::channel('security')->alert('Potential brute force attack', [
                    'ip' => $request->ip(),
                    'failed_attempts' => $failedAttempts + 1,
                    'email' => $request->input('email'),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now(),
                ]);
            }
        }

        // Log suspicious user agents
        $suspiciousPatterns = [
            '/bot/i',
            '/crawler/i',
            '/spider/i',
            '/scanner/i',
            '/sqlmap/i',
            '/nikto/i',
            '/nmap/i',
        ];

        $userAgent = $request->userAgent();
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                Log::channel('security')->warning('Suspicious user agent detected', [
                    'user_agent' => $userAgent,
                    'ip' => $request->ip(),
                    'route' => $request->path(),
                    'timestamp' => now(),
                ]);
                break;
            }
        }

        // Log access to auth routes by authenticated users (potential session hijacking)
        if (($request->is('login') || $request->is('register')) && auth()->check()) {
            Log::channel('security')->warning('Authenticated user accessing auth routes', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'route' => $request->path(),
                'ip' => $request->ip(),
                'timestamp' => now(),
            ]);
        }
    }
}