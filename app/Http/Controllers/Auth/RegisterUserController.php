<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SecurityQuestion;
use App\Models\UserSecurityAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterUserController extends Controller
{
    public function showRegistrationForm()
    {
        $securityQuestions = SecurityQuestion::all();
        return view('auth.register', compact('securityQuestions'));
    }

    public function register(Request $request)
    {
        if ($request->filled('cedula')) {
            $request->merge(['cedula' => preg_replace('/\D/', '', (string) $request->cedula)]);
        }

        $request->validate([
            'cedula'               => ['required', 'digits_between:6,10', 'unique:users,cedula'],
            'first_name'           => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_name'          => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'last_name'            => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_last_name'     => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'requested_role'       => ['required', 'in:docente,worker,admin'],
            'phone'                => ['required', 'string', 'max:50', new \App\Rules\VenezuelanPhone()],
            'email'                => ['required', 'string', 'max:255', new \App\Rules\StrictEmail(), 'unique:users,email'],
            'security_question_id' => ['required', 'exists:security_questions,id'],
            'security_answer'      => ['required', 'string', 'max:255'],
            'password'             => ['required', 'string', 'confirmed', Password::min(8)
                ->letters()->mixedCase()->numbers()->symbols()],
        ], [
            'cedula.unique'                => 'Esta cédula ya se encuentra registrada en Infochalbaud.',
            'email.unique'                 => 'Este correo electrónico ya está asociado a otro usuario.',
            'requested_role.required'      => 'Debes indicar el cargo o perfil que solicitas.',
            'security_question_id.required'=> 'Debes seleccionar una pregunta de seguridad.',
            'security_answer.required'     => 'La respuesta de seguridad es obligatoria.',
            'password.min'                 => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters'             => 'La contraseña debe contener al menos una letra.',
            'password.mixed_case'          => 'La contraseña debe contener al menos una mayúscula.',
            'password.numbers'             => 'La contraseña debe contener al menos un número.',
            'password.symbols'             => 'La contraseña debe contener al menos un carácter especial.',
        ]);

        // Crear usuario en estado PENDIENTE de aprobación y asignación de rol por Administrador
        $user = User::create([
            'cedula'           => $request->cedula,
            'first_name'       => $request->first_name,
            'second_name'      => $request->second_name,
            'last_name'        => $request->last_name,
            'second_last_name' => $request->second_last_name,
            'phone'            => \App\Rules\VenezuelanPhone::normalize($request->phone),
            'email'            => mb_strtolower(trim($request->email), 'UTF-8'),
            'role_id'          => null,
            'requested_role'   => $request->requested_role,
            'is_active'        => false,
            'password'         => Hash::make($request->password),
        ]);

        UserSecurityAnswer::create([
            'user_id'              => $user->id,
            'security_question_id' => $request->security_question_id,
            'answer'               => Hash::make(strtolower(trim($request->security_answer))),
        ]);

        return redirect()
            ->to(route('login') . '#login')
            ->with('registration_pending', '¡Solicitud registrada con éxito! Tu cuenta está pendiente de aprobación y asignación de rol por un administrador de Infochalbaud.');
    }
}

