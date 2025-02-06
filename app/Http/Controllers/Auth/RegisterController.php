<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseHelper;

class RegisterController extends Controller
{
    public function Acceuil()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];

        $messages = [
            'name.required' => 'Le nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit comporter au moins 6 caractères.',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return ResponseHelper::formatResponse(400, null, $error, false);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'client',
                'verification_code' => Str::random(6),
            ]);

            Mail::to($user->email)->send(new VerificationMail($user->verification_code));

            return ResponseHelper::formatResponse(200, [
                'user_id' => $user->id
            ], 'Utilisateur inscrit, veuillez vérifier votre e-mail pour le code de confirmation.');
        } catch (\Exception $e) {
            return ResponseHelper::formatResponse(500, null, 'Une erreur est survenue lors de l\'inscription.', false);
        }
    }

    public function verifyCode(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'code' => 'required|string',
        ];

        $messages = [
            'user_id.required' => 'L\'ID de l\'utilisateur est obligatoire.',
            'user_id.exists' => 'L\'utilisateur spécifié n\'existe pas.',
            'code.required' => 'Le code de vérification est obligatoire.',
            'code.string' => 'Le code de vérification doit être une chaîne de caractères.',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return ResponseHelper::formatResponse(400, null, $error, false);
        }

        $user = User::find($request->user_id);

        if ($user->verification_code === $request->code) {
            Auth::login($user);

            $user->update([
                'verification_code' => null,  
                'email_verified_at' => now(), 
                'status' => 'actif',        
            ]);
            return ResponseHelper::formatResponse(200, null, 'Inscription confirmée, vous êtes maintenant connecté.');
        }

        return ResponseHelper::formatResponse(400, null, 'Le code est incorrect. Veuillez essayer à nouveau.', false);
    }
}
