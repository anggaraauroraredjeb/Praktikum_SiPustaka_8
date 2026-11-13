<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ] : null,
            ],
            'navigation' => $user ? [
                ['label' => 'Ringkasan', 'href' => route('dashboard')],
                ['label' => 'Buku & Peminjaman', 'href' => route('pendaftaran.index')],
                ...($user->isAdmin() ? [['label' => 'Pengguna', 'href' => route('users.index')]] : []),
            ] : [],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
            ],
        ];
    }
}