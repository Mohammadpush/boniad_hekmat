<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use app\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return response()->view('login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            session([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'ip_address' => $request->ip(),
                'last_activity' => now()->timestamp
            ]);

            return redirect()->route('roler');
        }

        return back()->withErrors([
            'username' => 'نام کاربری یا رمز عبور اشتباه است.',
        ])->withInput($request->only('username'));
    }
    public function logout()
    {
        if (Auth::check()) {

            Auth::logout();
        }
        return redirect('/');
    }

    public function roler()
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // ریدایرکت به صفحه لاگین
        }
        
        // هدایت همه نقش‌ها به داشبورد واحد جدید
        return redirect()->route('unified.myrequests');
    }
}
