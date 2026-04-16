<?php

namespace App\Http\Controllers;

use App\Models\ListeAchat;
use Illuminate\Http\Request;

class ListeAchatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Ajoute une bouteille de vin dans la liste d'achat
     */
    public function store(Request $request)
    {
         // Validation des données d'entrée
         $request->validate(
            [
                'usager_id' => 'required|exists:usagers,id',
                'vin_id' =>     'required|exists:vins,id',
            ],
        );

        // Vérification de l'existence du vin dans la liste d'achat
        if (ListeAchat::where('vin_id', $request->vin_id)
            ->where('usager_id', $request->usager_id)
            ->exists()
        ) {
            // Si le vin existe déjà dans la liste d'achat, retourner un message d'erreur
            return response()->json([
                'message' => "Ce vin existe déjà la liste d'achat."
            ], 422);
        }
        else {
            // Si le vin n'existe pas dans le cellier, inserer le vin dans le cellier
            $ListeAchat = ListeAchat::create([
                'usager_id' => $request->usager_id,
                'vin_id' => $request->vin_id,
            ]);

            // Retourner une réponse de succès
            return response()->json([
                'message' => "Bouteille a ete ajouté dans la liste d'achat avec succès",
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ListeAchat $listeAchat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListeAchat $listeAchat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListeAchat $listeAchat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListeAchat $listeAchat)
    {
        //
    }
}
