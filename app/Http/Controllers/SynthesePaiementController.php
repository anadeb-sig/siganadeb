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

    public function par_village(){
        return view('fsb.syntheses.etat_paiement_par_village');
    }

    // public function fetch(Request $request)
    // {
    //     $perPage = 30; // Nombre de lignes par page
    //     $page = (int) $request->query('page', 1); // Page actuelle

    //     // Définir les colonnes pour la somme
    //     $columns = [
    //         'nbr', 'effectFem', 'effectHom', 'ressources', 'totalFrais', 
    //         'fraisFlooz', 'fraisTmoney', 'effectifPayeFlooz', 'effectifPayeTmoney'
    //     ];

    //     $sumQuery = DB::table('etat_ptf_village as e');

    //     // Initialiser une chaîne pour accumuler les expressions de somme
    //     $selects = [];

    //     // Construire les expressions de somme
    //     foreach (range(1, 6) as $index) {
    //         foreach ($columns as $column) {
    //             $selects[] = "SUM({$column}{$index}) as somme_{$column}{$index}";
    //         }
    //     }

        

    //     // Ajouter toutes les expressions de somme dans une seule requête
    //     $sumQuery->selectRaw(implode(', ', $selects));


    //     // Appliquer les filtres
    //     $filters = [
    //         'e.reg' => $request->nom_reg,
    //         'e.pref' => $request->nom_pref,
    //         'e.comm' => $request->nom_comm,
    //         'e.cant' => $request->nom_cant,
    //         'e.vill' => $request->nom_vill,
    //         'e.financement' => $request->financement
    //     ];

    //     foreach ($filters as $column => $value) {
    //         if (!empty($value)) {
    //             $sumQuery->where($column, 'LIKE', '%' . $value . '%');
    //         }
    //     }

    //     // Obtenez les sommes totales
    //     $totals = $sumQuery->first();

    //     // Cloner la requête pour paginer les données
    //     $dataQuery = clone $sumQuery;

    //     $dataQuery->select(
    //         'e.reg', 'e.pref', 'e.comm', 'e.cant', 'e.vill', 'e.financement',
    //         DB::raw('e.nbr1, e.effectFem1, e.effectHom1, e.date1, e.datePrevu1, e.ressources1, e.totalFrais1, e.fraisFlooz1, e.fraisTmoney1, e.effectifPayeFlooz1, e.effectifPayeTmoney1, e.comment1'),
    //         DB::raw('e.nbr2, e.effectFem2, e.effectHom2, e.date2, e.datePrevu2, e.ressources2, e.totalFrais2, e.fraisFlooz2, e.fraisTmoney2, e.effectifPayeFlooz2, e.effectifPayeTmoney2, e.comment2'),
    //         DB::raw('e.nbr3, e.effectFem3, e.effectHom3, e.date3, e.datePrevu3, e.ressources3, e.totalFrais3, e.fraisFlooz3, e.fraisTmoney3, e.effectifPayeFlooz3, e.effectifPayeTmoney3, e.comment3'),
    //         DB::raw('e.nbr4, e.effectFem4, e.effectHom4, e.date4, e.datePrevu4, e.ressources4, e.totalFrais4, e.fraisFlooz4, e.fraisTmoney4, e.effectifPayeFlooz4, e.effectifPayeTmoney4, e.comment4'),
    //         DB::raw('e.nbr5, e.effectFem5, e.effectHom5, e.date5, e.datePrevu5, e.ressources5, e.totalFrais5, e.fraisFlooz5, e.fraisTmoney5, e.effectifPayeFlooz5, e.effectifPayeTmoney5, e.comment5'),
    //         DB::raw('e.nbr6, e.effectFem6, e.effectHom6, e.date6, e.datePrevu6, e.ressources6, e.totalFrais6, e.fraisFlooz6, e.fraisTmoney6, e.effectifPayeFlooz6, e.effectifPayeTmoney6, e.comment6'),
    //         // Répétez pour les autres indices...
    //     );

    //     // Comptez le nombre total de lignes pour la pagination
    //     $totalRows = $dataQuery->count();

    //     // Obtenez les données paginées
    //     $results = $dataQuery->forPage($page, $perPage)->get();

    //     return response()->json([
    //         'totals' => $totals,
    //         'total' => $totalRows,
    //         'data' => $results,
    //         'current_page' => $page,
    //         'per_page' => $perPage,
    //     ]);
    // }

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




    public function par_beneficiaire(){
        return view('fsb.syntheses.etat_paiement_par_beneficiaire');
    }

    // public function fetch_beneficiaire(Request $request)
    // {
    //     $perPage = 30; // Nombre de lignes par page
    //     $page = (int) $request->query('page', 1); // Page actuelle

    //     // Définir les colonnes pour la somme
    //     $columns = [
    //         'montant','frais'
    //     ];

    //     $sumQuery = DB::table('etat_par_beneficiaires as e');

    //     // Initialiser une chaîne pour accumuler les expressions de somme
    //     $selects = [];

    //     // Construire les expressions de somme
    //     foreach (range(1, 6) as $index) {
    //         foreach ($columns as $column) {
    //             $selects[] = "SUM({$column}{$index}) as somme_{$column}{$index}";
    //         }
    //     }

    //     $selects["montantEnv"] = "SUM(montantEnv) as somme_montantEnv";
    //     $selects["montantRecu"] = "SUM(montantRecu) as somme_montantRecu";
    //     $selects["frais"] = "SUM(frais) as somme_frais";

        

    //     // Ajouter toutes les expressions de somme dans une seule requête
    //     $sumQuery->selectRaw(implode(', ', $selects));


    //     // Appliquer les filtres
    //     $filters = [
    //         'e.reg' => $request->nom_reg,
    //         'e.pref' => $request->nom_pref,
    //         'e.comm' => $request->nom_comm,
    //         'e.cant' => $request->nom_cant,
    //         'e.vill' => $request->nom_vill,
    //         'e.financement' => $request->financement,
    //         'e.nom' => $request->nom,
    //         'e.prenom' => $request->prenom,
    //         'e.cardNum' => $request->cardNum,
    //         'e.telephone' => $request->telephone
    //     ];

    //     foreach ($filters as $column => $value) {
    //         if (!empty($value)) {
    //             $sumQuery->where($column, 'LIKE', '%' . $value . '%');
    //         }
    //     }

    //     // Obtenez les sommes totales
    //     $totals = $sumQuery->first();

    //     // Cloner la requête pour paginer les données
    //     $dataQuery = clone $sumQuery;

    //     $dataQuery->select(
    //         'e.reg', 'e.pref', 'e.comm', 'e.cant', 'e.vill', 'e.financement','e.nom','e.prenom','e.sexe','e.cardNum','e.telephone','montantEnv','montantRecu','frais',
    //         DB::raw('e.montant1, e.frais1'),
    //         DB::raw('e.montant2, e.frais2'),
    //         DB::raw('e.montant3, e.frais3'),
    //         DB::raw('e.montant4, e.frais4'),
    //         DB::raw('e.montant5, e.frais5'),
    //         DB::raw('e.montant6, e.frais6')
    //     );

    //     // Comptez le nombre total de lignes pour la pagination
    //     $totalRows = $dataQuery->count();

    //     // Obtenez les données paginées
    //     $results = $dataQuery->forPage($page, $perPage)->get();

    //     return response()->json([
    //         'totals' => $totals,
    //         'total' => $totalRows,
    //         'data' => $results,
    //         'current_page' => $page,
    //         'per_page' => $perPage,
    //     ]);
    // }

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




// let excelButton = document.getElementById('excelButton');
// if (excelButton) {
// // Fonction de génération Excel au clic
// excelButton.addEventListener('click', () => {
//     const wb = XLSX.utils.book_new();
//     const ws = XLSX.utils.aoa_to_sheet([
//         [
//             "Région",
//             "Préfecture",
//             "Commune",
//             "Canton",
//             "Village",
//             "Financement",
//             "Nombre de bénéficiaires payés Tranche 1","Effectif Femmes payées Tranche 1","Effectif Hommes payés Tranche 1","Date de paiement Tranche 1",
//             "Date prévue paiement Tranche 1","Ressources transférées aux bénéficiaires Tranche 1","Total frais de retrait Tranche 1","Frais de retrait Flooz Tranche 1","Frais de retrait Tmoney Tranche 1","Effectif payé par Flooz Tranche 1","Effectif payé par Tmoney Tranche 1","Commentaires 1","Nombre de bénéficiaires payés Tranche 2","Effectif Femmes payées Tranche 2","Effectif Hommes payés Tranche 2","Date de paiement Tranche 2",
//             "Date prévue paiement Tranche 2","Ressources transférées aux bénéficiaires Tranche 2","Total frais de retrait Tranche 2","Frais de retrait Flooz Tranche 2","Frais de retrait Tmoney Tranche 2","Effectif payé par Flooz Tranche 2","Effectif payé par Tmoney Tranche 2","Commentaires 2","Nombre de bénéficiaires payés Tranche 3","Effectif Femmes payées Tranche 3","Effectif Hommes payés Tranche 3","Date de paiement Tranche 3",
//             "Date prévue paiement Tranche 3","Ressources transférées aux bénéficiaires Tranche 3","Total frais de retrait Tranche 3","Frais de retrait Flooz Tranche 3","Frais de retrait Tmoney Tranche 3","Effectif payé par Flooz Tranche 3","Effectif payé par Tmoney Tranche 3","Commentaires 3","Nombre de bénéficiaires payés Tranche 4","Effectif Femmes payées Tranche 4","Effectif Hommes payés Tranche 4","Date de paiement Tranche 4",
//             "Date prévue paiement Tranche 4","Ressources transférées aux bénéficiaires Tranche 4","Total frais de retrait Tranche 4","Frais de retrait Flooz Tranche 4","Frais de retrait Tmoney Tranche 4","Effectif payé par Flooz Tranche 4","Effectif payé par Tmoney Tranche 4","Commentaires 4","Nombre de bénéficiaires payés Tranche 5","Effectif Femmes payées Tranche 5","Effectif Hommes payés Tranche 5","Date de paiement Tranche 5",
//             "Date prévue paiement Tranche 5","Ressources transférées aux bénéficiaires Tranche 5","Total frais de retrait Tranche 5","Frais de retrait Flooz Tranche 5","Frais de retrait Tmoney Tranche 5","Effectif payé par Flooz Tranche 5","Effectif payé par Tmoney Tranche 5","Commentaires 5","Nombre de bénéficiaires payés Tranche 6","Effectif Femmes payées Tranche 6","Effectif Hommes payés Tranche 6","Date de paiement Tranche 6",
//             "Date prévue paiement Tranche 6","Ressources transférées aux bénéficiaires Tranche 6","Total frais de retrait Tranche 6","Frais de retrait Flooz Tranche 6","Frais de retrait Tmoney Tranche 6","Effectif payé par Flooz Tranche 6","Effectif payé par Tmoney Tranche 6","Commentaires 6",
//         ],  // En-têtes
//     ...data.data.map(item => [
//         item.reg,
//         item.pref,
//         item.comm,
//         item.cant,
//         item.vill,
//         item.financement,

//         item.nbr1,
//         item.effectFem1,
//         item.effectHom1,
//         item.date1,
//         item.datePrevu1,
//         item.ressources1,
//         item.totalFrais1,
//         item.fraisFlooz1,
//         item.fraisTmoney1,
//         item.effectifPayeFlooz1,
//         item.effectifPayeTmoney1,
//         item.comment1,

//         item.nbr2,
//         item.effectFem2,
//         item.effectHom2,
//         item.date2,
//         item.datePrevu2,
//         item.ressources2,
//         item.totalFrais2,
//         item.fraisFlooz2,
//         item.fraisTmoney2,
//         item.effectifPayeFlooz2,
//         item.effectifPayeTmoney2,
//         item.comment2,

//         item.nbr3,
//         item.effectFem3,
//         item.effectHom3,
//         item.date3,
//         item.datePrevu3,
//         item.ressources3,
//         item.totalFrais3,
//         item.fraisFlooz3,
//         item.fraisTmoney3,
//         item.effectifPayeFlooz3,
//         item.effectifPayeTmoney3,
//         item.comment3,

//         item.nbr4,
//         item.effectFem4,
//         item.effectHom4,
//         item.date4,
//         item.datePrevu4,
//         item.ressources4,
//         item.totalFrais4,
//         item.fraisFlooz4,
//         item.fraisTmoney4,
//         item.effectifPayeFlooz4,
//         item.effectifPayeTmoney4,
//         item.comment4,

//         item.nbr5,
//         item.effectFem5,
//         item.effectHom5,
//         item.date5,
//         item.datePrevu5,
//         item.ressources5,
//         item.totalFrais5,
//         item.fraisFlooz5,
//         item.fraisTmoney5,
//         item.effectifPayeFlooz5,
//         item.effectifPayeTmoney5,
//         item.comment5,

//         item.nbr6,
//         item.effectFem6,
//         item.effectHom6,
//         item.date6,
//         item.datePrevu6,
//         item.ressources6,
//         item.totalFrais6,
//         item.fraisFlooz6,
//         item.fraisTmoney6,
//         item.effectifPayeFlooz6,
//         item.effectifPayeTmoney6,
//         item.comment6,
//     ])
//     ]);
    
//     XLSX.utils.book_append_sheet(wb, ws, 'etatpaiement');

//     // Obtenir la date actuelle
//     let today = new Date();
//     let yyyy = today.getFullYear();
//     let mm = String(today.getMonth() + 1).padStart(2, '0'); // Mois de 0 à 11
//     let dd = String(today.getDate()).padStart(2, '0'); // Jours

//     // Créer le nom du fichier au format aaaa-mm-jj
//     let filename = yyyy + '_' + mm + '_' + dd + '_etatpaiement_par_village.xlsx';
//     XLSX.writeFile(wb, filename);
// });
// }