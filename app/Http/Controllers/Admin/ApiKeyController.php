<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiKeyController extends Controller
{
    private const ALLOWED_PERMISSIONS = ['read', 'write', 'delete', 'admin'];

    public function index()
    {
        $user = Auth::user();

        $keys = $user->hasRole('super_admin')
            ? ApiKey::with('user')->latest()->get()
            : ApiKey::where('user_id', $user->id)->latest()->get();

        $plainKey = session()->pull('api_plain_key');

        return view('admin.api-keys.index', compact('keys', 'plainKey'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:150'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:' . implode(',', self::ALLOWED_PERMISSIONS)],
            'expires_at'    => ['nullable', 'date', 'after:today'],
        ]);

        $user        = Auth::user();
        $permissions = $request->input('permissions', []);
        $expiresAt   = $request->filled('expires_at')
            ? \Carbon\Carbon::parse($request->expires_at)->endOfDay()
            : null;

        ['key' => $plainKey] = ApiKey::generate(
            $user->id,
            $request->name,
            $permissions,
            $expiresAt
        );

        // Store plain key in session — shown ONCE
        session()->flash('api_plain_key', $plainKey);

        return redirect()->route('admin.api-keys.index')
            ->with('success', 'تم إنشاء مفتاح API. انسخه الآن — لن يُعرض مجدداً.');
    }

    public function destroy(ApiKey $apiKey)
    {
        $user = Auth::user();

        if (! $user->hasRole('super_admin') && $apiKey->user_id !== $user->id) {
            abort(403);
        }

        $apiKey->delete();

        return redirect()->route('admin.api-keys.index')
            ->with('success', 'تم حذف مفتاح API.');
    }

    public function toggle(ApiKey $apiKey)
    {
        $user = Auth::user();

        if (! $user->hasRole('super_admin') && $apiKey->user_id !== $user->id) {
            abort(403);
        }

        $apiKey->update(['is_active' => ! $apiKey->is_active]);

        return redirect()->route('admin.api-keys.index')
            ->with('success', $apiKey->is_active ? 'تم تفعيل مفتاح API.' : 'تم تعطيل مفتاح API.');
    }
}
