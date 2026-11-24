<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\SecurityQuestion;
use App\Models\UserSecurityAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $query = User::with(['role', 'teacher'])
            ->orderByRaw('role_id IS NULL DESC')
            ->orderBy('first_name')
            ->orderBy('last_name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
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
            if ($request->status === 'pending') {
                $query->whereNull('role_id');
            } elseif ($request->status === 'active') {
                $query->where('is_active', true)->whereNotNull('role_id');
            } elseif ($request->status === 'suspended') {
                $query->where('is_active', false)->whereNotNull('role_id');
            }
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = Role::orderBy('name')->get();
        $pendingCount = User::whereNull('role_id')->count();

        return view('users.index', compact('users', 'roles', 'pendingCount'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $roles = Role::orderBy('name')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($request->filled('cedula')) {
            $request->merge(['cedula' => preg_replace('/\D/', '', (string) $request->cedula)]);
        }

        $request->validate([
            'cedula'           => 'required|digits_between:6,10|unique:users,cedula',
            'first_name'       => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_name'      => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'last_name'        => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_last_name' => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'phone'            => ['required', 'string', 'max:50', new \App\Rules\VenezuelanPhone()],
            'email'            => ['required', 'string', 'max:255', new \App\Rules\StrictEmail(), 'unique:users,email'],
            'role_id'          => 'required|exists:roles,id',
            'password'         => ['nullable', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ], [
            'cedula.unique'       => 'Esta cédula ya está registrada en el sistema.',
            'password.min'        => 'La contraseña debe tener al menos 8 caracteres.',
            'password.mixed_case' => 'La contraseña debe contener al menos una mayúscula.',
            'password.symbols'    => 'La contraseña debe contener al menos un carácter especial.',
        ]);

        $rawPassword = $request->filled('password') ? $request->password : (string) $request->cedula;

        // Si el rol es `docente`, el evento User::saved crea automáticamente su ficha en `teachers`
        $user = User::create([
            'cedula'           => $request->cedula,
            'first_name'       => $request->first_name,
            'second_name'      => $request->second_name,
            'last_name'        => $request->last_name,
            'second_last_name' => $request->second_last_name,
            'phone'            => \App\Rules\VenezuelanPhone::normalize($request->phone),
            'email'            => mb_strtolower(trim($request->email), 'UTF-8'),
            'role_id'          => $request->role_id,
            'is_active'        => true,
            'password'         => Hash::make($rawPassword),
        ]);

        $roleName = $user->role?->name;
        $extraMsg = $roleName === 'docente'
            ? ' Se creó automáticamente su ficha docente para asignación de aula.'
            : '';

        $pwdNote = $request->filled('password') ? '' : " Clave inicial: {$request->cedula}.";

        return redirect()->route('users.index')->with('success', "Usuario registrado y activado exitosamente.{$extraMsg}{$pwdNote}");
    }

    public function approve(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);

        // Al guardar con rol `docente`, User::saved sincroniza automáticamente su registro en `teachers`
        $user->update([
            'role_id'   => $role->id,
            'is_active' => true,
        ]);

        $msg = $role->name === 'docente'
            ? "Solicitud de {$user->full_name} aprobada como Docente. Ya aparece disponible para asignación de aula."
            : "Solicitud de {$user->full_name} aprobada y activada con el rol " . ucfirst($role->name) . ".";

        return redirect()->back()->with('success', $msg);
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $roles = Role::orderBy('name')->get();
        $securityQuestions = SecurityQuestion::all();
        $userSecurityAnswer = UserSecurityAnswer::where('user_id', $user->id)->with('question')->first();
        return view('users.edit', compact('user', 'roles', 'securityQuestions', 'userSecurityAnswer'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($request->filled('cedula')) {
            $request->merge(['cedula' => preg_replace('/\D/', '', (string) $request->cedula)]);
        }

        $rules = [
            'first_name'       => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_name'      => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'last_name'        => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_last_name' => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'phone'            => ['required', 'string', 'max:50', new \App\Rules\VenezuelanPhone()],
            'email'            => ['required', 'string', 'max:255', new \App\Rules\StrictEmail(), 'unique:users,email,' . $user->id],
            'cedula'           => 'required|digits_between:6,10|unique:users,cedula,' . $user->id,
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

        $isActive = $request->has('is_active');

        $data = [
            'first_name'       => $request->first_name,
            'second_name'      => $request->second_name,
            'last_name'        => $request->last_name,
            'second_last_name' => $request->second_last_name,
            'phone'            => \App\Rules\VenezuelanPhone::normalize($request->phone),
            'email'            => mb_strtolower(trim($request->email), 'UTF-8'),
            'cedula'           => $request->cedula,
            'role_id'          => $request->role_id,
            'is_active'        => $isActive,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($user->teacher) {
            $user->teacher->update(['status' => $isActive ? 'Activo' : 'Inactivo']);
        }

        if ($request->filled('security_answer') && $request->filled('security_question_id')) {
            UserSecurityAnswer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'security_question_id' => $request->security_question_id,
                    'answer'               => Hash::make(strtolower(trim($request->security_answer))),
                ]
            );
        }

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleStatus(User $user)
    {
        if (!auth()->user()->isAdmin() || $user->id === auth()->id()) {
            abort(403);
        }

        if (is_null($user->role_id)) {
            return redirect()->back()->with('warning', 'Selecciona primero el rol a asignar para aprobar esta solicitud.');
        }

        $newStatus = !$user->is_active;
        $user->update(['is_active' => $newStatus]);

        if ($user->teacher) {
            $user->teacher->update(['status' => $newStatus ? 'Activo' : 'Inactivo']);
        }

        $status = $newStatus ? 'activado' : 'suspendido';
        return redirect()->back()->with('success', "Usuario {$status} correctamente.");
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin() || $user->id === auth()->id()) {
            abort(403);
        }

        UserSecurityAnswer::where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->back()->with('success', 'Registro de usuario eliminado correctamente.');
    }
}
