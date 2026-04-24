<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HatchersLaunchController extends Controller
{
    public function __invoke(Request $request)
    {
        $sharedSecret = trim((string) env('WEBSITE_PLATFORM_SHARED_SECRET', env('HATCHERS_SHARED_SECRET', '')));
        if ($sharedSecret === '') {
            abort(500, 'WEBSITE_PLATFORM_SHARED_SECRET is not configured.');
        }

        $payload = $request->validate([
            'username' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'role' => ['required', 'string'],
            'target' => ['required', 'string'],
            'expires' => ['required', 'integer'],
            'signature' => ['required', 'string'],
        ]);

        if ((int) $payload['expires'] < time()) {
            return redirect('/admin')->with('error', 'This Hatchers OS launch link has expired.');
        }

        $expected = hash_hmac('sha256', implode('|', [
            (string) ($payload['username'] ?? ''),
            (string) ($payload['email'] ?? ''),
            (string) ($payload['role'] ?? ''),
            (string) ($payload['target'] ?? ''),
            (string) ($payload['expires'] ?? ''),
        ]), $sharedSecret);

        if (!hash_equals($expected, (string) $payload['signature'])) {
            return redirect('/admin')->with('error', 'Invalid Hatchers OS launch signature.');
        }

        $role = trim((string) $payload['role']);
        $query = User::query();

        if ($role === 'admin') {
            $query->where('type', 1);
        } elseif ($role === 'founder') {
            $query->where('type', 2);
        } else {
            return redirect('/admin')->with('error', 'This Hatchers OS role is not supported in Bazaar yet.');
        }

        $user = null;
        if (!empty($payload['email'])) {
            $user = (clone $query)->where('email', (string) $payload['email'])->first();
        }
        if (empty($user) && !empty($payload['username'])) {
            $user = (clone $query)->where('username', (string) $payload['username'])->first();
        }

        if (empty($user)) {
            return redirect('/admin')->with('error', 'No matching Bazaar account was found for this OS user.');
        }

        session()->put('admin_login', 1);
        Auth::login($user, true);

        $target = (string) $payload['target'];
        if ($target === '' || str_starts_with($target, 'http')) {
            $target = '/admin/dashboard';
        }

        return redirect($target);
    }
}
