<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->orderBy('name')->get();

        return view('admin.admin-user.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admin-user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.admin-user.index')->with('success', 'Admin baru berhasil ditambahkan.');
    }

    public function destroy(User $admin)
    {
        if ($admin->id === auth()->id()) {
            return redirect()->route('admin.admin-user.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $admin->delete();

        return redirect()->route('admin.admin-user.index')->with('success', 'Admin berhasil dihapus.');
    }
}
