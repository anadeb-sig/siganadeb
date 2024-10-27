<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Menu;
use App\Models\Repas;
use App\Models\Region;
use App\Models\Inscrit;
use Illuminate\Http\Request;
use App\Rules\Weekday;
use Exception;
use Validator;
use Auth;
use DB;
use Carbon\Carbon;   
use App\Exports\InscritsExport;
use Maatwebsite\Excel\Facades\Excel;

class RepasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:repas-create|repas-edit|repas-show|repas-destroy', ['only' => ['index']]);
        $this->middleware('permission:repas-create', ['only' => ['create','store']]);
        $this->middleware('permission:repas-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:repas-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:repas-show', ['only' => ['show']]);
        $this->middleware('permission:synthese-arf', ['only' => ['synthese_arf']]);
        $this->middleware('permission:synthese-ecole', ['only' => ['synthese_ecole']]);
        $this->middleware('permission:synthese-canton', ['only' => ['synthese_canton']]);
        $this->middleware('permission:synthese-commune', ['only' => ['synthese_commune']]);
        $this->middleware('permission:synthese-prefecture', ['only' => ['synthese_prefecture']]);
        $this->middleware('permission:synthese-region', ['only' => ['synthese_region']]);
        $this->middleware('permission:synthese-comptabilite', ['only' => ['synthese_comptabilite']]);
    }

    /**
     * Display a listing of the repas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {        
        //$inscrits = Inscrit::pluck('nom_cla','id')->all();
        $menus = Menu::all();
        $regions = Region::all();

        return view('repas.index', compact('menus', 'regions'));
    }

    public function fetch(){
       if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant')  || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                            ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                            // ->join('menus', function ($join) {
                            //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                            //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                            ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                            ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                            ->select('regions.nom_reg', 'repas.id', 'repas.effect_gar', 'repas.effect_fil', 'repas.date_rep', 'classes.nom_cla', 'ecoles.nom_ecl', 'repas.inscrit_id')
                            ->where('inscrits.status', 1)
                            
                            ->where(function ($query) {
                                $query->where('repas.effect_gar', '>', 0)
                                      ->orWhere('repas.effect_fil', '>', 0);
                            })
                            ->orderByDesc('repas.created_at')
                            ->get();
       }else{
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                            ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                            ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                            ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                            ->select('regions.nom_reg', 'repas.id', 'repas.effect_gar', 'repas.effect_fil', 'repas.date_rep', 'classes.nom_cla', 'ecoles.nom_ecl')
                            ->where('repas.user_id', Auth::user()->id)
                            
                            ->where(function ($query) {
                                $query->where('repas.effect_gar', '>', 0)
                                      ->orWhere('repas.effect_fil', '>', 0);
                            })
                            ->orderByDesc('repas.created_at')
                            ->get();
        }
        return response()->json($repas);    
    }

    /**
     * Show the form for creating a new repas.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $regions = Region::all();
        $Classes = Classe::pluck('nom_cla','id')->all();
        $menus = Menu::all();
        return view('repas.create', compact('Classes', 'menus', 'regions'));
    }

     /**
     * Show the form for creating a new repas.
     *
     * @return \Illuminate\View\View
     */
    public function create_syngle()
    {
        $regions = Region::all();
        $Classes = Classe::pluck('nom_cla','id')->all();
        $menus = Menu::all();
        return view('repas.create_syngle', compact('Classes', 'menus', 'regions'));
    }

    /**
     * Store a new repas in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request){
        
        $repas = new Repas();
        $concat0 = $repas->verifLigne($request->test0);

        // Date de début (2 mois avant la date système)
        $dateDebut = Carbon::now()->subMonths(4);

        // Date fin (la date système)
        $dateFin = Carbon::now();

        // Date "x" à vérifier
        $dateX = Carbon::parse($request->date_rep1);

        if ($request->eval == "prepri") {
            $validator = Validator::make($request->all(), [
                'inscrit_id0' => 'required',
                'effect_gar_0' => 'required|numeric',
                'effect_fil_0' => 'required|numeric',
                
                'inscrit_id1' => 'required',
                'effect_gar_1' => 'required|numeric',
                'effect_fil_1' => 'required|numeric',
                'date_rep1' => ['required', 'date', new Weekday],
                'descrip' => 'nullable',
    
                'menu_id' => 'required',
    
                'test0' => 'nullable',
                'test1' => 'nullable',
            ]);

            $repas = new Repas();

            $concat0 = $repas->verifLigne($request->test0);
            $concat1 = $repas->verifLigne($request->test1);


            if($validator->fails()){
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages(),
                ]);
            }else{
                $classe_0 = Inscrit::where('id', $request->inscrit_id0)
                        ->get();

                $effec_gar0 = 0;
                $effec_fil0 = 0;
                foreach($classe_0 as $value){
                    $effec_gar0 = $value->effec_gar;
                    $effec_fil0 = $value->effec_fil;
                };

                $classe_1 = Inscrit::where('id', $request->inscrit_id1)
                        ->get();

                $effec_gar1 = 0;
                $effec_fil1 = 0;
                foreach($classe_1 as $value){
                    $effec_gar1 = $value->effec_gar;
                    $effec_fil1 = $value->effec_fil;
                };

                $condition1 = true;
                $condition2 = $effec_gar0 >= $request->effect_gar_0; // Votre première condition ici
                $condition3 = $effec_fil0 >= $request->effect_fil_0; // Votre deuxième condition ici
                $condition4 = $effec_gar1 >= $request->effect_gar_1; // Votre troixième condition ici
                $condition5 = $effec_fil1 >= $request->effect_fil_1; // Votre quatrième condition ici

                if($effec_gar0 < $request->effect_gar_0) {
                    return response()->json([
                        'status' => 10,
                        'errors' => 'L\'effectif des garçons dépasse celui inscri au pré-scolaire.',
                    ]);
                }else if($effec_fil0 < $request->effect_fil_0){
                    return response()->json([
                        'status' => 11,
                        'errors' => 'L\'effectif des filles dépasse celui inscri au pré-scolaire.',
                    ]);
                }else if($effec_gar1 < $request->effect_gar_1) {
                    return response()->json([
                        'status' => 11,
                        'errors' => 'L\'effectif des garçons dépasse celui inscri au primaire.',
                    ]);
                }else if($effec_fil1 < $request->effect_fil_1){
                    return response()->json([
                        'status' => 12,
                        'errors' => 'L\'effectif des filles dépasse celui inscri au primaire.',
                    ]);
                }else if($concat0 > 0 || $concat1 > 0){
                    return response()->json([
                        'status' => 13,
                        'errors' => 'Le nombre de plats à cette date a été déjà ajouté.',
                    ]);
                }else if(!$dateX->between($dateDebut, $dateFin)){
                    return response()->json([
                        'status' => 20,
                        'errors' => 'La date renseignée n\'est pas dans la marge de 4 mois avant la date d\'aujourd\'hui.',
                    ]);
                }else if($condition1 && $condition2 && $condition3 && $condition4 && $condition5 && $concat0==0 && $concat1==0 && $dateX->between($dateDebut, $dateFin)) {

                    Repas::create([
                        'inscrit_id' => $request->inscrit_id0,
                        'menu_id' => $request->menu_id,
                        'effect_gar' => $request->effect_gar_0,
                        'effect_fil' => $request->effect_fil_0,
                        'date_rep' => $request->date_rep1,
                        'descrip' => $request->descrip,
                        'user_id' => Auth::user()->id,
                    ]);

                    Repas::create([
                        'inscrit_id' => $request->inscrit_id1,
                        'menu_id' => $request->menu_id,
                        'effect_gar' => $request->effect_gar_1,
                        'effect_fil' => $request->effect_fil_1,
                        'date_rep' => $request->date_rep1,
                        'descrip' => $request->descrip,
                        'user_id' => Auth::user()->id,
                    ]);
                    return response()->json([
                        'status' => 200,
                        'success' => 'Repas a été ajouté avec succès.',
                    ]);
                }
            }
############################################################################################################
        } else if ($request->eval == "prescolaire") {
            $validator = Validator::make($request->all(), [
                'inscrit_id0' => 'required',
                'effect_gar_0' => 'required|numeric',
                'effect_fil_0' => 'required|numeric',

                'date_rep1' => ['required', 'date', new Weekday],
                'descrip' => 'nullable',
    
                'menu_id' => 'required',
    
                'test0' => 'nullable'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages(),
                ]);
            }else{
                $classe = Inscrit::where('id', $request->inscrit_id0)
                        ->get();

                $effec_gar = 0;
                $effec_fil = 0;
                foreach($classe as $value){
                    $effec_gar = $value->effec_gar;
                    $effec_fil = $value->effec_fil;
                }



                $condition1 = true;
                $condition2 = $effec_gar >= $request->effect_gar_0; // Votre première condition ici
                $condition3 = $effec_fil >= $request->effect_fil_0; // Votre deuxième condition ici
                
                
                if($effec_gar < $request->effect_gar_0) {
                    return response()->json([
                        'status' => 10,
                        'errors' => 'L\'effectif des garçons dépasse celui inscri.',
                    ]);
                }else if($effec_fil < $request->effect_fil_0){
                    return response()->json([
                        'status' => 11,
                        'errors' => 'L\'effectif des filles dépasse celui inscri.',
                    ]);
                }else if($concat0 > 0){
                    return response()->json([
                        'status' => 12,
                        'errors' => 'Le nombre de plats à cette date a été déjà ajouté.',
                    ]);
                }else if(!$dateX->between($dateDebut, $dateFin)){
                    return response()->json([
                        'status' => 20,
                        'errors' => 'La date renseignée n\'est pas dans la marge de 2 mois avant la date d\'aujourd\'hui.',
                    ]);
                }else if($condition1 && $condition2 && $condition3 && $concat0==0 && $dateX->between($dateDebut, $dateFin)){
                    Repas::create([
                        'inscrit_id' => $request->inscrit_id0,
                        'menu_id' => $request->menu_id,
                        'effect_gar' => $request->effect_gar_0,
                        'effect_fil' => $request->effect_fil_0,
                        'date_rep' => $request->date_rep1,
                        'descrip' => $request->descrip,
                        'user_id' => Auth::user()->id,
                    ]);

                    return response()->json([
                        'status' => 200,
                        'success' => 'Repas a été ajouté avec succès.',
                    ]);
                }
            }
############################################################################################################
            
        } else if ($request->eval == "primaire") {
            $validator = Validator::make($request->all(), [
                'inscrit_id0' => 'required',
                'effect_gar_1' => 'required|numeric',
                'effect_fil_1' => 'required|numeric',
                'date_rep1' => ['required', 'date', new Weekday],
                'descrip' => 'nullable',
                'menu_id' => 'required',
                'test0' => 'nullable'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages(),
                ]);
            }else{
                $classe = Inscrit::where('id', $request->inscrit_id0)
                        ->get();

                $effec_gar = 0;
                $effec_fil = 0;
                foreach($classe as $value){
                    $effec_gar = $value->effec_gar;
                    $effec_fil = $value->effec_fil;
                }



                $condition1 = true;
                $condition2 = $effec_gar >= $request->effect_gar_1; // Votre première condition ici
                $condition3 = $effec_fil >= $request->effect_fil_1; // Votre deuxième condition ici
                
                
                if($effec_gar < $request->effect_gar_1) {
                    return response()->json([
                        'status' => 10,
                        'errors' => 'L\'effectif des garçons dépasse celui inscri.',
                    ]);
                }else if($effec_fil < $request->effect_fil_1){
                    return response()->json([
                        'status' => 11,
                        'errors' => 'L\'effectif des filles dépasse celui inscri.',
                    ]);
                }else if($concat0 > 0){
                    return response()->json([
                        'status' => 12,
                        'errors' => 'Le nombre de plats à cette date a été déjà ajouté.',
                    ]);
                }else if(!$dateX->between($dateDebut, $dateFin)){
                    return response()->json([
                        'status' => 20,
                        'errors' => 'La date renseignée n\'est pas dans la marge de 2 mois avant la date d\'aujourd\'hui.',
                    ]);
                }else if($condition1 && $condition2 && $condition3 && $concat0==0 && $dateX->between($dateDebut, $dateFin)){
                    Repas::create([
                        'inscrit_id' => $request->inscrit_id0,
                        'menu_id' => $request->menu_id,
                        'effect_gar' => $request->effect_gar_1,
                        'effect_fil' => $request->effect_fil_1,
                        'date_rep' => $request->date_rep1,
                        'descrip' => $request->descrip,
                        'user_id' => Auth::user()->id,
                    ]);

                    return response()->json([
                        'status' => 200,
                        'success' => 'Repas a été ajouté avec succès.',
                    ]);
                }
            }
        }
    }

    /**
     * Display the specified repas.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {        
        $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'repas.id', 'effect_gar', 'effect_fil', 'nom_cla', 'nom_ecl', 'date_rep')
                        ->findOrFail($id);
        return response()->json($repas);
    }

    /**
     * Show the form for editing the specified repas.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Repas $repas)
    {
        return response()->json($repas);
    }

    /**
     * Update the specified repas in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'inscrit_id' => 'required',
            'menu_id' => 'required',
            'effect_gar' => 'required|numeric',
            'effect_fil' => 'required|numeric',
            'date_rep' => ['required', 'date', new Weekday]
        ]);

        // Date de début (2 mois avant la date système)
        $dateDebut = Carbon::now()->subMonths(4);

        // Date fin (la date système)
        $dateFin = Carbon::now();

        // Date "x" à vérifier
        $dateX = Carbon::parse($request->date_rep);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }else{

            $classe = Inscrit::where('id', $request->inscrit_id)
                        ->get();

            $effec_gar = 0;
            $effec_fil = 0;
            foreach($classe as $value){
                $effec_gar = $value->effec_gar;
                $effec_fil = $value->effec_fil;
            }

            $condition1 = true;
            $condition2 = $effec_gar >= $request->effect_gar; // Votre première condition ici
            $condition3 = $effec_fil >= $request->effect_fil; // Votre deuxième condition ici
                
                
            if($effec_gar < $request->effect_gar) {
                return response()->json([
                    'status' => 10,
                    'errors' => 'L\'effectif des garçons dépasse celui inscri.',
                ]);
            }else if($effec_fil < $request->effect_fil){
                return response()->json([
                    'status' => 11,
                    'errors' => 'L\'effectif des filles dépasse celui inscri.',
                ]);
            }else if(!$dateX->between($dateDebut, $dateFin)){
                return response()->json([
                    'status' => 20,
                    'errors' => 'La date renseignée n\'est pas dans la marge de 2 mois avant la date d\'aujourd\'hui.',
                ]);
            }else if($condition1 && $condition2 && $condition3 && $dateX->between($dateDebut, $dateFin)){
                    
                $repas = Repas::findOrFail($id);
                $repas->update([
                        'inscrit_id' => $request->inscrit_id,
                        'menu_id' => $request->menu_id,
                        'effect_gar' => $request->effect_gar,
                        'effect_fil' => $request->effect_fil,
                        'date_rep' => $request->date_rep
                ]);
                return response()->json([
                    'status' => 200,
                    'success' => 'Repas a été modifié avec succès.',
                ]);
            }
        }
    }

    /**
     * Remove the specified repas from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Repas $repas)
    {
        try {
            $repas->delete();

            return redirect()->route('repas.index')
                ->with('success_message', 'Repas a été supprimé avec succès.');
            } catch (Exception $exception) {
                return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
            }
    }

    
    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'inscrit_id' => 'required',
            'menu_id' => 'required',
            'effect_gar' => 'required|numeric|min:0|max:2147483647',
            'effect_fil' => 'required|numeric|min:0|max:4294967295',
            'date_rep' => ['required', 'date', new Weekday] 
        ];
        $data = $request->validate($rules);
        return $data;
    }

    //Rapport synthétique de fourniture du repas
    public function arf(Request $request){
        $start = $request->start;
        $end = $request->end;
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant')  || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'nom_ecl', 'menus.cout_unt', 'financements.nom_fin', 'repas.date_rep', 'users.last_name', 'users.first_name', DB::raw('
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        ->where('inscrits.status', 1)
                        
                        //->whereBetween('repas.date_rep', [$start, $end])
                        ->orderBy('repas.date_rep', 'desc')
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'financements.nom_fin', 'repas.date_rep', 'menus.cout_unt', 'users.last_name', 'users.first_name')
                        //->limit(100)
                        ->get();
        }else{
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'nom_ecl', 'menus.cout_unt', 'financements.nom_fin', 'repas.date_rep', 'users.last_name', 'users.first_name', DB::raw('
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        ->where('inscrits.status', 1)
                        
                        ->where('user_id', Auth::user()->id)
                        ->whereBetween('repas.date_rep', [$start, $end])
                        ->orderBy('repas.date_rep', 'desc')
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'financements.nom_fin', 'repas.date_rep', 'menus.cout_unt', 'users.last_name', 'users.first_name')
                        //->limit(100)
                        ->get();
        }
            return response()->json($repas);
    }

    public function synthese_arf(){
        return view('synthese.arf');
    }

    //Par cantine
    public function par_ecole(Request $request){
        $start = $request->start;
        $end = $request->end;
        $nom_reg = $request->nom_reg;
        $nom_cant = $request->nom_cant;
        $nom_ecl = $request->nom_ecl;
        $nom_fin = $request->nom_fin;
        if (Auth::user()->hasRole('Admin')|| Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'nom_ecl', 'financements.nom_fin', 'users.last_name', 'users.first_name', 'menus.cout_unt', DB::raw('
                            COUNT(DISTINCT date_rep) as "nbrjr_max",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Primaire" THEN date_rep END) as "nbrjr_pri",
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Pré_scolaire" THEN date_rep END) as "nbrjr_pre",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_reg, function ($query, $nom_reg) {
                            return $query->where('regions.nom_reg', $nom_reg);
                        })
                        ->when($nom_cant, function ($query, $nom_cant) {
                            return $query->where('cantons.nom_cant', $nom_cant);
                        })
                        ->when($nom_ecl, function ($query, $nom_ecl) {
                            return $query->where('ecoles.nom_ecl', $nom_ecl);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'financements.nom_fin', 'users.last_name', 'users.first_name', 'menus.cout_unt')
                        ->orderBy('ecoles.nom_ecl', 'desc')
                        ->get();
        }else{
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'nom_ecl', 'financements.nom_fin', 'users.last_name', 'users.first_name', 'menus.cout_unt', DB::raw('
                            COUNT(DISTINCT date_rep) as "nbrjr_max",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Primaire" THEN date_rep END) as "nbrjr_pri",
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Pré_scolaire" THEN date_rep END) as "nbrjr_pre",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        ->where('user_id', Auth::user()->id)
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_reg, function ($query, $nom_reg) {
                            return $query->where('regions.nom_reg', $nom_reg);
                        })
                        ->when($nom_cant, function ($query, $nom_cant) {
                            return $query->where('cantons.nom_cant', $nom_cant);
                        })
                        ->when($nom_ecl, function ($query, $nom_ecl) {
                            return $query->where('ecoles.nom_ecl', $nom_ecl);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'financements.nom_fin', 'users.last_name', 'users.first_name', 'menus.cout_unt')
                        ->orderBy('ecoles.nom_ecl', 'desc')
                        ->get();
        }
            return response()->json($repas);
    }

    public function synthese_ecole(){
        $regions = Region::all();
        return view('synthese.ecole', compact('regions'));
    }

    //Par canton
    public function par_canton(Request $request){
        $start = $request->start;
        $end = $request->end;
        $nom_reg = $request->nom_reg;
        $nom_cant = $request->nom_cant;
        $nom_fin = $request->nom_fin;
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'menus.cout_unt', DB::raw('
                            COUNT(DISTINCT date_rep) as "nbrjr_max",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Primaire" THEN date_rep END) as "nbrjr_pri",
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Pré_scolaire" THEN date_rep END) as "nbrjr_pre",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_reg, function ($query, $nom_reg) {
                            return $query->where('regions.nom_reg', $nom_reg);
                        })
                        ->when($nom_cant, function ($query, $nom_cant) {
                            return $query->where('cantons.nom_cant', $nom_cant);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->orderBy('prefectures.nom_pref', 'desc')
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'menus.cout_unt')
                        ->get();
        }else{
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'menus.cout_unt', DB::raw('
                            COUNT(DISTINCT date_rep) as "nbrjr_max",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Primaire" THEN date_rep END) as "nbrjr_pri",
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Pré_scolaire" THEN date_rep END) as "nbrjr_pre",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_reg, function ($query, $nom_reg) {
                            return $query->where('regions.nom_reg', $nom_reg);
                        })
                        ->when($nom_cant, function ($query, $nom_cant) {
                            return $query->where('cantons.nom_cant', $nom_cant);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('prefectures.nom_pref', 'desc')
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'menus.cout_unt')
                        ->get();
        }
            return response()->json($repas);
    }

    public function synthese_canton(){
        return view('synthese.canton');
    }

    //Par commune
    public function par_commune(Request $request){
        $start = $request->start;
        $end = $request->end;
        $nom_reg = $request->nom_reg;
        $nom_comm = $request->nom_comm;
        $nom_fin = $request->nom_fin;
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'menus.cout_unt', DB::raw('
                            COUNT(DISTINCT date_rep) as "nbrjr_max",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Primaire" THEN date_rep END) as "nbrjr_pri",
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Pré_scolaire" THEN date_rep END) as "nbrjr_pre",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_reg, function ($query, $nom_reg) {
                            return $query->where('regions.nom_reg', $nom_reg);
                        })
                        ->when($nom_comm, function ($query, $nom_comm) {
                            return $query->where('communes.nom_comm', $nom_comm);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->orderBy('prefectures.nom_pref', 'desc')
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'menus.cout_unt')
                        ->get();
        }else{
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'menus.cout_unt', DB::raw('
                            COUNT(DISTINCT date_rep) as "nbrjr_max",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Primaire" THEN date_rep END) as "nbrjr_pri",
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Pré_scolaire" THEN date_rep END) as "nbrjr_pre",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_reg, function ($query, $nom_reg) {
                            return $query->where('regions.nom_reg', $nom_reg);
                        })
                        ->when($nom_comm, function ($query, $nom_comm) {
                            return $query->where('communes.nom_comm', $nom_comm);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('prefectures.nom_pref', 'desc')
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'menus.cout_unt')
                        ->get();
        }
            return response()->json($repas);
    }

    public function synthese_commune(){
        return view('synthese.commune');
    }

    //Par préfecture
    public function par_prefecture(Request $request){
        $start = $request->start;
        $end = $request->end;
        $nom_pref = $request->nom_pref;
        $nom_fin = $request->nom_fin;
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('regions.nom_reg', 'prefectures.nom_pref', 'menus.cout_unt', DB::raw('
                            COUNT(DISTINCT date_rep) as "nbrjr_max",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Primaire" THEN date_rep END) as "nbrjr_pri",
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Pré_scolaire" THEN date_rep END) as "nbrjr_pre",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_pref, function ($query, $nom_pref) {
                            return $query->where('prefectures.nom_pref', $nom_pref);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->orderBy('prefectures.nom_pref', 'desc')
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'menus.cout_unt')
                        ->get();
        }else{
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('regions.nom_reg', 'prefectures.nom_pref', 'menus.cout_unt', DB::raw('
                            COUNT(DISTINCT date_rep) as "nbrjr_max",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Primaire" THEN date_rep END) as "nbrjr_pri",
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Pré_scolaire" THEN date_rep END) as "nbrjr_pre",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_pref, function ($query, $nom_pref) {
                            return $query->where('prefectures.nom_pref', $nom_pref);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('prefectures.nom_pref', 'desc')
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'menus.cout_unt')
                        ->get();
        }
            return response()->json($repas);
    }

    public function synthese_prefecture(){
        return view('synthese.prefecture');
    }

    //Par région avec financement
    public function par_region(Request $request){
        $start = $request->start;
        $end = $request->end;
        $nom_reg = $request->nom_reg;
        $nom_fin = $request->nom_fin;
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('nom_reg', 'menus.cout_unt', DB::raw('
                            COUNT(DISTINCT date_rep) as "nbrjr_max",
                            COUNT(DISTINCT CASE WHEN TRIM(nom_cla) = "Primaire" THEN date_rep END) as "nbrjr_pri",
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            COUNT(DISTINCT CASE WHEN TRIM(nom_cla) = "Pré_scolaire" THEN date_rep END) as "nbrjr_pre",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_reg, function ($query, $nom_reg) {
                            return $query->where('regions.nom_reg', $nom_reg);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->orderBy('regions.nom_reg', 'desc')
                        ->groupBy('regions.nom_reg', 'menus.cout_unt')
                        //->limit(100)
                        ->get();
        }else{
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('nom_reg', 'menus.cout_unt', DB::raw('
                            COUNT(DISTINCT date_rep) as "nbrjr_max",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Primaire" THEN date_rep END) as "nbrjr_pri",
                            SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                            SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                            COUNT(DISTINCT CASE WHEN nom_cla = "Pré_scolaire" THEN date_rep END) as "nbrjr_pre",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                            SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_reg, function ($query, $nom_reg) {
                            return $query->where('regions.nom_reg', $nom_reg);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('regions.nom_reg', 'desc')
                        ->groupBy('regions.nom_reg', 'menus.cout_unt')
                        //->limit(100)
                        ->get();
        }
            return response()->json($repas);
    }

    public function synthese_region(){
        return view('synthese.region');
    }

    public function par_compta(Request $request){
        $start = $request->start;
        $end = $request->end;
        $nom_reg = $request->nom_reg;
        $nom_cant = $request->nom_cant;
        $nom_ecl = $request->nom_ecl;
        $nom_fin = $request->nom_fin;
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        // ->join('menus', function ($join) {
                        //     $join->on('repas.date_rep', '>=', 'menus.date_debut')
                        //         ->on('repas.date_rep', '<=', 'menus.date_fin');})
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('nom_reg', 'nom_pref', 'nom_comm','cout_unt', 'nom_cant', 'nom_vill', 'nom_ecl', 'repas.date_rep', DB::raw('
                            SUM(effect_gar) as "garcons",
                            SUM(effect_fil) as "filles"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->when($nom_reg, function ($query, $nom_reg) {
                            return $query->where('regions.nom_reg', $nom_reg);
                        })
                        ->when($nom_cant, function ($query, $nom_cant) {
                            return $query->where('cantons.nom_cant', $nom_cant);
                        })
                        ->when($nom_ecl, function ($query, $nom_ecl) {
                            return $query->where('ecoles.nom_ecl', $nom_ecl);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'repas.date_rep', 'cout_unt')
                        //->limit(100)
                        ->get();
        }else{
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        ->join('users', 'users.id', '=', 'repas.user_id')
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'nom_ecl', 'repas.date_rep', DB::raw('
                            SUM(effect_gar) as "garcons",
                            SUM(effect_fil) as "filles"'))
                        
                        ->where('inscrits.status', 1)
                        
                        ->where('user_id', Auth::user()->id)
                        ->when($nom_reg, function ($query, $nom_reg) {
                            return $query->where('regions.nom_reg', $nom_reg);
                        })
                        ->when($nom_cant, function ($query, $nom_cant) {
                            return $query->where('cantons.nom_cant', $nom_cant);
                        })
                        ->when($nom_ecl, function ($query, $nom_ecl) {
                            return $query->where('ecoles.nom_ecl', $nom_ecl);
                        })
                        ->when($nom_fin, function ($query, $nom_fin) {
                            return $query->where('financements.nom_fin', $nom_fin);
                        })
                        ->when($start && $end, function ($query) use ($start, $end) {
                            return $query->whereBetween('repas.date_rep', [$start, $end]);
                        }, function ($query) {
                            // Si aucune plage de dates n'est fournie, vous pouvez définir une valeur par défaut ici
                            return $query->whereBetween('repas.date_rep', ['2023-01-01', '2024-12-01']);
                        })
                        ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'repas.date_rep')
                        //->limit(100)
                        ->get();
        }
            return response()->json($repas);
    }

    public function synthese_comptabilite(){
        return view('synthese.comptabilite');
    }

    //************ Les statistiques **************** */
    public function char_parsexe(){
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                ->join('menus', 'menus.id', '=', 'repas.menu_id')
                ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                ->select(DB::raw('                    
                    SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                    SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                    SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                    SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                
                ->where('inscrits.status', 1)
                ->get();
            return response()->json($repas);
        }else{
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
            ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
            ->join('menus', 'menus.id', '=', 'repas.menu_id')
            ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
            ->select(DB::raw('                    
                SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
            
            ->where('inscrits.status', 1)
            ->where('user_id', Auth::user()->id)
            ->get();
        return response()->json($repas);
        }
    }

    public function char_parfinancement(){
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                ->join('menus', 'menus.id', '=', 'repas.menu_id')
                ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select('nom_fin', DB::raw('                    
                    SUM(effect_gar) as "gar",
                    SUM(effect_fil) as "fil"'))
                ->groupBy('financements.nom_fin')
                
                ->where('inscrits.status', 1)
                
                ->get();
            return response()->json($repas);
        }else {
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
            ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
            ->join('menus', 'menus.id', '=', 'repas.menu_id')
            ->join('users', 'users.id', '=', 'repas.user_id')
            ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
            ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
            ->join('villages', 'villages.id', '=', 'ecoles.village_id')
            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
            ->select('nom_fin', DB::raw('                   
                SUM(effect_gar) as "gar",
                SUM(effect_fil) as "fil"'))
            ->groupBy('financements.nom_fin')
            
            ->where('inscrits.status', 1)
            
            ->where('user_id', Auth::user()->id)
            ->get();
        return response()->json($repas);
        }
    }


    public function char_parregion(){
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                ->join('menus', 'menus.id', '=', 'repas.menu_id')
                ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select('nom_reg', DB::raw('                    
                    SUM(effect_gar) as "gar",
                    SUM(effect_fil) as "fil"'))
                ->groupBy('regions.nom_reg')
                ->where('inscrits.status', 1)
                
                ->get();
            return response()->json($repas);
        }else {
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
 ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
 ->join('menus', 'menus.id', '=', 'repas.menu_id')
            ->join('users', 'users.id', '=', 'repas.user_id')
            ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
            ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
            ->join('villages', 'villages.id', '=', 'ecoles.village_id')
            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
            ->select('nom_reg', DB::raw('                    
                SUM(effect_gar) as "gar",
                SUM(effect_fil) as "fil"'))
            ->groupBy('regions.nom_reg')
            
            ->where('inscrits.status', 1)
            
            ->where('user_id', Auth::user()->id)
            ->get();
        return response()->json($repas);
        }
    }

    public function char_parfinancement_date(){
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                ->join('menus', 'menus.id', '=', 'repas.menu_id')
                ->join('users', 'users.id', '=', 'repas.user_id')
                ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select('nom_fin', DB::raw('                    
                    SUM(effect_gar) as "gar",
                    SUM(effect_fil) as "fil",
                    YEAR(repas.date_rep) as "year"'))
                ->groupBy('nom_fin', 'year')
                ->where('inscrits.status', 1)
                
                
                ->get();
            return response()->json($repas);
        }else {
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
            ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
            ->join('menus', 'menus.id', '=', 'repas.menu_id')
            ->join('users', 'users.id', '=', 'repas.user_id')
            ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
            ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
            ->join('villages', 'villages.id', '=', 'ecoles.village_id')
            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
            ->select('nom_fin', DB::raw('                    
                SUM(effect_gar) as "gar",
                SUM(effect_fil) as "fil",
                YEAR(repas.date_rep) as "year"'))
            ->groupBy('nom_fin', 'year')
            
            ->where('inscrits.status', 1)
            
            ->where('user_id', Auth::user()->id)
            ->get();
        return response()->json($repas);
        }
    }

    /**
     * Show the form for creating a new repas.
     *
     * @return \Illuminate\View\View
     */
    public function format_charger()
    {
        $regions = Region::all();
        return view('repas.format_charger', compact('regions'));
    }

    public function format_telecharger(Request $request)
    {
        $region_id = $request->region_id;
        $prefecture_id = $request->prefecture_id;
        $commune_id = $request->commune_id;
        $canton_id = $request->canton_id;

        return Excel::download(new InscritsExport($region_id, $prefecture_id, $commune_id, $canton_id), 'liste_cantines.xlsx');
    }

}

    