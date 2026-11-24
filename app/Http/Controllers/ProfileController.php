<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\SecurityQuestion;
use App\Models\UserSecurityAnswer;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user()->load('teacher');
        $securityQuestions = SecurityQuestion::all();
        $userSecurityAnswer = UserSecurityAnswer::where('user_id', $user->id)->with('question')->first();
        return view('profile.edit', compact('user', 'securityQuestions', 'userSecurityAnswer'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'first_name'       => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_name'      => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'last_name'        => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_last_name' => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'phone'            => ['required', 'string', 'max:50', new \App\Rules\VenezuelanPhone()],
            'email'            => ['required', 'string', 'max:255', new \App\Rules\StrictEmail(), 'unique:users,email,' . $user->id],
        ];

        if ($user->isDocente()) {
            $rules['academic_degree'] = 'nullable|string|max:255';
        }

        if ($request->filled('security_answer')) {
            $rules['security_question_id'] = 'required|exists:security_questions,id';
            $rules['security_answer']      = 'required|string|max:255';
        }

        $request->validate($rules);

        // Update shared identity fields
        $user->update([
            'first_name'       => $request->first_name,
            'second_name'      => $request->second_name,
            'last_name'        => $request->last_name,
            'second_last_name' => $request->second_last_name,
            'phone'            => \App\Rules\VenezuelanPhone::normalize($request->phone),
            'email'            => mb_strtolower(trim($request->email), 'UTF-8'),
        ]);

        // Update security question if provided
        if ($request->filled('security_answer')) {
            UserSecurityAnswer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'security_question_id' => $request->security_question_id,
                    'answer'               => Hash::make(strtolower(trim($request->security_answer))),
                ]
            );
        }

        // Update role-specific fields
        if ($user->isDocente() && $user->teacher && $request->has('academic_degree')) {
            $user->teacher->update([
                'academic_degree' => $request->academic_degree,
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
