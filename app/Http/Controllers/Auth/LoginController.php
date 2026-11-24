<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\LoginRequest;
use App\Services\CaptchaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function authenticate(LoginRequest $request)
    {
        // 1. Verify Captcha
        if (!CaptchaService::verify($request->captcha)) {
            throw ValidationException::withMessages([
                'captcha' => ['El resultado del captcha es incorrecto.'],
            ]);
        }

        // 2. Clear captcha from session
        session()->forget('captcha_result');

        // 3. Attempt Login
        $credentials = ['cedula' => $request->cedula, 'password' => $request->password];
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            if (!$user->is_active || is_null($user->role_id)) {
                $isPending = $user->isPending();
                Auth::logout();
                throw ValidationException::withMessages([
                    'cedula' => [
                        $isPending
                            ? 'Tu solicitud de registro está pendiente de aprobación y asignación de rol por un administrador.'
                            : 'Tu cuenta ha sido suspendida. Por favor contacta a la administración.',
                    ],
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'cedula' => ['Las credenciales proporcionadas no coinciden con nuestros registros.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
