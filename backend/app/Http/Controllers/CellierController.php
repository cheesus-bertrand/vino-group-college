<?php

namespace App\Http\Controllers;

use App\Models\Cellier;
use Illuminate\Http\Request;

class CellierController extends Controller
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $request->validate(
            [
                'nom' => 'required|string|min:2|max:50|unique:celliers,nom',
            ],
            [
                'nom.required' => 'Le nom est obligatoire.',
                'nom.min' => 'Le nom doit contenir au moins 2 caractères.',
                'nom.max' => 'Le nom ne peut pas dépasser 50 caractères.',
                'nom.unique' => 'Ce nom existe déjà.',
            ]
        );

        // Création du cellier associé à l'usager connecté
        $cellier = Cellier::create([
            'nom' => $request->nom,
            'usager_id' => $request->user()->id,
        ]);

        // Retour de la réponse JSON avec le cellier créé
        return response()->json([
            'message' => 'Cellier créé avec succès',
            'data' => $cellier
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cellier $cellier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cellier $cellier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cellier $cellier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cellier $cellier)
    {
        //
    }
}
