<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prefecture;
use App\Models\Region;
use Illuminate\Http\Request;
use Exception;

class PrefecturesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:prefecture-create|prefecture-edit|prefecture-show|prefecture-destroy', ['only' => ['index']]);
        $this->middleware('permission:prefecture-index', ['only' => ['index']]);
        $this->middleware('permission:prefecture-create', ['only' => ['create','store']]);
        $this->middleware('permission:prefecture-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:prefecture-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:prefecture-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the prefectures.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $regions = Region::all();
        return view('prefectures.index', compact('regions'));
    }

    public function fetch(){
        $prefectures = Prefecture::join('regions', 'regions.id', '=', 'prefectures.region_id' )
                    ->select('prefectures.id', 'prefectures.nom_pref', 'regions.nom_reg')
                    ->orderByDesc('prefectures.created_at')
                    ->get();
        return response()->json($prefectures);
    }

    /**
     * Show the form for creating a new prefecture.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $regions = Region::all();
        return view('regions.create', compact('regions'));
    }

    /**
     * Store a new prefecture in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $data['nom_pref'] = mb_strtoupper($request->nom_pref, 'UTF-8');

            Prefecture::create($data);

            return redirect()->route('prefectures.index')
            ->with('success_message', 'Préfecture a été ajoutée avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    /**
     * Display the specified prefecture.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $prefecture = Prefecture::with('region')->findOrFail($id);

        return response()->json($prefecture);
    }

    /**
     * Show the form for editing the specified prefecture.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Prefecture $prefecture)
    {
        return response()->json($prefecture);
    }

    /**
     * Update the specified prefecture in the storage.
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
            
            $prefecture = Prefecture::findOrFail($request->id);
            
            $data['nom_pref'] = mb_strtoupper($request->nom_pref, 'UTF-8');

            $prefecture->update($data);
            return redirect()->route('prefectures.index')
                ->with('success_message', 'Préfecture à été modifiée avec succès.');
            } catch (Exception $exception) {
                return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
            }        
    }

    /**
     * Remove the specified prefecture from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Prefecture $prefecture)
    {
        try {
            $prefecture->delete();

            return redirect()->route('prefectures.index')
                ->with('success_message', 'Préfecture a été supprimée avec succès.');
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
            'region_id' => 'required',
            'nom_pref' => 'required|string|min:0|max:150', 
        ];
        $data = $request->validate($rules);
        return $data;
    }

    public function prefecture($id){
        $prefectures = Prefecture::where('region_id', $id)
                        ->orderBy('prefectures.nom_pref', 'ASC')
                            ->get();
        return response()->json($prefectures);
    }

}
