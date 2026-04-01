<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vin;
use App\Services\SAQService;
use Symfony\Component\VarDumper\VarDumper;

class VinController extends Controller
{
    /**
     * Récupère les paramètres de pagination depuis la requête HTTP
     * page : numéro de la page actuelle (par défaut 1)
     * per_page : nombre de résultats par page (par défaut 12)
     * Récupère les filtres envoyés dans la requête, ou tableau vide si aucun
     * Initialise une requête Eloquent sur le modèle Vin
     * Filtre par un champ spécifié
     * @param Request $request
     * @return void
     */

    public function index(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 12);
        $filters = $request->get('filters', []);

        $query = Vin::query();

        if (!empty($filters['countries'])) {
            $query->whereIn('country', $filters['countries']);
        }

        if (!empty($filters['regions'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($filters['regions'] as $region) {
                    $q->orWhere('region', 'like', "%$region%");
                }
            });
        }

        if (!empty($filters['cepages'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($filters['cepages'] as $cepage) {
                    $q->orWhere('grape', 'like', "%$cepage%");
                }
            });
        }

        if (!empty($filters['prix'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($filters['prix'] as $prix) {
                    if (is_string($prix) && str_contains($prix, '-')) {
                        [$min, $max] = explode('-', $prix);
                        $q->orWhereBetween('price', [(float)$min, (float)$max]);
                    } else {
                        $q->orWhere('price', (float)$prix);
                    }
                }
            });
        }

        if (!empty($filters['formats'])) {
            $query->whereIn('litre', $filters['formats']);
        }

        if (!empty($filters['degres'])) {
            $query->whereIn('alcohol', array_map('floatval', $filters['degres']));
        }

        if (!empty($filters['producteurs'])) {
            $query->whereIn('producer', $filters['producteurs']);
        }

        if (!empty($filters['millesimes'])) {
            $query->whereIn('millesime', $filters['millesimes']);
        }

        if (!empty($filters['couleur'])) {
            $query->whereIn('couleur', $filters['couleur']);
        }

        $wines = $query->paginate($perPage, ['*'], 'page', $page);

        $allFilters = [
            'countries' => Vin::distinct()->pluck('country')->filter()->values(),
            'regions' => Vin::distinct()->pluck('region')->filter()->values(),
            'cepages' => Vin::distinct()->pluck('grape')->filter()->values(),
            'prix' => Vin::distinct()->pluck('price')->filter()->values(),

            'formats' => Vin::distinct()->pluck('litre')->filter()->values(),
            'degres' => Vin::distinct()->pluck('alcohol')->filter()->values(),
            'producteurs' => Vin::distinct()->pluck('producer')->filter()->values(),
            'millesimes' => Vin::distinct()->pluck('millesime')->filter()->values(),
            'couleur' => Vin::distinct()->pluck('couleur')->filter()->values(),
        ];

        return response()->json([
            'data' => $wines->items(),
            'total' => $wines->total(),
            'filters' => $allFilters,
        ]);
    }
    public function getVinsSaq(SAQService $service)
    {
        $bouteilles = $service->getWines();
        return $bouteilles;
    }
}
