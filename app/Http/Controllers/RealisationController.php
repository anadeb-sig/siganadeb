<?php

namespace App\Http\Controllers;

use App\Models\Realisation;
use App\Models\Region;
use Illuminate\Http\Request;
use DB;

class RealisationController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('realisations.index', compact('regions'));
    }

    public function fetch(Request $request){
        $perPage = 30;
        $nom_reg = $request->nom_reg;
        $nom_comm = $request->nom_comm;
        $nom_site = $request->nom_site;
        $nom_ouvrage = $request->nom_ouvrage;
        
        $realisations = realisation::join('ouvrages', 'realisations.ouvrage_id', '=', 'ouvrages.id' )
                            ->join('sites', 'ouvrages.site_id', '=', 'sites.id' )
                            ->join('villages', 'villages.id', '=', 'sites.village_id' )
                            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                            ->select('nom_ouvrage','qte', 'realisations.id', 'prix_unit','estimation_id','ouvrage_id')
                            ->when($nom_reg, function ($query, $nom_reg) {
                                return $query->where('regions.nom_reg', 'like', "%$nom_reg%");
                            })->when($nom_comm, function ($query, $nom_comm) {
                                return $query->where('communes.nom_comm', 'like', "%$nom_comm%");
                            })->when($nom_site, function ($query, $nom_site) {
                                return $query->where('sites.nom_site', 'like', "%$nom_site%");
                            })->when($nom_ouvrage, function ($query, $nom_ouvrage) {
                                return $query->where('ouvrages.nom_ouvrage', 'like', "%$nom_ouvrage%");
                            })->orderByDesc('realisations.created_at')
                    ->paginate($perPage);
        return response()->json($realisations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::all();
        return view('realisations.create', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $data = $this->getData($request);

            // Vérifier si l'ouvrage existe déjà dans les realisations
            $estExists = realisation::where('ouvrage_id', $request->ouvrage_id)
            ->where('estimation_id', $request->estimation_id)
            ->exists();

            if($estExists){
                return redirect()->route('realisations.index')
                ->with('error_message', __('Enregistrement est déja disponible'));
            }

            realisation::create($data);
            return redirect()->route('realisations.index')
                ->with('success_message', __('Enregistrement effectué avec succès'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['error_message' => trans('realisations.unexpected_error')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\realisation  $realisation
     * @return \Illuminate\Http\Response
     */
    public function show(realisation $realisation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\realisation  $realisation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $realisation = realisation::findOrFail($id);
        return response()->json($realisation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\realisation  $realisation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $data = $this->getData($request);
            
            $realisation = Realisation::findOrFail($request->id);
            $realisation->update($data);
            return redirect()->route('realisations.index')
                ->with('success_message', __('Modification effectuée avec succès'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['error_message' => trans('realisations.unexpected_error')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\realisation  $realisation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $realisation = Realisation::findOrFail($id);
            $realisation->delete();

            return redirect()->route('realisations.index')
                ->with('success_message', __('Suppression effectuée avec succès'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['error_message' => trans('entreprises.unexpected_error')]);
        }
    }

    protected function getData(Request $request)
    {
        $rules = [
            'ouvrage_id' => 'required',
            'qte' => 'required',
            'prix_unit' => 'required',
            'estimation_id' => 'required'
        ];

        $data = $request->validate($rules);

        return $data;
    }

    public function par_ouvrage($id){
        $designs = DB::table('estimations as e')
            ->join('ouvrages as o', 'e.ouvrage_id', '=', 'o.id')
            ->select('e.design', 'e.id')
            ->whereNotIn('e.id', function ($query) {
                $query->select('estimation_id')
                    ->from('realisations');
            })
            ->where('e.ouvrage_id', '=', $id)
            ->get();

        return response()->json($designs);
    }
}
