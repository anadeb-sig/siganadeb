<?php

namespace App\Http\Controllers;

use App\Models\Estimation;
use App\Models\Region;
use App\Models\Type_realisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use DB;
use Auth;

class EstimationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::all();
        $libelles = Type_realisation::all();
        return view('estimations.index', compact('regions','libelles'));
    }

    public function fetch(Request $request){
        $perPage = 30;
        $nom_reg = $request->nom_reg;
        $nom_comm = $request->nom_comm;
        $nom_site = $request->nom_site;
        $nom_ouvrage = $request->nom_ouvrage;
        
        $estimations = Estimation::join('type_realisations', 'estimations.type_realisation_id', '=', 'type_realisations.id')
                            ->join('ouvrages', 'estimations.ouvrage_id', '=', 'ouvrages.id' )
                            ->join('sites', 'ouvrages.site_id', '=', 'sites.id' )
                            ->join('villages', 'villages.id', '=', 'sites.village_id' )
                            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                            ->select('design', 'unite','qte', 'estimations.id', 'prix_unit','type_realisation_id','ouvrage_id')
                            ->when($nom_reg, function ($query, $nom_reg) {
                                return $query->where('regions.nom_reg', 'like', "%$nom_reg%");
                            })->when($nom_comm, function ($query, $nom_comm) {
                                return $query->where('communes.nom_comm', 'like', "%$nom_comm%");
                            })->when($nom_site, function ($query, $nom_site) {
                                return $query->where('sites.nom_site', 'like', "%$nom_site%");
                            })->when($nom_ouvrage, function ($query, $nom_ouvrage) {
                                return $query->where('ouvrages.nom_ouvrage', 'like', "%$nom_ouvrage%");
                            })->orderByDesc('estimations.created_at')
                    ->paginate($perPage);
        return response()->json($estimations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::all();
        $libelles = Type_realisation::all();
        return view('estimations.create', compact('regions','libelles'));
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

            // Vérifier si l'ouvrage existe déjà dans les estimations
            $estExists = Estimation::where('ouvrage_id', $request->ouvrage_id)
            ->where('type_realisation_id', $request->type_realisation_id)
            ->exists();

            if($estExists){
                return redirect()->route('estimations.index')
                ->with('error_message', __('Enregistrement est déja disponible'));
            }

            Estimation::create($data);
            return redirect()->route('estimations.index')
                ->with('success_message', __('Enregistrement effectué avec succès'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['error_message' => trans('estimations.unexpected_error')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estimation  $estimation
     * @return \Illuminate\Http\Response
     */
    public function show(Estimation $estimation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estimation  $estimation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $estimation = Estimation::findOrFail($id);
        return response()->json($estimation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estimation  $estimation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $data = $this->getData($request);
            
            $estimation = estimation::findOrFail($request->id);
            $estimation->update($data);
            return redirect()->route('estimations.index')
                ->with('success_message', __('Modification effectuée avec succès'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['error_message' => trans('estimations.unexpected_error')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estimation  $estimation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $estimation = Estimation::findOrFail($id);
            $estimation->delete();

            return redirect()->route('estimations.index')
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
            'unite' => 'required|string',
            'design' => 'required|string',
            'qte' => 'required',
            'prix_unit' => 'required',
            'type_realisation_id' => 'required'
        ];

        $data = $request->validate($rules);

        return $data;
    }

    // Chargement de modale d'exportation du format csv
    public function telecharger()
    {
        $regions = Region::all();
        return view('estimations.telecharger_format', compact('regions'));
    }

    public function export_format(Request $request){
        $ouvrage_id = $request->ouvrage_id;

        $types = Type_realisation::orderBy('id')
                    ->get();

        $data = [];

        $data[] = ['libelle','designation','unite','qte','prix_unitaire','numero_ouvrage','numero_type'];

        foreach ($types as $value) {
            $data[] = [
                $value->lib,
                '',
                '',
                '',
                '',
                $ouvrage_id,
                $value->id
            ];
        }

        // Convertir les données en chaîne CSV
        $csvOutput = "\xEF\xBB\xBF"; // UTF-8 BOM pour les logiciels comme Excel
        foreach ($data as $row) {
            $csvOutput .= implode(";", $row) . "\n";  // Utiliser un point-virgule pour séparer les colonnes
        }

        // Retourner la réponse avec le fichier CSV
        return Response::make($csvOutput, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="format_estimation.csv"',
        ]);
    }

    public function uploadEstimations(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            // Récupération et stockage du fichier
            $file = $request->file('file');
            $filename = time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $filename);

            // Enregistrement du fichier dans une table temporaire
            $tempId = DB::table('temps')->insertGetId([
                'user_id' => Auth::id(),
                'file' => $filename,
                'path' => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Récupération du chemin complet du fichier
            $tempFile = DB::table('temps')->find($tempId);
            $filepath = storage_path('app/' . $tempFile->path);

            // Lecture et traitement du fichier CSV
            if (!file_exists($filepath)) {
                return back()->with('error_message', 'Le fichier est introuvable.');
            }

            $handle = fopen($filepath, 'r');
            if (!$handle) {
                return back()->with('error_message', 'Impossible de lire le fichier.');
            }

            $header = fgetcsv($handle, 1000, ','); // Lire l'en-tête (non utilisé dans ce cas)
            $data = [];
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data[] = $row;
            }
            fclose($handle);
            //dd($data);
            // Préparer les données pour l'insertion
            $lignes_excel = [];
            foreach ($data as $row) {

                $details = explode(";", $row[0]);
                
                // Vérifier si l'ouvrage existe déjà dans les estimations
                $estExists = Estimation::where('ouvrage_id', $details[5])
                                ->where('type_realisation_id', $details[6])
                                ->exists();
                
                if ($estExists) {
                    return back()->with('error_message', 'Données relatives à cet ouvrage déjà chargées.');
                }

                $lignes_excel[] = [
                    "design" => $details[1],
                    "unite" => $details[2],
                    "qte" => $details[3],
                    "prix_unit" => $details[4],
                    "ouvrage_id" => $details[5],
                    "type_realisation_id" => $details[6]
                ];
            }

            // Insérer les données dans la table estimations
            if (!empty($lignes_excel)) {
                DB::table('estimations')->insert($lignes_excel);

                return redirect()->route('estimations.index')
                    ->with('success_message', count($lignes_excel) . ' enregistrements chargés avec succès.');
            } else {
                return back()->with('error_message', 'Aucune donnée valide à insérer.');
            }
        } catch (\Exception $e) {
            // Gestion des erreurs
            return back()->with('error_message', 'Erreur : ' . $e->getMessage());
        }
    }

}
