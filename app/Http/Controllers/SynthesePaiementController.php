<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Beneficiaire;
use DB;

class SynthesePaiementController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:synthese_paiement_par_village', ['only' => ['fetch','par_village']]);
        $this->middleware('permission:synthese_paiement_par_beneficiaire', ['only' => ['fetch_beneficiaire','par_beneficiaire']]);
    }

    public function par_village()
    {
        $pro = new Beneficiaire();
        $projets = $pro->projet();
        return view('fsb.syntheses.etat_paiement_par_village', compact('projets'));
    }
    
    public function fetch(Request $request)
    {
        $perPage = 30; // Nombre de lignes par page
        $page = (int) $request->query('page', 1); // Page actuelle
        $export = $request->query('export', false); // Paramètre pour exporter toutes les données

        // Définir les colonnes pour la somme
        $columns = [
            'nbr', 'effectFem', 'effectHom', 'ressources', 'totalFrais', 
            'fraisFlooz', 'fraisTmoney', 'effectifPayeFlooz', 'effectifPayeTmoney'
        ];

        // Requête pour calculer les sommes
        $sumQuery = DB::table('etat_ptf_village as e');

        // Construire les expressions de somme
        $selects = [];
        foreach (range(1, 6) as $index) {
            foreach ($columns as $column) {
                $selects[] = "SUM({$column}{$index}) as somme_{$column}{$index}";
            }
        }
        $sumQuery->selectRaw(implode(', ', $selects));

        // Appliquer les filtres pour les sommes
        $filters = [
            'e.reg' => $request->nom_reg,
            'e.pref' => $request->nom_pref,
            'e.comm' => $request->nom_comm,
            'e.cant' => $request->nom_cant,
            'e.vill' => $request->nom_vill,
            'e.projet_id' => $request->projet_id,
            'e.financement' => $request->financement
        ];
        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                $sumQuery->where($column, 'LIKE', '%' . $value . '%');
            }
        }

        // Obtenir les sommes totales
        $totals = $sumQuery->first();

        // Nouvelle instance de requête pour les données sans les sommes
        $dataQuery = DB::table('etat_ptf_village as e');

        // Sélectionner les colonnes de données sans somme
        $dataQuery->select(
            'e.reg', 'e.pref', 'e.comm', 'e.cant', 'e.vill', 'e.financement',
            DB::raw('e.nbr1, e.effectFem1, e.effectHom1, e.date1, e.datePrevu1, e.ressources1, e.totalFrais1, e.fraisFlooz1, e.fraisTmoney1, e.effectifPayeFlooz1, e.effectifPayeTmoney1, e.comment1'),
            DB::raw('e.nbr2, e.effectFem2, e.effectHom2, e.date2, e.datePrevu2, e.ressources2, e.totalFrais2, e.fraisFlooz2, e.fraisTmoney2, e.effectifPayeFlooz2, e.effectifPayeTmoney2, e.comment2'),
            DB::raw('e.nbr3, e.effectFem3, e.effectHom3, e.date3, e.datePrevu3, e.ressources3, e.totalFrais3, e.fraisFlooz3, e.fraisTmoney3, e.effectifPayeFlooz3, e.effectifPayeTmoney3, e.comment3'),
            DB::raw('e.nbr4, e.effectFem4, e.effectHom4, e.date4, e.datePrevu4, e.ressources4, e.totalFrais4, e.fraisFlooz4, e.fraisTmoney4, e.effectifPayeFlooz4, e.effectifPayeTmoney4, e.comment4'),
            DB::raw('e.nbr5, e.effectFem5, e.effectHom5, e.date5, e.datePrevu5, e.ressources5, e.totalFrais5, e.fraisFlooz5, e.fraisTmoney5, e.effectifPayeFlooz5, e.effectifPayeTmoney5, e.comment5'),
            DB::raw('e.nbr6, e.effectFem6, e.effectHom6, e.date6, e.datePrevu6, e.ressources6, e.totalFrais6, e.fraisFlooz6, e.fraisTmoney6, e.effectifPayeFlooz6, e.effectifPayeTmoney6, e.comment6')
        );

        // Appliquer les mêmes filtres pour les données
        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                $dataQuery->where($column, 'LIKE', '%' . $value . '%');
            }
        }

        // Compter le nombre total de lignes pour la pagination
        $totalRows = $dataQuery->count();

        // Si export = true, récupérer toutes les données filtrées, sinon utiliser la pagination
        if ($export) {
            $results = $dataQuery->get();
        } else {
            $results = $dataQuery->forPage($page, $perPage)->get();
        }

        return response()->json([
            'totals' => $totals,
            'total' => $totalRows,
            'data' => $results,
            'current_page' => $page,
            'per_page' => $perPage,
        ]);
    }




    public function par_beneficiaire()
    { 
        $pro = new Beneficiaire();
        $projets = $pro->projet();
        return view('fsb.syntheses.etat_paiement_par_beneficiaire', compact('projets'));
    }

    public function fetch_beneficiaire(Request $request)
    {
        $perPage = 30; // Nombre de lignes par page
        $page = (int) $request->query('page', 1); // Page actuelle
        $export = $request->query('export', false); // Paramètre pour exporter toutes les données

        // Définir les colonnes pour la somme
        $columns = ['montant', 'frais'];

        // Requête pour calculer les sommes
        $sumQuery = DB::table('etat_par_beneficiaires as e');

        // Construire les expressions de somme
        $selects = [];
        foreach (range(1, 6) as $index) {
            foreach ($columns as $column) {
                $selects[] = "SUM({$column}{$index}) as somme_{$column}{$index}";
            }
        }
        $selects[] = "SUM(montantEnv) as somme_montantEnv";
        $selects[] = "SUM(montantRecu) as somme_montantRecu";
        $selects[] = "SUM(frais) as somme_frais";

        // Ajouter toutes les expressions de somme dans une seule requête
        $sumQuery->selectRaw(implode(', ', $selects));

        // Appliquer les filtres pour les sommes
        $filters = [
            'e.reg' => $request->nom_reg,
            'e.pref' => $request->nom_pref,
            'e.comm' => $request->nom_comm,
            'e.cant' => $request->nom_cant,
            'e.vill' => $request->nom_vill,
            'e.projet_id' => $request->projet_id,
            'e.financement' => $request->financement,
            'e.nom' => $request->nom,
            'e.prenom' => $request->prenom,
            'e.cardNum' => $request->cardNum,
            'e.telephone' => $request->telephone
        ];
        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                $sumQuery->where($column, 'LIKE', '%' . $value . '%');
            }
        }

        // Obtenir les sommes totales
        $totals = $sumQuery->first();

        // Nouvelle instance de requête pour les données sans les sommes
        $dataQuery = DB::table('etat_par_beneficiaires as e');

        // Sélectionner les colonnes de données sans somme
        $dataQuery->select(
            'e.reg', 'e.pref', 'e.comm', 'e.cant', 'e.vill', 'e.financement', 'e.nom', 'e.prenom', 'e.sexe', 'e.cardNum', 'e.telephone',
            'montantEnv', 'montantRecu', 'frais',
            DB::raw('e.montant1, e.frais1'),
            DB::raw('e.montant2, e.frais2'),
            DB::raw('e.montant3, e.frais3'),
            DB::raw('e.montant4, e.frais4'),
            DB::raw('e.montant5, e.frais5'),
            DB::raw('e.montant6, e.frais6')
        );

        // Appliquer les mêmes filtres pour les données
        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                $dataQuery->where($column, 'LIKE', '%' . $value . '%');
            }
        }

        // Compter le nombre total de lignes pour la pagination
        $totalRows = $dataQuery->count();

        // Si `export` est activé, récupérer toutes les données filtrées, sinon utiliser la pagination
        if ($export) {
            $results = $dataQuery->get();
        } else {
            $results = $dataQuery->forPage($page, $perPage)->get();
        }

        return response()->json([
            'totals' => $totals,
            'total' => $totalRows,
            'data' => $results,
            'current_page' => $page,
            'per_page' => $perPage,
        ]);
    }

}