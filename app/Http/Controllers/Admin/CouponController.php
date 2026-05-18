<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        $query = DB::table('coupons')->orderByDesc('created_at');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $coupons = $query->paginate(20)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create(): View
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code'        => 'required|string|max:50|unique:coupons,code',
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|numeric|min:0',
            'max_uses'    => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date',
            'is_active'   => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        DB::table('coupons')->insert([
            'code'        => strtoupper($data['code']),
            'type'        => $data['type'],
            'value'       => $data['value'],
            'max_uses'    => $data['max_uses'] ?? null,
            'times_used'  => 0,
            'expires_at'  => $data['expires_at'] ?? null,
            'is_active'   => $request->boolean('is_active', true) ? 1 : 0,
            'description' => $data['description'] ?? null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'تم إنشاء الكوبون بنجاح.');
    }

    public function edit(int $coupon): View
    {
        $coupon = DB::table('coupons')->where('id', $coupon)->firstOrFail();

        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, int $coupon): RedirectResponse
    {
        DB::table('coupons')->where('id', $coupon)->firstOrFail();

        $data = $request->validate([
            'code'        => 'required|string|max:50|unique:coupons,code,' . $coupon,
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|numeric|min:0',
            'max_uses'    => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date',
            'is_active'   => 'nullable|boolean',
            'description' => 'nullable|string|max:500',
        ]);

        DB::table('coupons')->where('id', $coupon)->update([
            'code'        => strtoupper($data['code']),
            'type'        => $data['type'],
            'value'       => $data['value'],
            'max_uses'    => $data['max_uses'] ?? null,
            'expires_at'  => $data['expires_at'] ?? null,
            'is_active'   => $request->boolean('is_active') ? 1 : 0,
            'description' => $data['description'] ?? null,
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'تم تحديث الكوبون.');
    }

    public function destroy(int $coupon): RedirectResponse
    {
        DB::table('coupons')->where('id', $coupon)->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'تم حذف الكوبون.');
    }
}
