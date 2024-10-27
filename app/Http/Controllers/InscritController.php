<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Classe;
use App\Models\Inscrit;
use App\Models\Region;
use App\Models\Repas;
use Exception;
use DB;
use Carbon\Carbon;
use Validator;

class InscritController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:inscrit-create|inscrit-edit|inscrit-show|inscrit-destroy', ['only' => ['index']]);
        $this->middleware('permission:inscrit-index', ['only' => ['index']]);
        $this->middleware('permission:inscrit-create', ['only' => ['create','store']]);
        $this->middleware('permission:inscrit-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:inscrit-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:inscrit-show', ['only' => ['show']]);
    }

    public function index()
    {
        $regions = Region::all();
        return view('inscrits.index', compact('regions'));
    }

    public function fetch(){
        $inscrits = Inscrit::join('classes', 'classes.id', '=', 'inscrits.classe_id')
                    ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('regions.nom_reg', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'inscrits.id', 'classes.nom_cla', 'inscrits.effec_fil', 'inscrits.effec_gar', 'inscrits.status', DB::raw('YEAR(inscrits.date_debut) as year1'), DB::raw('YEAR(inscrits.date_fin) as year2'))
                    //->where('inscrits.status', '=',1)
                    ->where(function ($query) {
                        $query->where('inscrits.effec_gar', '>', 0)
                              ->orWhere('inscrits.effec_fil', '>', 0);
                    })
                    ->orderByDesc('inscrits.created_at')
                    ->get();
        return response()->json($inscrits);
    }

    public function index_zero()
    {
        $regions = Region::all();
        return view('inscrits.index_zero', compact('regions'));
    }

    public function ecoleazero(){
        $inscrits = Inscrit::join('classes', 'classes.id', '=', 'inscrits.classe_id')
                    ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('regions.nom_reg', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'inscrits.id', 'classes.nom_cla', 'inscrits.effec_fil', 'inscrits.effec_gar')
                    ->where(function ($query) {
                        $query->where('inscrits.effec_gar', '=', 0)
                              ->Where('inscrits.effec_fil', '=', 0);
                    })
                    ->orderByDesc('inscrits.created_at')
                    ->get();
        return response()->json($inscrits);
    }

    /**
     * Show the form for creating a new classe.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $regions = Region::all();
        return view('inscrits.create', compact('regions'));
    }

    /**
     * Store a new classe in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            $nom_cla = $request->input('nom_cla');

            $rowCount = 0;

            $classe_id = new Inscrit();
            $classe_id = $classe_id->classe_id($request->ecole_id);

            //dd($classe_id);

            $year1 = Carbon::parse($request->date_debut)->year;
            $year2 = Carbon::parse($request->date_fin)->year;

            foreach ($classe_id as $key => $value) {

                

                if ($value->nom_cla == $nom_cla[$key]) {

                    $concat = $year1 ."". $year2 ."". $value->id;

                    $verif = new Inscrit();
                    $ver = $verif->verif($concat);

                    $rowCount = 0;

                    if($ver == 0){
                        $data['classe_id'] = $value->id;
                        $data['effec_gar'] = $request->effec_gar[$key];
                        $data['effec_fil'] = $request->effec_fil[$key];
                        $data['nbr_gr'] = $request->nbr_gr[$key];
                        $data['date_debut'] = $request->date_debut;
                        $data['date_fin'] = $request->date_fin;
                        $data['nbr_mam'] = $request->nbr_mam;
                        $data['nbr_ensg'] = $request->nbr_ensg;
                        $data['status'] = $request->status[$key];

                        $insertedId = DB::table('inscrits')->insertGetId($data);

                        if ($insertedId) {
                            $rowCount++;
                        }
                    }else{
                        continue;
                    }
                    
                }
            }

            if($rowCount > 0){
                return redirect()->route('inscrits.index')
                    ->with('success_message', 'L’enseignement a été ajouté avec succès.');
            }else {
                return redirect()->route('inscrits.index')
                    ->with('error_message', 'Ecole déjà créée pour le compte de l\'année!');
            }
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    /**
     * Display the specified classe.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $classe = Inscrit::join('classes', 'classes.id', '=', 'inscrits.classe_id')
        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->select('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'inscrits.id', 'classes.nom_cla', 'inscrits.effec_fil', 'inscrits.effec_gar', 'inscrits.nbr_gr', 'inscrits.nbr_ensg', 'inscrits.nbr_mam')
        ->findOrFail($id);
        return response()->json($classe);
    }

    /**
     * Show the form for editing the specified classe.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Inscrit $inscrit)
    {
        return response()->json($inscrit);
    }

    /**
     * Update the specified classe in the storage.
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

            $inscrit = Inscrit::findOrFail($request->id);

            $inscrit->update($data);

            return redirect()->route('inscrits.index')
                ->with('success_message', 'L’enseignement a été modifié avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    /**
     * Remove the specified classe from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Inscrit $inscrit)
    {
        try {
            $inscrit->delete();

            return redirect()->route('inscrits.index')
                ->with('success_message', 'L’enseignement a été supprimé avec succès.');
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
            'effec_gar' => 'required|numeric',
            'effec_fil' => 'required|numeric',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'status' => 'required',
            'classe_id' => 'required',
            'nbr_mam' => 'required|numeric',
            'nbr_gr' => 'required|numeric',
            'nbr_ensg' => 'required|numeric'
        ];
        $data = $request->validate($rules);
        return $data;
    }

    public function classe($id){
        $classes = Classe::where('ecole_id', $id)
                            ->get();
        return view('classes.classe', compact('classes'));
    }

    public function get_options($id)
    {
        $inscrit = Inscrit::join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->select('inscrits.id', 'classes.nom_cla')
                        ->where('ecole_id', $id)
                        ->where('inscrits.status', 1)
                        ->get();
        return response()->json($inscrit);
    }

    

    public function classeEcole($id)
    {
        $classes = Classe::join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->select('classes.id', 'classes.nom_cla')
                        ->where('ecole_id', $id)
                        ->get();
        return response()->json($classes);
    }


    public function updateStatus($id, $status)
    {
        // Validation
        $validate = Validator::make([
            'id'   => $id,
            'status'    => $status
        ], [
            'id'   =>  'required|exists:inscrits,id',
            'status'    =>  'required|in:0,1',
        ]);

        $rep = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                ->where('inscrits.id', $id)
                ->count();

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('inscrits.index')->with('error', $validate->errors()->first());
        }elseif ($rep >0) {
            return redirect()->route('inscrits.index')->with('error_message','Pas possible de désactiver cette école!');
        }else{

            DB::beginTransaction();

            // Update Status
            Inscrit::whereId($id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('inscrits.index')->with('success_message','Statut de l\'école modifié avec succès!');
        }
    }
}
