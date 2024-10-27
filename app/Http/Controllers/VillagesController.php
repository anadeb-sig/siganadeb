<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Canton;
use App\Models\Village;
use App\Models\Region;
use Illuminate\Http\Request;
use Exception;

class VillagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:village-create|village-edit|village-show|village-destroy', ['only' => ['index']]);
        $this->middleware('permission:village-index', ['only' => ['index']]);
        $this->middleware('permission:village-create', ['only' => ['create','store']]);
        $this->middleware('permission:village-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:village-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:village-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the villages.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $regions = Region::all();
        return view('villages.index', compact('regions'));
    }

    public function fetch(){
        $villages = Village::join('cantons', 'cantons.id', '=', 'villages.canton_id' )
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id' )
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id' )
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id' )
                    ->select('villages.id', 'villages.nom_vill', 'cantons.nom_cant', 'prefectures.nom_pref', 'regions.nom_reg', 'communes.nom_comm')
                    ->orderByDesc('villages.created_at')
                    ->get();
        return response()->json($villages);
    }

    /**
     * Show the form for creating a new village.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $regions = Region::all();
        
        return view('villages.create', compact('regions'));
    }

    /**
     * Store a new village in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);

            $data['nom_vill'] = mb_strtoupper($request->nom_vill, 'UTF-8');
            
            Village::create($data);

            return redirect()->route('villages.index')
            ->with('success_message', 'Village a été ajouté avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    /**
     * Display the specified village.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $village = Village::findOrFail($id);
        return response()->json($village);
    }

    /**
     * Show the form for editing the specified village.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $villages = Village::join('cantons', 'cantons.id', '=', 'villages.canton_id' )
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id' )
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id' )
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id' )
                    ->select('villages.id', 'villages.canton_id', 'villages.nom_vill', 'cantons.nom_cant', 'prefectures.nom_pref', 'regions.nom_reg', 'communes.nom_comm')
                    ->orderByDesc('villages.created_at')
                    ->findOrFail($id);
        return response()->json($villages);
    }

    /**
     * Update the specified village in the storage.
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
            
            $data['nom_vill'] = mb_strtoupper($request->nom_vill, 'UTF-8');
            
            $village = Village::findOrFail($request->id);
            $village->update($data);

            return redirect()->route('villages.index')
            ->with('success_message', 'Village à été modifié avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }          
    }

    /**
     * Remove the specified village from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Village $village)
    {
        try {
            $village->delete();

            return redirect()->route('villages.index')
            ->with('success_message', 'Village a été supprimée avec succès.');
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
            'canton_id' => 'required',
            'nom_vill' => 'required|string|min:0|max:150', 
        ];
        $data = $request->validate($rules);
        return $data;
    }

    public function village($id){
        $villages = Village::where('canton_id', $id)
                        ->orderBy('villages.nom_vill', 'ASC')
                            ->get();
        return view('villages.village', compact('villages'));
    }

    public function get_options($id)
    {
        $cantons = Canton::where('commune_id', $id)
                    ->orderBy('cantons.nom_cant', 'ASC')
                        ->get();
        return response()->json($cantons);
    }

    public function get_option($id)
    {
        $villages = Village::where('canton_id', $id)
                    ->orderBy('villages.nom_vill', 'ASC')
                        ->get();
        return response()->json($villages);
    }

    public function get_option_comm($id)
    {
        $villages = Village::join('cantons', 'cantons.id', '=', 'villages.canton_id' )
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id' )
                    ->select('villages.nom_vill', 'villages.id')
                    ->where('commune_id', $id)
                    ->orderBy('villages.nom_vill', 'ASC')
                    ->get();
        return response()->json($villages);
    }

}
