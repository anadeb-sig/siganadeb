<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Canton;
use App\Models\Commune;
use App\Models\Region;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Exception;

class CantonsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:canton-create|canton-edit|canton-show|canton-destroy', ['only' => ['index']]);
        $this->middleware('permission:canton-index', ['only' => ['index']]);
        $this->middleware('permission:canton-create', ['only' => ['create','store']]);
        $this->middleware('permission:canton-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:canton-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:canton-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:canton-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the cantons.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $regions = Region::all();
        return view('cantons.index', compact('regions'));
    }

    public function fetch(){
        $cantons = Canton::join('communes', 'communes.id', '=', 'cantons.commune_id' )
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id' )
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id' )
                    ->select('cantons.id', 'cantons.nom_cant', 'prefectures.nom_pref', 'regions.nom_reg', 'communes.nom_comm')
                    ->orderByDesc('cantons.created_at')
                    ->get();
        return response()->json($cantons);
    }

    /**
     * Show the form for creating a new canton.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $regions = Region::all();
        
        return view('cantons.create', compact('regions'));
    }

    /**
     * Store a new canton in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);

            $data['nom_cant'] = mb_strtoupper($request->nom_cant, 'UTF-8');
            
            Canton::create($data);

            return redirect()->route('cantons.index')
            ->with('success_message', 'Canton a été ajouté avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    /**
     * Display the specified canton.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $canton = Canton::findOrFail($id);
        return response()->json($canton);
    }

    /**
     * Show the form for editing the specified canton.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $canton = Canton::join('communes', 'communes.id', '=', 'cantons.commune_id' )
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id' )
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id' )
                    ->select('cantons.id', 'cantons.nom_cant', 'prefectures.nom_pref', 'regions.nom_reg', 'communes.nom_comm', 'cantons.commune_id')
                    ->findOrFail($id);
        return response()->json($canton);
    }

    /**
     * Update the specified canton in the storage.
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
            
            $canton = Canton::findOrFail($request->id);

            $data['nom_cant'] = mb_strtoupper($request->nom_cant, 'UTF-8');
            
            $canton->update($data);

            return redirect()->route('cantons.index')
            ->with('success_message', 'Canton à été modifié avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }       
    }

    /**
     * Remove the specified canton from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Canton $canton)
    {
        try {
            $canton->delete();

            return redirect()->route('cantons.index')
            ->with('success_message', 'Canton a été supprimée avec succès.');
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
            'commune_id' => 'required',
            'nom_cant' => 'required|string|min:0|max:150', 
        ];
        $data = $request->validate($rules);
        return $data;
    }



    public function canton($id){
        $cantons = Canton::where('commune_id', $id)
                    ->orderBy('cantons.nom_cant', 'ASC')
                    ->get();
        return view('cantons.canton', compact('cantons'));
    }

    public function cantonRegions($id){
        $cantons = Canton::join('communes', 'communes.id', '=', 'cantons.commune_id' )
                            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id' )
                            ->join('regions', 'regions.id', '=', 'prefectures.region_id' )
                            ->select('cantons.nom_cant', 'cantons.id')
                            ->where('regions.id', $id)
                            ->orderBy('cantons.nom_cant', 'ASC')
                            ->get();
        return response()->json($cantons);
    }

    public function cantonCommune($id){
        $cantons = Canton::select('cantons.id', 'cantons.nom_cant')
                        ->join('communes', 'cantons.commune_id', '=', 'communes.id')
                        ->where('commune_id', $id)
                        ->orderBy('cantons.nom_cant', 'ASC')
                        ->get();
        return response()->json($cantons);
    }

    public function ecoleCanton($id){
        $cantons = Ecole::join('villages', 'villages.id', '=', 'ecoles.village_id' )
                            ->join('cantons', 'cantons.id', '=', 'villages.canton_id' )
                            ->join('communes', 'communes.id', '=', 'cantons.commune_id' )
                            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id' )
                            ->join('regions', 'regions.id', '=', 'prefectures.region_id' )
                            ->select('ecoles.nom_ecl', 'ecoles.id')
                            ->where('cantons.id', $id)
                            ->where('ecoles.status', '=',1)
                            ->orderBy('ecoles.nom_ecl', 'ASC')
                            ->get();
        return response()->json($cantons);
    }

    public function get_options($id)
    {
        $communes = Commune::where('prefecture_id', $id)
                        ->orderBy('communes.nom_comm', 'ASC')
                        ->get();
        return response()->json($communes);
    }

}
