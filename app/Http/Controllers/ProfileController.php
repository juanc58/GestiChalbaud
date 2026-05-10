<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user()->load('teacher', 'representative');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'first_name'       => 'required|string|max:255',
            'second_name'      => 'nullable|string|max:255',
            'last_name'        => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:50',
            'email'            => 'nullable|email|unique:users,email,' . $user->id,
        ];

        if ($user->isRepresentative()) {
            $rules['phone_whatsapp']    = 'required|string|max:50';
            $rules['phone_local']       = 'nullable|string|max:50';
            $rules['facebook']          = 'nullable|string|max:255';
            $rules['job_title']         = 'nullable|string|max:255';
            $rules['workplace_address'] = 'nullable|string';
        }

        if ($user->isDocente()) {
            $rules['academic_degree'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        // Update shared identity fields in users table
        $user->update([
            'first_name'       => $request->first_name,
            'second_name'      => $request->second_name,
            'last_name'        => $request->last_name,
            'second_last_name' => $request->second_last_name,
            'phone'            => $request->phone,
            'email'            => $request->email,
        ]);

        // Update role-specific fields
        if ($user->isDocente() && $user->teacher) {
            $user->teacher->update([
                'academic_degree' => $request->academic_degree,
            ]);
        } elseif ($user->isRepresentative() && $user->representative) {
            $user->representative->update([
                'phone_whatsapp'    => $request->phone_whatsapp,
                'phone_local'       => $request->phone_local,
                'facebook'          => $request->facebook,
                'job_title'         => $request->job_title,
                'workplace_address' => $request->workplace_address,
            ]);
        }

        return back()->with('success', 'Perfil actualizado exitosamente.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)
                ->letters()->mixedCase()->numbers()->symbols()],
        ], [
            'password.min'        => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.mixed_case' => 'La nueva contraseña debe contener al menos una mayúscula.',
            'password.symbols'    => 'La nueva contraseña debe contener al menos un carácter especial.',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
