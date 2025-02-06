<?php

namespace App\Http\Controllers;

use App\Models\CompteBancaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseHelper; 

class CompteBancaireController extends Controller
{
    
    public function index()
    {
        try {
            $comptes = Auth::user()->comptesBancaires; 

            return view('admin.acceuil', compact('comptes'));
        } catch (\Exception $e) {
            return ResponseHelper::formatResponse(500, null, 'Une erreur est survenue lors de la récupération des comptes bancaires. Message d\'erreur: ' . $e->getMessage(), false);
        }
    }

   
    public function store(Request $request)
    {
        try {
            $rules = [
                'numero_compte' => 'required|string|max:255|unique:comptes_bancaires',
                'titulaire_compte' => 'required|string|max:255',
            ];

            $messages = [
                'numero_compte.required' => 'Le champ numéro de compte est obligatoire.',
                'numero_compte.unique' => 'Ce numéro de compte existe déjà.',
                'titulaire_compte.required' => 'Le titulaire du compte est obligatoire.',
            ];

            $validator = \Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return ResponseHelper::formatResponse(400, null, $error, false);
            }

            $compte = new CompteBancaire();
            $compte->numero_compte = $request->numero_compte;
            $compte->titulaire_compte = $request->titulaire_compte;
            $compte->solde = 0.00;
            $compte->user_id = Auth::id();
            $compte->save();
            $compte->solde = number_format($compte->solde, 2); 
            $compte->created_at = $compte->created_at->format('d-m-Y');

            return ResponseHelper::formatResponse(201, [
                'compte' => $compte
            ], 'Le compte bancaire a été ajouté avec succès.');
        } catch (\Exception $e) {
            return ResponseHelper::formatResponse(500, null, 'Une erreur est survenue lors de l\'ajout du compte bancaire. Message d\'erreur: ' . $e->getMessage(), false);
        }
    }

    
    public function indexActifs()
    {
        try {
            $comptes = CompteBancaire::where('statut', 'actif')->with('user')->get();

            return view('admin.comptes_actifs', compact('comptes'));
        } catch (\Exception $e) {
            return ResponseHelper::formatResponse(500, null, 'Une erreur est survenue lors de la récupération des comptes actifs. Message d\'erreur: ' . $e->getMessage(), false);
        }
    }

    
    public function indexInactifs()
    {
        try {
            $comptes = CompteBancaire::where('statut', 'inactif')->with('user')->get();

            return view('admin.comptes_inactifs', compact('comptes'));
        } catch (\Exception $e) {
            return ResponseHelper::formatResponse(500, null, 'Une erreur est survenue lors de la récupération des comptes inactifs. Message d\'erreur: ' . $e->getMessage(), false);
        }
    }

    public function activer($id)
    {
        try {
            $compte = CompteBancaire::findOrFail($id);
            if ($compte->statut == 'actif') {
                return ResponseHelper::formatResponse(400, null, 'Le compte est déjà actif.', false);
            }

            $compte->statut = 'actif';
            $compte->save();

            return ResponseHelper::formatResponse(200, [
                'compte' => $compte
            ], 'Le compte a été activé avec succès.');
        } catch (\Exception $e) {
            return ResponseHelper::formatResponse(500, null, 'Une erreur est survenue lors de l\'activation du compte. Message d\'erreur: ' . $e->getMessage(), false);
        }
    }

    public function desactiver($id)
    {
        try {
            $compte = CompteBancaire::findOrFail($id);

            if ($compte->statut == 'inactif') {
                return ResponseHelper::formatResponse(400, null, 'Le compte est déjà inactif.', false);
            }

            $compte->statut = 'inactif';
            $compte->save();

            return ResponseHelper::formatResponse(200, [
                'compte' => $compte
            ], 'Le compte a été désactivé avec succès.');
        } catch (\Exception $e) {
            return ResponseHelper::formatResponse(500, null, 'Une erreur est survenue lors de la désactivation du compte. Message d\'erreur: ' . $e->getMessage(), false);
        }
    }

    
    public function voirGestionnaire($id)
    {
        try {
            $compte = CompteBancaire::findOrFail($id);

            $gestionnaire = $compte->user;

            if (!$gestionnaire) {
                return ResponseHelper::formatResponse(404, null, 'Aucun gestionnaire trouvé pour ce compte.', false);
            }
            return ResponseHelper::formatResponse(200, [
                'gestionnaire' => $gestionnaire
            ], 'ok', true);

        } catch (\Exception $e) {
            return ResponseHelper::formatResponse(500, null, 'Une erreur est survenue lors de la récupération du gestionnaire. Message d\'erreur: ' . $e->getMessage(), false);
        }
    }
}
