<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Only Admins
        if (!auth()->user()->isAdmin()) abort(403);

        $query = User::with('role')->orderBy('first_name')->orderBy('last_name');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $rules = [
            'first_name'       => 'required|string|max:255',
            'second_name'      => 'nullable|string|max:255',
            'last_name'        => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:50',
            'email'            => 'nullable|email|unique:users,email,' . $user->id,
            'cedula'           => 'required|integer|unique:users,cedula,' . $user->id,
            'role_id'          => 'required|exists:roles,id',
            'is_active'        => 'boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()];
        }

        $request->validate($rules, [
            'password.min'        => 'La contraseña debe tener al menos 8 caracteres.',
            'password.mixed_case' => 'La contraseña debe contener al menos una mayúscula.',
            'password.symbols'    => 'La contraseña debe contener al menos un carácter especial.',
        ]);

        $data = [
            'first_name'       => $request->first_name,
            'second_name'      => $request->second_name,
            'last_name'        => $request->last_name,
            'second_last_name' => $request->second_last_name,
            'phone'            => $request->phone,
            'email'            => $request->email,
            'cedula'           => $request->cedula,
            'role_id'          => $request->role_id,
            'is_active'        => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleStatus(User $user)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activado' : 'desactivado';
        return redirect()->back()->with('success', "Usuario {$status} correctamente.");
    }
}
