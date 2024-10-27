<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Region;
use App\Models\Inscrit;
use Illuminate\Http\Request;
use Exception;
use DB;

class ClassesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:classe-create|classe-edit|classe-show|classe-destroy', ['only' => ['index']]);
        $this->middleware('permission:classe-index', ['only' => ['index']]);
        $this->middleware('permission:classe-create', ['only' => ['create','store']]);
        $this->middleware('permission:classe-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:classe-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:classe-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the classes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $regions = Region::all();

        return view('classes.index', compact('regions'));
    }

    public function fetch(){
        $classes = Classe::join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('regions.nom_reg', 'communes.nom_comm', 'ecoles.nom_ecl', 'classes.id', 'classes.nom_cla')
                    ->orderByDesc('classes.created_at')
                    ->get();
        return response()->json($classes);
    }

    public function ecoleazero(){
        $classes = Classe::join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                    ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('regions.nom_reg', 'communes.nom_comm', 'ecoles.nom_ecl', 'classes.id', 'classes.nom_cla')
                    ->orderByDesc('classes.created_at')
                    ->get();
        return response()->json($classes);
    }

    public function index_zero()
    {
        $regions = Region::all();

        return view('classes.index_zero', compact('regions'));
    }

    /**
     * Show the form for creating a new classe.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $regions = Region::all();
        return view('classes.create', compact('regions'));
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
        $nom_cla = $request->input('nom_cla');
        $rowCount = 0;
        for ($i=0; $i < count($nom_cla) ; $i++) { 
                
            $concat = $request->ecole_id.''.$request->nom_cla[$i];
            
            $verif = new Classe();

            $ver = $verif->verif($concat);

            if($ver === 0){
                $data['ecole_id'] = $request->input('ecole_id');
                $data['nom_cla'] = $request->nom_cla[$i];
                $insertedId = DB::table('classes')->insertGetId($data);
                if ($insertedId) {
                    $rowCount++;
                }
            }else{
                continue;
            }
        }

        if($rowCount > 0){
            return redirect()->route('classes.index')
                ->with('success_message', 'L’enseignement a été ajouté avec succès.');
        }else {
            return back()->with('error_message', 'Cette école a été déjà créée!');
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
        $classe = Classe::join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->select('regions.nom_reg', 'prefectures.nom_pref', 'communes.nom_comm', 'cantons.nom_cant', 'villages.nom_vill', 'ecoles.nom_ecl', 'classes.id', 'classes.nom_cla')
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
    public function edit(Classe $classe)
    {
        return response()->json($classe);
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

            $classe = Classe::findOrFail($request->id);

            $classe->update($data);

            return redirect()->route('classes.index')
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
    public function destroy(Classe $classe)
    {
        try {
            $classe->delete();

            return redirect()->route('classes.index')
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
            'nom_cla' => 'required|string',
            'ecole_id' => 'required',
        ];
        $data = $request->validate($rules);
        return $data;
    }

    public function get_options($id)    {
        $classes = Inscrit::join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->select('inscrits.id', 'nom_cla')
                        ->where('ecole_id', $id)
                        ->get();
        return response()->json($classes);
    }




    // public function classe($id){
    //     $classes = Classe::where('ecole_id', $id)
    //                         ->get();
    //     return view('classes.classe', compact('classes'));
    // }


    // public function classeEcole($id)
    // {
    //     $classes = Classe::join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
    //                     ->select('classes.id', 'classes.nom_cla')
    //                     ->where('ecole_id', $id)
    //                     ->get();
    //     return response()->json($classes);
    // }
}
