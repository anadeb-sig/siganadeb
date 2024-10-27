<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Exception;
use DB;
class RegionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:region-create|region-edit|region-show|region-destroy', ['only' => ['index']]);
        $this->middleware('permission:region-index', ['only' => ['index']]);
        $this->middleware('permission:region-create', ['only' => ['create','store']]);
        $this->middleware('permission:region-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:region-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:region-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:region-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the regions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('regions.index');
    }

    public function fetch(){
        $regions = Region::orderBy('nom_reg', 'ASC')
                    ->get();
                    //orderByDesc('created_at')
        return response()->json($regions);
    }

    /**
     * Show the form for creating a new region.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('regions.create');
    }

    /**
     * Store a new region in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $data['nom_reg'] = mb_strtoupper($request->nom_reg, 'UTF-8');
            
            Region::create($data);

            return redirect()->route('regions.index')
            ->with('success_message', 'Région a été ajoutée avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    /**
     * Display the specified region.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $region = Region::findOrFail($id);

        return response()->json($region);
    }

    /**
     * Show the form for editing the specified region.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $region = Region::findOrFail($id);
        return response()->json($region);
    }

    /**
     * Update the specified region in the storage.
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
            
            $region = Region::findOrFail($request->id);

            $data['nom_reg'] = mb_strtoupper($request->nom_reg, 'UTF-8');

            $region->update($data);

            return redirect()->route('regions.index')
            ->with('success_message', 'Région a été modifiée avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }       
    }

    /**
     * Remove the specified region from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Region $region)
    {
        try {
            $region->delete();
            return redirect()->route('regions.index')
            ->with('success_message', 'Région a été supprimée avec succès.');
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
                'nom_reg' => 'required|string|min:0|max:150', 
        ];
        $data = $request->validate($rules);
        return $data;
    }

    public function autorecherche_reg(Request $request)
    {
        
        $term = $request->input('term');
        
        // Recherche dans la première table
        $table1Results = DB::table('regions')
            ->select('id', 'nom_reg')
            ->where('nom_reg', 'LIKE', '%' . $term . '%')
            ->orderBy('nom_reg', 'ASC')
            ->get();
        
        return response()->json($table1Results);
    }

    public function autorecherche_pref(Request $request)
    {
        
        $term = $request->input('term');
        
        // Recherche dans la première table
        $table1Results = DB::table('prefectures')
            ->select('id', 'nom_pref')
            ->where('nom_pref', 'LIKE', '%' . $term . '%')
            ->orderBy('nom_pref', 'ASC')
            ->get();
        
        return response()->json($table1Results);
    }

    public function autorecherche_comm(Request $request)
    {
        $term = $request->input('term');
        
        // Recherche dans la première table
        $table1Results = DB::table('communes')
            ->select('id', 'nom_comm')
            ->where('nom_comm', 'LIKE', '%' . $term . '%')
            ->orderBy('nom_comm', 'ASC')
            ->get();
        
        return response()->json($table1Results);
    }

    public function autorecherche_cant(Request $request)
    {
        
        $term = $request->input('term');
        
        // Recherche dans la première table
        $table1Results = DB::table('cantons')
            ->select('id', 'nom_cant')
            ->where('nom_cant', 'LIKE', '%' . $term . '%')
            ->orderBy('nom_cant', 'ASC')
            ->get();
        
        return response()->json($table1Results);
    }

    public function autorecherche_vill(Request $request)
    {
        
        $term = $request->input('term');
        
        // Recherche dans la première table
        $table1Results = DB::table('villages')
            ->join('cantons', 'villages.canton_id', '=', 'cantons.id')
            ->select('nom_vill')
            ->distinct()
            ->where('nom_vill', 'LIKE', '%' . $term . '%')
            ->orderBy('nom_vill', 'ASC')
            ->get();
        
        return response()->json($table1Results);
    }

    public function autocomplete_phoneMenage(Request $request)
    {
        
        $term = $request->input('term');
        
        // Recherche dans la première table
        $table1Results = DB::table('menages')
            ->select('phone_member1')
            ->distinct()
            ->where('phone_member1', 'LIKE', '%' . $term . '%')
            ->where('phone_member1', '!=', '0')
            ->orderBy('phone_member1', 'ASC')
            ->get();
        
        return response()->json($table1Results);
    }

    public function autorecherche_ecl(Request $request)
    {
        
        $term = $request->input('term');
        
        // Recherche dans la première table
        $table1Results = DB::table('ecoles')
            ->select('id', 'nom_ecl')
            ->where('nom_ecl', 'LIKE', '%' . $term . '%')
            ->orderBy('ecoles.nom_ecl', 'ASC')
            ->get();
        
        return response()->json($table1Results);
    }

    public function autorecherche_fin(Request $request)
    {
        $term = $request->input('term');
        
        // Recherche dans la première table
        $table1Results = DB::table('financements')
            ->select('id', 'nom_fin')
            ->where('nom_fin', 'LIKE', '%' . $term . '%')
            ->orderBy('nom_fin', 'ASC')
            ->get();
        
        return response()->json($table1Results);
    }

}
