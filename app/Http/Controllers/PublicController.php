<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Classe;
use App\Models\Inscrit;

class PublicController extends Controller
{

    /**
     * Display a listing of the cantons.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $inscrits = Inscrit::join('classes', 'classes.id', '=', 'inscrits.classe_id')
                    ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('regions.nom_reg', 'communes.nom_comm', 'cantons.nom_cant', 'ecoles.nom_ecl', 'classes.nom_cla')
                    ->where(function ($query) {
                        $query->where('inscrits.effec_gar', '>', 0)
                              ->orWhere('inscrits.effec_fil', '>', 0);
                    })
                    ->where('inscrits.status', 1)
                    ->orderByDesc('inscrits.created_at')
                    ->get();
        return response()->json($inscrits);
    }
}
