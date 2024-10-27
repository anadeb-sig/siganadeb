<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use App\Models\Region;
use App\Models\Prefecture;
use Illuminate\Http\Request;
use Exception;

class CommunesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:commune-create|commune-edit|commune-show|commune-destroy', ['only' => ['index']]);
        $this->middleware('permission:commune-index', ['only' => ['index']]);
        $this->middleware('permission:commune-create', ['only' => ['create','store']]);
        $this->middleware('permission:commune-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:commune-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:commune-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the communes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $regions = Region::all();
        return view('communes.index', compact('regions'));
    }

    public function fetch(){
        $communes = Commune::join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id' )
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id' )
                    ->select('communes.id', 'prefectures.nom_pref', 'regions.nom_reg', 'communes.nom_comm')
                    ->orderByDesc('communes.created_at')
                    ->get();
        return response()->json($communes);
    }

    /**
     * Show the form for creating a new commune.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $regions = Region::all();
        
        return view('communes.create', compact('regions'));
    }

    /**
     * Store a new commune in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);

            $data['nom_comm'] = mb_strtoupper($request->nom_comm, 'UTF-8');
            
            Commune::create($data);

            return redirect()->route('communes.index')
            ->with('success_message', 'Commune a été ajoutée avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    /**
     * Display the specified commune.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $commune = Commune::join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id' )
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id' )
                        ->findOrFail($id);
        return response()->json($commune);
    }

    /**
     * Show the form for editing the specified commune.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $commune = Commune::join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id' )
        ->join('regions', 'regions.id', '=', 'prefectures.region_id' )
        ->findOrFail($id);
        return response()->json($commune);
    }

    /**
     * Update the specified commune in the storage.
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
            $commune = Commune::findOrFail($request->id);

            $data['nom_comm'] = mb_strtoupper($request->nom_comm, 'UTF-8');
            
            $commune->update($data);
            return redirect()->route('communes.index')
                ->with('success_message', 'Commune à été modifiée avec succès.');
            } catch (Exception $exception) {
                return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
            }        
    }

    /**
     * Remove the specified commune from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Commune $commune)
    {
        try {
            $commune->delete();
            return redirect()->route('communes.index')
            ->with('success_message', 'Commune a été supprimée avec succès.');
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
            'prefecture_id' => 'required',
            'nom_comm' => 'required|string|min:0|max:150', 
        ];
        $data = $request->validate($rules);
        return $data;
    }

    public function commune($id){
        $communes = Commune::where('prefecture_id', $id)
                        ->orderBy('communes.nom_comm', 'ASC')
                            ->get();
        return view('communes.commune', compact('communes'));
    }

    public function get_options($id)
    {
        $prefecture = Prefecture::where('region_id', $id)
                        ->orderBy('prefectures.nom_pref', 'ASC')
                        ->get();
        return response()->json($prefecture);
    }


    public function communeRegion($id){
        $commune = Commune::join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id' )
                            ->join('regions', 'regions.id', '=', 'prefectures.region_id' )
                            ->select('communes.nom_comm', 'communes.id')
                            ->where('regions.id', $id)
                            ->orderBy('communes.nom_comm', 'ASC')
                            ->get();
        return response()->json($commune);
    }

}
