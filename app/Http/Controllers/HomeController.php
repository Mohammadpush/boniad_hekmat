<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
        public function addview()
    {
        $users=User::all();
        return view('singup',compact('users'));
    }
public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);
    $validated['password'] = bcrypt($validated['password']);
    $validated['role'] = 'user'; // نقش پیش‌فرض

    User::create($validated);

    return redirect()->route('home')->with('success', 'کاربر ثبت شد!');
}
}
