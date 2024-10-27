<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Beneficiaire;
use DB;

class BeneficiaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:canton-create|canton-edit|canton-show|canton-destroy', ['only' => ['index']]);
        $this->middleware('permission:beneficiaire-index', ['only' => ['index']]);
        $this->middleware('permission:etat_paiements', ['only' => ['etat_paiements']]);
    }
    /**
     * Display a listing of the resource.
     *etat_paiements
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fsb.beneficiaires.index');
    }

    public function fetch(Request $request)
    {
        $perPage = 30; // Nombre de lignes par page
        //$page = $request->query('page', 1); // Page actuelle
        $nom_reg = $request->nom_reg;
        $nom_comm = $request->nom_comm;
        $nom_vill = $request->nom_vill;
        $type_carte = $request->type_carte;
        $card_number = $request->card_number;
        $telephone = $request->telephone; 
        $rang = $request->rang;
        $id = $request->id;
        $projet_id = $request->projet_id;
        $date_naiss = $request->date_naiss;
        $nom = $request->nom;
        $prenom = $request->prenom;
        $sexe = $request->sexe;

        $beneficiaires = DB::table('beneficiaires as b')
        ->join('villages as v', 'v.id', '=', 'b.village_id')
                ->join('cantons', 'cantons.id', '=', 'v.canton_id')
                ->join('communes as c', 'c.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'c.prefecture_id')
                ->join('regions as r', 'r.id', '=', 'prefectures.region_id')
                ->select('b.id','r.nom_reg', 'c.nom_comm', 'v.nom_vill','b.menage_id', 'b.rang', 'b.nom', 'b.prenom', 'b.sexe','b.telephone')
                ->where('b.status', 'Active')
                ->when($nom_reg, function ($query, $nom_reg) {
                    return $query->where('r.nom_reg', 'like', "%$nom_reg%");
                })->when($nom_comm, function ($query, $nom_comm) {
                    return $query->where('c.nom_comm', 'like', "%$nom_comm%");
                })->when($nom_vill, function ($query, $nom_vill) {
                    return $query->where('v.nom_vill', 'like', "%$nom_vill%");
                })->when($nom, function ($query, $nom) {
                    return $query->where('nom', 'like', "%$nom%");
                })->when($prenom, function ($query, $prenom) {
                    return $query->where('prenom', 'like', "%$prenom%");
                })->when($id, function ($query, $id) {
                    return $query->where('menage_id', 'like', "%$id%");
                })->when($rang, function ($query, $rang) {
                    return $query->where('rang', $rang);
                })->when($date_naiss, function ($query, $date_naiss) {
                    return $query->where('date_naiss', $date_naiss);
                })->when($sexe, function ($query, $sexe) {
                    return $query->where('sexe', $sexe);
                })->when($telephone, function ($query, $telephone) {
                    return $query->where('telephone', 'like', "%$telephone%");
                })->when($type_carte, function ($query, $type_carte) {
                    return $query->where('type_carte', 'like', "%$type_carte%");
                })->when($card_number, function ($query, $card_number) {
                    return $query->where('card_number', 'like', "%$card_number%");
                })->when($projet_id, function ($query, $projet_id) {
                    return $query->where('v.projet_id', $projet_id);
                })->paginate($perPage);
        
        return response()->json($beneficiaires);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $beneficiaires = Beneficiaire::findOrFail($id);
        return response()->json($beneficiaires);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function paiements($telephone)
    {
        $beneficiaires = DB::table('paiements')
                                ->where('telephone', $telephone)
                                ->paginate(10);
        return response()->json($beneficiaires);
    }

    public function etat_paiements()
    {
        return view('fsb.beneficiaires.etat_paiements');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function fetch_etat_paiements(Request $request)
    {
        $type_transfert = null;
        if (!empty($request->type_transfert)) {
            $type_transfert = ($request->type_transfert == "MIE") ? ['e.mie' => 1] : ['e.tm' => 1];
        }

        $perPage = 30;
        $page = $request->query('page', 1);
        $isExport = $request->query('export', false);

        // Requête de base pour les sommes totales
        $sumQuery = DB::table('etatpaiements as e')
            ->join('villages as v', 'e.village_id', '=', 'v.id')
            ->join('cantons', 'cantons.id', '=', 'v.canton_id')
            ->join('communes as c', 'c.id', '=', 'cantons.commune_id')
            ->join('prefectures', 'prefectures.id', '=', 'c.prefecture_id')
            ->join('regions as r', 'r.id', '=', 'prefectures.region_id')
            ->selectRaw('SUM(e.SommeSucces) as totalSommeSucces, SUM(e.SommeTM) as totalSommeTM, SUM(e.SommeMIE) as totalSommeMIE, SUM(e.SommePending) as totalSommePending, SUM(e.SommeCancel) as totalSommeCancel, SUM(e.SommeFail) as totalSommeFail');

        // Appliquez les filtres
        $filters = [
            'r.nom_reg' => $request->nom_reg,
            'c.nom_comm' => $request->nom_comm,
            'v.nom_vill' => $request->nom_vill,
            'e.nom' => $request->nom,
            'e.prenom' => $request->prenom,
            'e.financement' => $request->financement,
            'e.sexe' => $request->sexe,
            'e.telephone' => $request->telephone,
            'e.type_card' => $request->type_card,
            'e.card_number' => $request->card_number,
        ];

        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                $sumQuery->where($column, 'LIKE', '%' . $value . '%');
            }
        }

        if (!empty($type_transfert)) {
            $sumQuery->where($type_transfert);
        }

        if ($request->SommeTM !== null) {
            $sumQuery->where(function ($subQuery) use ($request) {
                if ($request->SommeTM > 0) {
                    $subQuery->where('e.SommeTM', '>', 0);
                } elseif ($request->SommeTM === '0') {
                    $subQuery->where('e.SommeTM', '0');
                }
            });
        }

        if ($request->montant !== null) {
            $sumQuery->where(function ($subQuery) use ($request) {
                $montant = (int) $request->montant;
                if ($montant >= 100000) {
                    $subQuery->where('e.SommeTM', '>=', 100000);
                } else {
                    $ranges = [
                        [90000, 95000], [75000, 80000], [60000, 65000], [45000, 50000],
                        [30000, 35000], [15000, 17000], [0, 14000]
                    ];
                    foreach ($ranges as [$min, $max]) {
                        if ($montant >= $min && $montant < $max) {
                            $subQuery->whereBetween('e.SommeTM', [$min, $max]);
                            break;
                        }
                    }
                }
            });
        }

        // Obtenez les sommes totales
        $totals = $sumQuery->first();

        // Requête de base pour les données
        $dataQuery = DB::table('etatpaiements as e')
            ->join('villages as v', 'e.village_id', '=', 'v.id')
            ->join('cantons', 'cantons.id', '=', 'v.canton_id')
            ->join('communes as c', 'c.id', '=', 'cantons.commune_id')
            ->join('prefectures', 'prefectures.id', '=', 'c.prefecture_id')
            ->join('regions as r', 'r.id', '=', 'prefectures.region_id')
            ->select('e.id', 'e.card_number', 'e.nom', 'e.prenom', 'e.sexe', 'e.telephone', 'e.financement', 'e.nbr', 'e.SommeSucces', 'e.nombre_succes', 'SommeTM', 'SommeMIE', 'e.SommePending', 'e.nombre_pending', 'e.SommeCancel', 'e.nombre_Cancel', 'e.SommeFail', 'e.nombre_Fail');

        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                $dataQuery->where($column, 'LIKE', '%' . $value . '%');
            }
        }

        if (!empty($type_transfert)) {
            $dataQuery->where($type_transfert);
        }

        if ($request->SommeTM !== null) {
            $dataQuery->where(function ($subQuery) use ($request) {
                if ($request->SommeTM > 0) {
                    $subQuery->where('e.SommeTM', '>', 0);
                } elseif ($request->SommeTM === '0') {
                    $subQuery->where('e.SommeTM', '0');
                }
            });
        }

        if ($request->montant !== null) {
            $dataQuery->where(function ($subQuery) use ($request) {
                $montant = (int) $request->montant;
                if ($montant >= 100000) {
                    $subQuery->where('e.SommeTM', '>=', 100000);
                } else {
                    $ranges = [
                        [90000, 95000], [75000, 80000], [60000, 65000], [45000, 50000],
                        [30000, 35000], [15000, 17000], [0, 14000]
                    ];
                    foreach ($ranges as [$min, $max]) {
                        if ($montant >= $min && $montant < $max) {
                            $subQuery->whereBetween('e.SommeTM', [$min, $max]);
                            break;
                        }
                    }
                }
            });
        }

        // Obtenez toutes les données si export est activé, sinon appliquez la pagination
        if ($isExport) {
            $results = $dataQuery->get();
        } else {
            $totalRows = $dataQuery->count();
            $results = $dataQuery->forPage($page, $perPage)->get();
        }

        return response()->json([
            'totals' => $totals,
            'total' => $isExport ? $results->count() : $totalRows,
            'data' => $results,
            'current_page' => $isExport ? 1 : $page,
            'per_page' => $isExport ? $results->count() : $perPage,
        ]);
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    function getTotalFemmes(Request $request)
    {
        $nom_reg = $request->nom_reg;
        $projet = $request->projet;

        $totalFemmes = DB::table('beneficiaires as b')
        ->leftJoin('menages as m', 'b.menage_id', '=', 'm.id')
        ->join('villages as v', DB::raw('COALESCE(m.village_id, b.village_id)'), '=', 'v.id')
        ->join('cantons as c', 'v.canton_id', '=', 'c.id')
        ->join('communes as cc', 'c.commune_id', '=', 'cc.id')
        ->join('prefectures as p', 'cc.prefecture_id', '=', 'p.id')
        ->join('regions as r', 'p.region_id', '=', 'r.id')
        ->where('b.status','=', 'Active')
        ->when($projet, function ($query, $projet) {
            return $query->where('b.nature_projet',  $projet);
        })->when($nom_reg, function ($query, $nom_reg) {
            return $query->where('r.nom_reg',  $nom_reg);
        })->selectRaw('
            SUM(CASE 
                WHEN m.sexe_cm = "F" THEN 1 
                ELSE 0 
            END) AS total_femmes_menages,
            SUM(CASE 
                WHEN b.sexe = "F" AND b.menage_id NOT IN (SELECT id FROM menages) THEN 1 
                ELSE 0 
            END) AS total_femmes_vo_menages,
            SUM(
                CASE 
                    WHEN m.sexe_cm = "F" THEN 1 
                    ELSE 0 
                END 
                +
                CASE 
                    WHEN b.sexe = "F" AND b.menage_id NOT IN (SELECT id FROM menages) THEN 1 
                    ELSE 0 
                END
            ) AS total_femmes
        ')
    ->first();
            
        return response()->json($totalFemmes);
    }

    function countBeneficiairesAges60Plus(Request $request)
    {
        $nom_reg = $request->nom_reg;
        $projet = $request->projet;

        return DB::table('beneficiaires as b')
            ->join('villages as v', 'b.village_id', '=', 'v.id')
            ->join('cantons as c', 'v.canton_id', '=', 'c.id')
            ->join('communes as cc', 'c.commune_id', '=', 'cc.id')
            ->join('prefectures as p', 'cc.prefecture_id', '=', 'p.id')
            ->join('regions as r', 'p.region_id', '=', 'r.id')
            ->where('b.status','=', 'Active')
            ->whereRaw('TIMESTAMPDIFF(YEAR, b.date_naiss, CURDATE()) >= 60')
            ->when($projet, function ($query, $projet) {
                return $query->where('b.nature_projet',  $projet);
            })->when($nom_reg, function ($query, $nom_reg) {
                return $query->where('r.nom_reg',  $nom_reg);
            })->count();
    }


    function getTotalFemmesEtHommes(Request $request)
    {
        $nom_reg = $request->nom_reg;
        $projet = $request->projet;

        $total = DB::table('beneficiaires as b')
        ->join('villages as v', 'b.village_id', '=', 'v.id')
        ->join('cantons as c', 'v.canton_id', '=', 'c.id')
        ->join('communes as cc', 'c.commune_id', '=', 'cc.id')
        ->join('prefectures as p', 'cc.prefecture_id', '=', 'p.id')
        ->join('regions as r', 'p.region_id', '=', 'r.id')
        ->where('b.status','=', 'Active')
        ->when($projet, function ($query, $projet) {
            return $query->where('b.nature_projet',  $projet);
        })->when($nom_reg, function ($query, $nom_reg) {
            return $query->where('r.nom_reg',  $nom_reg);
        })->selectRaw('
            SUM(CASE
                WHEN b.sexe = "F" THEN 1
                ELSE 0
            END) AS total_femmes_beneficiaires,
            SUM(CASE
                WHEN b.sexe = "H" THEN 1
                ELSE 0
            END) AS total_hommes_beneficiaires,
            SUM(
                CASE
                    WHEN b.sexe = "F" THEN 1
                    ELSE 0
                END 
                +
                CASE
                    WHEN b.sexe = "H" THEN 1
                    ELSE 0 
                END
            ) AS total
        ')
    ->first();
            
        return response()->json($total);
    }


    public function auMoinsUnAge(Request $request)
    {
        $nom_reg = $request->nom_reg;
        $projet = $request->projet;

        $counts = DB::table('beneficiaires as b')
                ->join('membres as m', 'b.menage_id', '=', 'm.menage_id')
                ->join('villages as v', 'b.village_id', '=', 'v.id')
                ->join('cantons as c', 'v.canton_id', '=', 'c.id')
                ->join('communes as cc', 'c.commune_id', '=', 'cc.id')
                ->join('prefectures as p', 'cc.prefecture_id', '=', 'p.id')
                ->join('regions as r', 'p.region_id', '=', 'r.id')
                ->when($projet, function ($query, $projet) {
                    return $query->where('b.nature_projet',  $projet);
                })->when($nom_reg, function ($query, $nom_reg) {
                    return $query->where('r.nom_reg',  $nom_reg);
                })
                ->selectRaw("
                    COUNT(DISTINCT CASE WHEN m.age >= 60 THEN m.menage_id END) as count_age_60_plus,
                    COUNT(DISTINCT CASE WHEN m.disability = 'Oui' THEN m.menage_id END) as count_disability_yes
                ")
                ->first();
        
        return response()->json($counts);
    }

    public function cible_par_region(){
        $counts = DB::table('etatpaiements as e')
                ->join('villages as v', 'e.village_id', '=', 'v.id')
                ->join('cantons as c', 'v.canton_id', '=', 'c.id')
                ->join('communes as cc', 'c.commune_id', '=', 'cc.id')
                ->join('prefectures as p', 'cc.prefecture_id', '=', 'p.id')
                ->join('regions as r', 'p.region_id', '=', 'r.id')
                ->join('cibles_fsb as ccc', 'r.id', '=', 'ccc.region_id')
                ->select('r.nom_reg', 'ccc.cible', DB::RAW('count(e.id) as nbr'))
                ->where('e.SommeTM','>', 0)
                ->groupBy('r.nom_reg', 'ccc.cible')
                ->get();
        
        return response()->json($counts);

    }

    public function cible_par_six_tranche(){
        $counts = DB::table('etatpaiements as e')
                ->join('villages as v', 'e.village_id', '=', 'v.id')
                ->join('cantons as c', 'v.canton_id', '=', 'c.id')
                ->join('communes as cc', 'c.commune_id', '=', 'cc.id')
                ->join('prefectures as p', 'cc.prefecture_id', '=', 'p.id')
                ->join('regions as r', 'p.region_id', '=', 'r.id')
                ->join('cibles_fsb as ccc', 'r.id', '=', 'ccc.region_id')
                ->select(DB::RAW('count(e.id) as nbr'))
                ->where('e.SommeTM','>', 90000)
                ->get();
        
        return response()->json($counts);

    }

    public function cible_par_financement(){
        $counts = DB::table('etatpaiements as e')
        ->select(
            'e.financement',
            DB::raw('COUNT(e.id) as nbr'),
            DB::raw('
                MAX(CASE WHEN e.financement = "BM" THEN 63931 ELSE NULL END) as cible_BM,
                MAX(CASE WHEN e.financement = "ETAT" THEN 20476 ELSE NULL END) as cible_ETAT,
                MAX(CASE WHEN e.financement = "AFD" THEN 50736 ELSE NULL END) as cible_AFD
            ')
        )
        ->where('e.SommeTM', '>', 0)
        ->groupBy('e.financement')
        ->get();
        
        return response()->json($counts);

    }

    public function cible_par_financement_boucles(){
        $counts = DB::table('etatpaiements as e')
        ->select(
            'e.financement',
            DB::raw('COUNT(e.id) as nbr'),
            DB::raw('
                MAX(CASE WHEN e.financement = "BM" THEN 63931 ELSE NULL END) as cible_BM,
                MAX(CASE WHEN e.financement = "ETAT" THEN 20476 ELSE NULL END) as cible_ETAT,
                MAX(CASE WHEN e.financement = "AFD" THEN 50736 ELSE NULL END) as cible_AFD
            ')
        )
        ->where('e.SommeTM', '>', 90000)
        ->groupBy('e.financement')
        ->get();
        
        return response()->json($counts);

    }
}
