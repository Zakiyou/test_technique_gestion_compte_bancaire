<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\CompteBancaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OperationEffectuee;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;

class OperationController extends Controller
{
  
    public function create()
    {
        $comptes = Auth::user()->comptesBancaires()->where('statut', 'actif')->get();
        return view('admin.operationscreate', compact('comptes'));
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'montant' => 'required|numeric|min:0.01',
                'compte_id' => 'required|exists:comptes_bancaires,id',
                'type_operation' => 'required|in:depot,retrait',
            ];

            $messages = [
                'montant.required' => 'Le montant est obligatoire.',
                'montant.numeric' => 'Le montant doit être un nombre valide.',
                'compte_id.required' => 'Le compte est obligatoire.',
                'compte_id.exists' => 'Le compte sélectionné est invalide.',
                'type_operation.required' => 'Le type d\'opération est obligatoire.',
                'type_operation.in' => 'Le type d\'opération doit être dépôt ou retrait.',
            ];

            $validator = \Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return ResponseHelper::formatResponse(400, null, $validator->errors()->first(), false);
            }

            $compte = CompteBancaire::where('id', $request->compte_id)->first();
            if (!$compte) {
                return ResponseHelper::formatResponse(404, null, 'Compte introuvable.', false);
            }
            if ($compte->statut !== 'actif') {
                return ResponseHelper::formatResponse(400, null, 'Le compte est inactif.', false);
            }
            if ($compte->user_id !== Auth::id()) {
                return ResponseHelper::formatResponse(403, null, 'Vous ne pouvez pas effectuer une opération sur un compte dont vous n\'êtes pas le titulaire.', false);
            }
            DB::beginTransaction();
            $nouveauSolde = $compte->solde;
            if ($request->type_operation == 'retrait') {
                if ($compte->solde < $request->montant) {
                    return ResponseHelper::formatResponse(400, null, 'Fonds insuffisants.', false);
                }
                $nouveauSolde -= $request->montant;
            } else {
                $nouveauSolde += $request->montant;
            }

            $compte->solde = $nouveauSolde;
            $compte->save();

            $operation = Operation::create([
                'comptes_bancaire_id' => $compte->id,
                'montant' => $request->montant,
                'type' => $request->type_operation,
                'solde' => $nouveauSolde,
            ]);
            DB::commit();

            if ($request->type_operation == 'retrait') {
        Mail::to(Auth::user()->email)->send(new OperationEffectuee($operation, $compte));
            }

            return ResponseHelper::formatResponse(201, [
                'operation' => $operation
            ], 'Opération effectuée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::formatResponse(500, null, 'Une erreur est survenue: ' . $e->getMessage(), false);
        }
    }

    public function index($compte_id)
    {
        $compte = CompteBancaire::where('id', $compte_id)->first();

        if (!$compte) {
            abort(404, 'Compte introuvable.');
        }

        $user = Auth::user();

        if ($compte->user_id !== $user->id) {
            if ($user->role !== 'admin') {
                return redirect()->route('access.denied');
            }
        }

        $operations = Operation::where('comptes_bancaire_id', $compte_id)
                               ->get();

        return view('admin.operationsindex', compact('compte', 'operations'));
    }
}

