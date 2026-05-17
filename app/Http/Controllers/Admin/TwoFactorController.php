<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function index(): View
    {
        $user    = Auth::user();
        $enabled = $user->two_factor_enabled;

        return view('admin.two-factor.index', compact('user', 'enabled'));
    }

    public function enable(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->two_factor_enabled) {
            return back()->with('error', 'المصادقة الثنائية مُفعّلة بالفعل.');
        }

        $secret = $this->generateSecret();
        $user->update([
            'totp_secret'        => encrypt($secret),
            'two_factor_enabled' => false,
        ]);

        session(['2fa_setup_secret' => $secret]);

        return back()->with('success', 'تم بدء إعداد المصادقة الثنائية. امسح رمز QR وأدخل الرمز للتأكيد.');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|string|size:6|regex:/^\d+$/']);

        $user   = Auth::user();
        $secret = session('2fa_setup_secret') ?? ($user->totp_secret ? decrypt($user->totp_secret) : null);

        if (!$secret) {
            return back()->with('error', 'انتهت صلاحية الجلسة. يرجى إعادة إعداد المصادقة الثنائية.');
        }

        if ($this->verifyTotp($secret, $request->input('code'))) {
            $user->update(['two_factor_enabled' => true]);
            session()->forget('2fa_setup_secret');
            return back()->with('success', 'تم تفعيل المصادقة الثنائية بنجاح.');
        }

        return back()->with('error', 'الرمز غير صحيح. حاول مجدداً.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate(['password' => 'required|string']);

        if (!\Hash::check($request->input('password'), Auth::user()->password)) {
            return back()->with('error', 'كلمة المرور غير صحيحة.');
        }

        Auth::user()->update([
            'totp_secret'        => null,
            'two_factor_enabled' => false,
        ]);

        return back()->with('success', 'تم تعطيل المصادقة الثنائية.');
    }

    private function generateSecret(): string
    {
        $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 32; $i++) {
            $secret .= $chars[random_int(0, 31)];
        }
        return $secret;
    }

    private function verifyTotp(string $secret, string $code): bool
    {
        $timestamp = floor(time() / 30);
        for ($offset = -1; $offset <= 1; $offset++) {
            if ($this->calcTotp($secret, $timestamp + $offset) === $code) {
                return true;
            }
        }
        return false;
    }

    private function calcTotp(string $secret, int $timestamp): string
    {
        $base32Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret      = strtoupper($secret);
        $decoded     = '';
        $buffer      = 0;
        $bitsLeft    = 0;

        foreach (str_split($secret) as $char) {
            $pos = strpos($base32Chars, $char);
            if ($pos === false) continue;
            $buffer   = ($buffer << 5) | $pos;
            $bitsLeft += 5;
            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $decoded  .= chr(($buffer >> $bitsLeft) & 0xFF);
            }
        }

        $time  = pack('N*', 0) . pack('N*', $timestamp);
        $hash  = hash_hmac('sha1', $time, $decoded, true);
        $offset= ord($hash[19]) & 0xF;
        $code  = (
            ((ord($hash[$offset + 0]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8)  |
            (ord($hash[$offset + 3]) & 0xFF)
        ) % 1_000_000;

        return str_pad((string) $code, 6, '0', STR_PAD_LEFT);
    }
}
