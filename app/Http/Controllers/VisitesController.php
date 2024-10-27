<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Visite;
use App\Models\Region;
use Illuminate\Http\Request;
use Exception;
use Auth;

class VisitesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:visite-create|visite-edit|visite-show|visite-destroy', ['only' => ['index']]);
        $this->middleware('permission:visite-index', ['only' => ['index']]);
        $this->middleware('permission:visite-create', ['only' => ['create','store']]);
        $this->middleware('permission:visite-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:visite-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:visite-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the visites.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $regions = Region::all();
        return view('visites.index', compact('regions'));
    }

    public function fetch(){
        $visites = Visite::join('ecoles', 'ecoles.id', '=', 'visites.ecole_id')
                            ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                            ->select("nom_reg", "nom_cant", "nom_ecl", "titre", "objet","date_visite", "contact", "visites.id")
                            ->orderByDesc('visites.created_at')
                            ->get();
        return response()->json($visites);
    }

    /**
     * Show the form for creating a new visite.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $regions = Region::all();
        return view('visites.create', compact('regions'));
    }

    /**
     * Store a new visite in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $data['titre'] = mb_strtoupper($request->titre, 'UTF-8');
            $data['objet'] = mb_strtoupper($request->objet, 'UTF-8');
            $data['constat'] = mb_strtoupper($request->constat, 'UTF-8');
            $data['niveau_exe'] = mb_strtoupper($request->niveau_exe, 'UTF-8');
            $data['recommandation'] = mb_strtoupper($request->recommandation, 'UTF-8');
            $data['ecole_id'] = $request->ecole_id;
            $data['user_id'] = Auth::user()->id;

            Visite::create($data);

            return redirect()->route('visites.index')
                ->with('success_message', 'La visite a été ajoutée avec succès.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.']);
        }
    }

    /**
     * Display the specified visite.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $visite = Visite::join('ecoles', 'ecoles.id', '=', 'visites.ecole_id')
                        ->join('users', 'users.id', '=', 'visites.user_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->findOrFail($id);

        return response()->json($visite);
    }

    /**
     * Show the form for editing the specified visite.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $visite = Visite::findOrFail($id);
        return response()->json($visite);
    }

    /**
     * Update the specified visite in the storage.
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
            
            $visite = Visite::findOrFail($request->id);

            $data['titre'] = mb_strtoupper($request->titre, 'UTF-8');
            $data['objet'] = mb_strtoupper($request->objet, 'UTF-8');
            $data['constat'] = mb_strtoupper($request->constat, 'UTF-8');
            $data['niveau_exe'] = mb_strtoupper($request->niveau_exe, 'UTF-8');
            $data['recommandation'] = mb_strtoupper($request->recommandation, 'UTF-8');
            $data['ecole_id'] = $request->ecole_id;
            $data['user_id'] = Auth::user()->id;

            $visite->update($data);

            return redirect()->route('visites.index')
                ->with('success_message', 'La visiate a été modifiée avec succès..');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.']);
        }        
    }

    /**
     * Remove the specified visite from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Visite $visite)
    {
        try {

            $visite->delete();

            return redirect()->route('visites.index')
                ->with('success_message', 'La visite a été supprimée avec succès..');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.']);
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
            'titre' => 'required|string|min:0|max:150',
            'objet' => 'required|string|min:0|max:150',
            'constat' => 'required|',
            'ecole_id' => 'required|',
            'recommandation' => 'nullable',
            'date_visite' => 'nullable|date',
            'niveau_exe' => 'required|',
            'contact' => 'required|integer|digits:8', 
        ];

        
        $data = $request->validate($rules);




        return $data;
    }

}
