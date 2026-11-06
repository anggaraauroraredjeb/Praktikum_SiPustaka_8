<?php

namespace Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Modules\UserManagement\Http\Requests\StoreUserRequest;
use Modules\UserManagement\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index(): View
    {
        return view('user-management::index', ['users' => User::withCount('loans')->orderBy('name')->paginate(12)]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        if ($user->is($request->user()) && $data['role'] !== 'admin') {
            return back()->withErrors(['role' => 'Role admin akun yang sedang digunakan tidak dapat diubah.']);
        }
        if (filled($data['password'] ?? null)) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);

        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 422, 'Akun yang sedang digunakan tidak dapat dihapus.');
        if ($user->isAdmin() && User::where('role', 'admin')->count() < 2) {
            return back()->withErrors(['user' => 'Minimal harus ada satu akun admin.']);
        }
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
