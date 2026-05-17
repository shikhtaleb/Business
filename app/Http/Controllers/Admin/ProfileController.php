<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        ActivityLog::record(
            "Profile updated: {$user->email}",
            'users',
            ['id' => $user->id]
        );

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->with('error', 'كلمة المرور الحالية غير صحيحة.');
        }

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        ActivityLog::record(
            "Password changed: {$user->email}",
            'users',
            ['id' => $user->id]
        );

        return back()->with('success', 'تم تحديث كلمة المرور بنجاح.');
    }
}
