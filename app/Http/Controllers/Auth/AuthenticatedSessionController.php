<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();

        $this->logInfo('login');

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function logInfo($params)
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $device = '';
        if (preg_match('/mobile/i', $userAgent)) {
            $device = "mobile";
        } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
            $device = "tablet";
        } else {
            $device = "desktop";
        }

        $browser = "Unknown Browser";

        function getBrowser($userAgent)
        {
            $browser = "Unknown Browser";

            // Check for different browsers
            if (preg_match('/MSIE/i', $userAgent) || preg_match('/Trident/i', $userAgent)) {
                $browser = 'Internet Explorer';
            } elseif (preg_match('/Firefox/i', $userAgent)) {
                $browser = 'Mozilla Firefox';
            } elseif (preg_match('/Chrome/i', $userAgent) && !preg_match('/Edge/i', $userAgent)) {
                $browser = 'Google Chrome';
            } elseif (preg_match('/Safari/i', $userAgent) && !preg_match('/Chrome/i', $userAgent)) {
                $browser = 'Apple Safari';
            } elseif (preg_match('/Edge/i', $userAgent)) {
                $browser = 'Microsoft Edge';
            } elseif (preg_match('/Opera/i', $userAgent) || preg_match('/OPR/i', $userAgent)) {
                $browser = 'Opera';
            }

            return $browser;
        }

        // Example usage
        $userAgent = request()->header('User-Agent');
        $browserName = getBrowser($userAgent);

        $data = [
            'user_name' => Auth::user()->name,

            'user_id' => Auth::user()->id,
            'entry_dt' => Carbon::now()->toDateString(),
            'device_type' => $device,
            'browser_info' => $browserName,
            'ip_address' => request()->ip(),
        ];
        if ($params == 'logout') {
            $data['exit_time'] = Carbon::now();
        } else {
            $data['access_time'] = Carbon::now();
        }
        DB::table('USR_AUDIT_TRAIL')->insert($data);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->logInfo('logout');
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}