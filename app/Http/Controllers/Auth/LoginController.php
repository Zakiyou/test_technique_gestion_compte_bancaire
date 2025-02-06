<?php
namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;
use Exception;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');  
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];

        $messages = [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return ResponseHelper::formatResponse(400, null, $error, false);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->email_verified_at !== null) {
                return ResponseHelper::formatResponse(200, null, 'Connexion réussie', true);
            } else {
                try {
                    $verificationCode = Str::random(6);

                    $user->update(['verification_code' => $verificationCode]);

                    Mail::to($user->email)->send(new VerificationMail($verificationCode));

                    return ResponseHelper::formatResponse(202, ['user_id' => $user->id], 'Votre compte n\'est pas encore vérifié. Un code vous a été envoyé par email.', true);
                } catch (Exception $e) {
                    return ResponseHelper::formatResponse(500, null, 'Erreur lors de l\'envoi du code de vérification.', false);
                }
            }
        }

        return ResponseHelper::formatResponse(400, null, 'Les identifiants sont incorrects.', false);
    }

    public function logout(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return ResponseHelper::formatResponse(400, null, 'Utilisateur non authentifié.', false);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return view('auth.login');  

    } catch (\Exception $e) {
        return ResponseHelper::formatResponse(500, null, 'Erreur lors de la déconnexion.', false);
    }
}

}
