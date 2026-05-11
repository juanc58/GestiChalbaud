<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSecurityAnswer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PasswordResetController extends Controller
{
    /**
     * Step 1: Get the security question associated with the cedula.
     */
    public function getQuestion(Request $request)
    {
        $request->validate([
            'cedula' => 'required|numeric'
        ]);

        $user = User::where('cedula', $request->cedula)->first();

        if (!$user) {
            return response()->json(['message' => 'Cédula no registrada en el sistema.'], 404);
        }

        $securityAnswer = UserSecurityAnswer::with('question')->where('user_id', $user->id)->first();

        if (!$securityAnswer || !$securityAnswer->question) {
            return response()->json(['message' => 'El usuario no tiene configurada una pregunta de seguridad.'], 400);
        }

        return response()->json([
            'question' => $securityAnswer->question->question
        ]);
    }

    /**
     * Step 2: Verify the provided answer against the hashed answer in DB.
     */
    public function verifyAnswer(Request $request)
    {
        $request->validate([
            'cedula' => 'required|numeric',
            'answer' => 'required|string'
        ]);

        $user = User::where('cedula', $request->cedula)->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        $securityAnswer = UserSecurityAnswer::where('user_id', $user->id)->first();

        if (!$securityAnswer) {
            return response()->json(['message' => 'Pregunta de seguridad no configurada.'], 400);
        }

        // Normalize answer to compare: lowercased, trimmed (optional based on how it was saved,
        // but if it's securely hashed using bcrypt, we must compare the exact string or at least a normalized version
        // Assuming it's hashed exactly as inputted. If it was lowercased before hashing during save,
        // we must lowercase it here. Let's assume standard hashing of trimmed lowercase for robustness.
        $inputAnswer = strtolower(trim($request->answer));

        if (!Hash::check($inputAnswer, $securityAnswer->answer) && !Hash::check($request->answer, $securityAnswer->answer)) {
             return response()->json(['message' => 'Respuesta incorrecta.'], 401);
        }

        // Generate an authorization token in the session for this user to reset password
        Session::put('password_reset_authorized_user_id', $user->id);
        Session::put('password_reset_expires_at', now()->addMinutes(15)); // 15 mins expiry

        return response()->json(['message' => 'Respuesta correcta. Proceda a cambiar la contraseña.']);
    }

    /**
     * Step 3: Perform the password reset.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $userId = Session::get('password_reset_authorized_user_id');
        $expiresAt = Session::get('password_reset_expires_at');

        if (!$userId || !$expiresAt || now()->greaterThan($expiresAt)) {
            Session::forget(['password_reset_authorized_user_id', 'password_reset_expires_at']);
            return response()->json(['message' => 'La sesión de recuperación ha expirado o es inválida.'], 403);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session authorization
        Session::forget(['password_reset_authorized_user_id', 'password_reset_expires_at']);

        return response()->json(['message' => 'Contraseña restablecida exitosamente.']);
    }
}
