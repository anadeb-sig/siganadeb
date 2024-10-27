<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use App\Models\Financement;
use App\Models\Village;
use App\Models\Region;
use App\Models\Repas;
use App\Models\Classe;
use App\Models\Inscrit;
use Illuminate\Http\Request;
use Exception;
use Validator;
use DB;
class EcolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:ecole-create|ecole-edit|ecole-show|ecole-delete', ['only' => ['index']]);
        $this->middleware('permission:ecole-index', ['only' => ['index']]);
        $this->middleware('permission:ecole-create', ['only' => ['create','store']]);
        $this->middleware('permission:ecole-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:ecole-delete', ['only' => ['destroy']]);
        $this->middleware('permission:ecole-show', ['only' => ['show']]);
        $this->middleware('permission:liste-ecole-village', ['only' => ['ecole_village']]);
        $this->middleware('permission:liste-ecole-canton', ['only' => ['ecole_canton']]);
        $this->middleware('permission:liste-ecole-commune', ['only' => ['ecole_commune']]);
        $this->middleware('permission:liste-ecole-prefecture', ['only' => ['ecole_prefecture']]);
        $this->middleware('permission:liste-ecole-region', ['only' => ['ecole_region']]);
    }

    /**
     * Display a listing of the ecoles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $ecoles = Ecole::with('village','financement')->paginate(25);

        $Villages = Village::pluck('nom_vill','id')->all();
        $Financements = Financement::pluck('nom_fin','id')->all();

        $regions = Region::all();

        return view('ecoles.index', compact('ecoles', 'Villages', 'Financements', 'regions'));
    }

    public function fetch(){
        $classes = Ecole::join('financements', 'financements.id', '=', 'ecoles.financement_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('regions.nom_reg','cantons.nom_cant', 'cantons.id as canton', 'villages.nom_vill', 'financements.nom_fin', 'ecoles.nom_ecl', 'ecoles.id')
                    ->orderByDesc('ecoles.created_at')
                    ->get();

        return response()->json($classes);
    }

    /**
     * Show the form for creating a new 
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Villages = Village::pluck('nom_vill','id')->all();
        $Financements = Financement::pluck('id','id')->all();
        $regions = Region::all();
        
        return view('ecoles.create', compact('Villages','Financements', 'regions'));
    }

    /**
     * Store a new ecole in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        
        $data['nom_ecl'] = mb_strtoupper($request->nom_ecl, 'UTF-8');

        $concat = $request->village_id.''.mb_strtoupper($request->nom_ecl, 'UTF-8');
        
        $verif = new Ecole();

        $ver = $verif->verif($concat);        

        if($ver === 0){
            $id = DB::table('ecoles')->insertGetId($data);

            $dataaa = [
                "ecole_id" => $id
            ];

            for ($i=0; $i < 2; $i++) {

                if ($i==0){
                    $dataaa["nom_cla"] = "Pré_scolaire";
                }else {
                    $dataaa["nom_cla"] = "Primaire";
                }

                Classe::create($dataaa);
            }

            return redirect()->route('ecoles.index')
            ->with('success_message', 'L\'école a été ajoutée avec succès.');
        }else {
            return back()->with('error_message', 'La cantine que vous tentez d\'ajouter existe déjà!');
        }
    }

    /**
     * Display the specified 
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $ecole = Ecole::join('financements', 'financements.id', '=', 'ecoles.financement_id')
        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->select('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill','financements.nom_fin', 'ecoles.nom_ecl', 'ecoles.id')
        ->findOrFail($id);

        return response()->json($ecole);
    }

    /**
     * Show the form for editing the specified 
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Ecole $ecole)
    {
        return response()->json($ecole);
    }

    /**
     * Update the specified ecole in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        try {
            $data = $this->getData($request);

            $ecole = Ecole::findOrFail($request->id);
            
            $data['nom_ecl'] = mb_strtoupper($request->nom_ecl, 'UTF-8');

            $ecole->update($data);

            return redirect()->route('ecoles.index')
            ->with('success_message', 'L’école a été modifiée avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }       
    }

    /**
     * Remove the specified ecole from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Ecole $ecole)
    {
        try {
            
            $ecole->delete();   

            return redirect()->route('ecoles.index')
            ->with('success_message', 'La cantine a été supprimée avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Pas possible de supprimer cette donnée, elle est utilisée par d\'autres entités!');
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
            'nom_ecl' => 'required|string',
            'financement_id' => 'required',
            'village_id' => 'required|numeric',
        ];
        $data = $request->validate($rules);
        return $data;
    }

    public function updateStatus($id, $status)
    {
        // Validation
        $validate = Validator::make([
            'id'   => $id,
            'status'    => $status
        ], [
            'id'   =>  'required|exists:ecoles,id',
            'status'    =>  'required|in:0,1',
        ]);

        $rep = Repas::join('classes', 'classes.id', '=', 'repas.classe_id')
                ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                ->where('ecoles.id', $id)
                ->count();

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('ecoles.index')->with('error', $validate->errors()->first());
        }elseif ($rep >0) {
            return redirect()->route('ecoles.index')->with('error_message','Pas possible de désactiver cette cantine!');
        }else{

            DB::beginTransaction();

            // Update Status
            Ecole::whereId($id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('ecoles.index')->with('success_message','Statut de l\'école modifié avec succès!');
        }
    }

    public function ecole($id){
        $ecoles = Ecole::where('village_id', $id)
                            ->get();
        return view('ecoles.ecole', compact('ecoles'));
    }


    public function get_options($id)
    {
        $ecoles = Ecole::join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('ecoles.nom_ecl', 'ecoles.id')
                        ->where('commune_id', $id)
                        ->orderBy('ecoles.nom_ecl', 'ASC')
                        ->get();
        return response()->json($ecoles);
    }

    public function ecole_village(){
        return view('ecole_localite.par_village');
    }

    public function ecole_canton(){
        return view('ecole_localite.par_canton');
    }

    public function ecole_commune(){
        return view('ecole_localite.par_commune');
    }

    public function ecole_prefecture(){
        return view('ecole_localite.par_prefecture');
    }

    public function ecole_region(){
        return view('ecole_localite.par_region');
    }

    public function liste_ecole_village($village_id){
        $ecoles = Inscrit::join('classes', 'inscrits.classe_id', '=', 'classes.id')
                    ->join('ecoles', 'classes.ecole_id', '=', 'ecoles.id')
                    ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->where('village_id', $village_id)
                    ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'nom_ecl', 'financements.nom_fin', 'inscrits.status',
                        DB::raw('SUM(IF(nom_cla = "Pré_scolaire", nbr_gr,0)) as "nbr_gr_pre",
                            SUM(IF(nom_cla = "Primaire", nbr_gr,0)) as "nbr_gr_pri"'))
                    ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'financements.nom_fin', 'inscrits.status')
                    ->get();
                    return response()->json($ecoles);
    }
    
    public function liste_ecole_canton($canton_id){
        $ecoles = Inscrit::join('classes', 'inscrits.classe_id', '=', 'classes.id')
                    ->join('ecoles', 'classes.ecole_id', '=', 'ecoles.id')
                    ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->where('cantons.id', $canton_id)
                    ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'nom_ecl', 'financements.nom_fin', 'inscrits.status',
                        DB::raw('SUM(IF(nom_cla = "Pré_scolaire", nbr_gr,0)) as "nbr_gr_pre",
                            SUM(IF(nom_cla = "Primaire", nbr_gr,0)) as "nbr_gr_pri"'))
                    ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'financements.nom_fin', 'inscrits.status')
                    ->orderBy('ecoles.nom_ecl', 'ASC')
                    ->get();
        return response()->json($ecoles);
    }


    public function liste_ecole_commune($commune_id){
        $ecoles = Inscrit::join('classes', 'inscrits.classe_id', '=', 'classes.id')
                    ->join('ecoles', 'classes.ecole_id', '=', 'ecoles.id')
                    ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->where('communes.id', $commune_id)
                    ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'nom_ecl', 'financements.nom_fin', 'inscrits.status',
                        DB::raw('SUM(IF(nom_cla = "Pré_scolaire", nbr_gr,0)) as "nbr_gr_pre",
                            SUM(IF(nom_cla = "Primaire", nbr_gr,0)) as "nbr_gr_pri"'))
                    ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'financements.nom_fin', 'inscrits.status')
                    ->orderBy('cantons.nom_cant', 'ASC')
                    ->get();
        return response()->json($ecoles);
    }

    public function liste_ecole_prefecture($prefecture_id){
        $ecoles = Inscrit::join('classes', 'inscrits.classe_id', '=', 'classes.id')
                    ->join('ecoles', 'classes.ecole_id', '=', 'ecoles.id')
                    ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->where('prefectures.id', $prefecture_id)
                    ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'nom_ecl', 'financements.nom_fin', 'inscrits.status',
                        DB::raw('SUM(IF(nom_cla = "Pré_scolaire", nbr_gr,0)) as "nbr_gr_pre",
                            SUM(IF(nom_cla = "Primaire", nbr_gr,0)) as "nbr_gr_pri"'))
                    ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'financements.nom_fin', 'inscrits.status')
                    ->orderBy('communes.nom_comm', 'ASC')
                    ->get();
        return response()->json($ecoles);
    }

    public function liste_ecole_region($region_id){
        $ecoles = Inscrit::join('classes', 'inscrits.classe_id', '=', 'classes.id')
                    ->join('ecoles', 'classes.ecole_id', '=', 'ecoles.id')
                    ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('nom_reg', 'nom_pref', 'nom_comm', 'nom_cant', 'nom_vill', 'nom_ecl', 'financements.nom_fin', 'inscrits.status',
                        DB::raw('SUM(IF(nom_cla = "Pré_scolaire", nbr_gr,0)) as "nbr_gr_pre",
                            SUM(IF(nom_cla = "Primaire", nbr_gr,0)) as "nbr_gr_pri"'))
                    ->where('region_id', $region_id)
                    ->groupBy('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'financements.nom_fin', 'inscrits.status')
                    ->orderBy('prefectures.nom_pref', 'ASC')
                    ->get();
        return response()->json($ecoles);
    }

}
