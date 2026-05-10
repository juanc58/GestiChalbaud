<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Representative;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterRepresentativeController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register_representative');
    }

    public function register(Request $request)
    {
        $request->validate([
            'cedula'           => ['required', 'integer', 'unique:users,cedula'],
            'first_name'       => ['required', 'string', 'max:255'],
            'second_name'      => ['nullable', 'string', 'max:255'],
            'last_name'        => ['required', 'string', 'max:255'],
            'second_last_name' => ['nullable', 'string', 'max:255'],
            'relationship'     => ['required', 'string', 'max:255'],
            'phone'            => ['required', 'string'],
            'phone_local'      => ['nullable', 'string'],
            'email'            => ['nullable', 'email', 'unique:users,email'],
            'facebook'         => ['nullable', 'string'],
            'job_title'        => ['nullable', 'string'],
            'workplace_address'=> ['nullable', 'string'],
            'password'         => ['required', 'string', 'confirmed', Password::min(8)
                ->letters()->mixedCase()->numbers()->symbols()],
        ], [
            'cedula.unique'      => 'Esta cédula ya se encuentra registrada en el sistema.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters'   => 'La contraseña debe contener al menos una letra.',
            'password.mixed_case'=> 'La contraseña debe contener al menos una mayúscula.',
            'password.numbers'   => 'La contraseña debe contener al menos un número.',
            'password.symbols'   => 'La contraseña debe contener al menos un carácter especial.',
        ]);

        $roleRep = Role::where('name', 'representative')->first();

        // 1. Create User with all shared identity fields
        $user = User::create([
            'cedula'           => $request->cedula,
            'first_name'       => $request->first_name,
            'second_name'      => $request->second_name,
            'last_name'        => $request->last_name,
            'second_last_name' => $request->second_last_name,
            'phone'            => $request->phone,
            'email'            => $request->email ?? $request->cedula . '@sepaez.representative.com',
            'role_id'          => $roleRep->id,
            'password'         => Hash::make($request->password),
        ]);

        // 2. Create Representative profile (role-specific data only)
        Representative::create([
            'user_id'           => $user->id,
            'relationship'      => $request->relationship,
            'phone_whatsapp'    => $request->phone,
            'phone_local'       => $request->phone_local,
            'facebook'          => $request->facebook,
            'job_title'         => $request->job_title,
            'workplace_address' => $request->workplace_address,
        ]);

        // 3. Auto Login
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', '¡Bienvenido! Tu cuenta de representante ha sido creada exitosamente.');
    }
}

