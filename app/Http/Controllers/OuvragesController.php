<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Typeouvrage;
use App\Models\Village;
use App\Models\Region;
use App\Models\Site;
use App\Models\Financement;
use Illuminate\Http\Request;
use Validator;
use DB;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Auth;

class OuvragesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ouvrage-create|ouvrage-edit|ouvrage-show|ouvrage-destroy', ['only' => ['index']]);
        $this->middleware('permission:ouvrage-index', ['only' => ['index']]);
        $this->middleware('permission:ouvrage-create', ['only' => ['create','store']]);
        $this->middleware('permission:ouvrage-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:ouvrage-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:ouvrage-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the ouvrages.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $Typeouvrages = Typeouvrage::all();
        $regions = Region::all();
        $projets = DB::table('projets')
                ->whereIn('name', ['INFRASTRUCTURE/COSO', 'INFRASTRUCTURE/CLASSIQUE'])
                ->get();

        $Financements = Financement::all();
        
        return view('ouvrages.index', compact('regions', 'Typeouvrages', 'projets','Financements'));
    }

    public function fetch(Request $request){
        $perPage = 30;

        $nom_reg = $request->nom_reg;
        $nom_comm = $request->nom_comm;
        $nom_ouvrage = $request->nom_ouvrage;
        $nom_projet = $request->nom_projet;
        $nom_fin = $request->nom_fin;
        $nom_site = $request->nom_site;
        $type_ouvrage = $request->nom_type;
        $statu = $request->statu;

        $ouvrages = Ouvrage::join('sites', 'sites.id', '=', 'ouvrages.site_id' )
                    ->join('typeouvrages', 'typeouvrages.id', '=', 'ouvrages.typeouvrage_id' )
                    ->join('financements', 'ouvrages.financement_id', '=', 'financements.id' )
                    ->join('projets', 'ouvrages.projet_id', '=', 'projets.id' )
                    ->join('villages', 'villages.id', '=', 'sites.village_id' )
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('ouvrages.id', 'ouvrages.nom_ouvrage', 'projets.name', 'financements.nom_fin', 'ouvrages.descrip', 'typeouvrages.nom_type', 'sites.nom_site', 'ouvrages.statu')
                    ->when($nom_reg, function ($query, $nom_reg) {
                        return $query->where('regions.nom_reg', 'like', "%$nom_reg%");
                    })->when($nom_comm, function ($query, $nom_comm) {
                        return $query->where('communes.nom_comm', 'like', "%$nom_comm%");
                    })->when($nom_ouvrage, function ($query, $nom_ouvrage) {
                        return $query->where('ouvrages.nom_ouvrage', 'like', "%$nom_ouvrage%");
                    })->when($type_ouvrage, function ($query, $type_ouvrage) {
                        return $query->where('typeouvrages.nom_type', 'like', "%$type_ouvrage%");
                    })->when($nom_projet, function ($query, $nom_projet) {
                        return $query->where('projets.name', 'like', "%$nom_projet%");
                    })->when($nom_fin, function ($query, $nom_fin) {
                        return $query->where('financements.nom_fin', 'like', "%$nom_fin%");
                    })->when($nom_site, function ($query, $nom_site) {
                        return $query->where('sites.nom_site', 'like', "%$nom_site%");
                    })->when($statu, function ($query, $statu) {
                        return $query->where('ouvrages.statu', 'like', "%$statu%");
                    })->orderByDesc('ouvrages.created_at')
                    ->paginate($perPage);

        return response()->json($ouvrages);
    }

    public function index_statu($statut)
    {
        $statu = $statut;
        $Typeouvrages = Typeouvrage::all();
        $regions = Region::all();
        $projets = DB::table('projets')
                ->whereIn('name', ['INFRASTRUCTURE/COSO', 'INFRASTRUCTURE/CLASSIQUE'])
                ->get();

        $Financements = Financement::all();
        
        return view('ouvrages.index', compact('regions', 'Typeouvrages', 'projets','Financements','statu'));
    }

    /**
     * Show the form for creating a new ouvrage.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Villages = Village::pluck('nom_vill','id')->all();
        $Typeouvrages = Typeouvrage::all();
        $regions = Region::all();
        $Financements = Financement::all();
        $projets = DB::table('projets')->get();
        return view('ouvrages.create', compact('Villages', 'regions', 'Typeouvrages','projets','Financements'));
    }

    /**
     * Store a new ouvrage in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            $data['nom_ouvrage'] = mb_strtoupper($request->nom_ouvrage, 'UTF-8');
            $data['descrip'] = mb_strtoupper($request->descrip, 'UTF-8');

            Ouvrage::create($data);

            return redirect()->route('ouvrages.index')
                ->with('success_message', 'Ouvrage ajouté avec succès');
            } catch (Exception $exception) {
                return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
            }
    }

    /**
     * Display the specified ouvrage.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $ouvrage = Ouvrage::join('sites', 'ouvrages.site_id', '=', 'sites.id')
        ->join('typeouvrages', 'typeouvrages.id', '=', 'ouvrages.typeouvrage_id')
        ->join('financements', 'ouvrages.financement_id', '=', 'financements.id' )
        ->join('projets', 'ouvrages.projet_id', '=', 'projets.id' )
        ->join('villages', 'villages.id', '=', 'sites.village_id')
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->select('ouvrages.id', 'nom_vill', 'nom_reg', 'nom_pref', 'nom_fin', 'nom_ouvrage', 'descrip', 'nom_cant', 'nom_comm', 'nom_type','name', 'ouvrages.statu')
        ->findOrFail($id);

        return response()->json($ouvrage);
    }

    /**
     * Show the form for editing the specified ouvrage.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $ouvrage = Ouvrage::findOrFail($id);
        return response()->json($ouvrage);
    }

    /**
     * Update the specified ouvrage in the storage.
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
            
            $ouvrage = Ouvrage::findOrFail($request->id);

            $data['nom_ouvrage'] = mb_strtoupper($request->nom_ouvrage, 'UTF-8');
            $data['descrip'] = mb_strtoupper($request->descrip, 'UTF-8');
            
            $ouvrage->update($data);

            return redirect()->route('ouvrages.index')
                ->with('success_message', 'Ouvrage mis à jour avec succès');
            } catch (Exception $exception) {
                return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
            }        
    }

    /**
     * Remove the specified ouvrage from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $ouvrage = Ouvrage::findOrFail($id);
            $ouvrage->delete();
            return redirect()->route('ouvrages.index')
                ->with('success_message', __('Ouvrage supprimé avec succès'));
            } catch (Exception $exception) {
                return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    //Modification du status du site
    public function updateStatus($id, $statu)
    {
        // Validation
        $validate = Validator::make([
            'id'   => $id,
            'statu'    => $statu
        ], [
            'id'   =>  'required|exists:ouvrages,id',
            'statu'    =>  'required',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('ouvrages.index')->with('error', $validate->errors()->first());
        }else{
            // if ($statu === "EC") {
            //     DB::beginTransaction();
            //     // Update Status
            //     Ouvrage::whereId($id)->update(['statu' => $statu]);

            //     Contrat::join('signers', 'contrats.id', '=', 'signers.contrat_id')
            //             ->join('ouvrages', 'signers.ouvrage_id', '=', 'ouvrages.id')
            //             ->where('signers.ouvrage_id', $id)
            //             ->update(['statu' => '']);
            //     // Commit And Redirect on index with Success Message
            //     DB::commit();
            // }
            DB::beginTransaction();

            // Update Status
            Ouvrage::whereId($id)->update(['statu' => $statu]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('ouvrages.index')->with('success_message','Statut d\'ouvrage modifié avec succès!');
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
            'nom_ouvrage' => 'required|string',
            'descrip' => 'nullable|string',
            'site_id' => 'required',
            'projet_id' => 'required',
            'financement_id' => 'required',
            'typeouvrage_id' => 'required', 
            'statu' => 'required', 
        ];
        $data = $request->validate($rules);
        return $data;
    }

    public function get_sign($id)
    {
        $ouvrage = Ouvrage::select('ouvrages.id', 'ouvrages.nom_ouvrage')
                        ->join('sites', 'ouvrages.site_id', '=', 'sites.id' )
                        ->join('financements', 'ouvrages.financement_id', '=', 'financements.id' )
                        ->join('projets', 'ouvrages.projet_id', '=', 'projets.id' )
                        ->join('villages', 'villages.id', '=', 'sites.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->where('communes.id', $id)
                        ->get();
        return response()->json($ouvrage);
    }

    // Chargement de modale d'exportation du format csv
    public function telecharger()
    {
        $regions = Region::all();
        return view('ouvrages.telecharger_format', compact('regions'));
    }

    public function format_csv(Request $request)
    {
        // Récupérer les paramètres de la requête
        $region_id = $request->query('region_id');
        $commune_id = $request->query('commune_id');

        // Récupérer les financements
        $Financement = Financement::all();
        $Typeouvrage = Typeouvrage::all();
        
        $Projet = DB::table('projets')
                    ->whereIn('name', ['INFRASTRUCTURE/COSO','INFRASTRUCTURE/CLASSIQUE'])
                    ->get();

        // Requête pour récupérer les données des villages avec jointures
        $data = Site::join('villages', 'sites.village_id', '=', 'villages.id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select(
                        'regions.nom_reg',
                        'communes.nom_comm',
                        'sites.nom_site'
                    )
                    ->when($region_id, function ($query, $region_id) {
                        return $query->where('regions.id', $region_id);
                    })
                    ->when($commune_id, function ($query, $commune_id) {
                        return $query->where('communes.id', $commune_id);
                    })
                    ->orderBy('regions.nom_reg')
                    ->orderBy('communes.nom_comm')
                    ->orderBy('sites.nom_site')
                    ->get();

        // Préparer les données pour le CSV
        $csvData = [];
        // En-têtes
        $csvData[] = ['Region', 'Commune', 'Site', 'Financement', 'Type ouvrage', 'Projet', 'Ouvrage', 'Description'];

        // Lignes des données
        foreach ($data as $item) {
            // Extraire les financements sous forme de chaîne de texte
            $financements = $Financement->pluck('nom_fin')->implode(',');
            $typeouvrages = $Typeouvrage->pluck('nom_type')->implode(',');
            $projets = $Projet->pluck('name')->implode(',');

            $csvData[] = [
                $item->nom_reg,       // Région
                $item->nom_comm,      // Commune
                $item->nom_site,      // Site
                $financements,        // Liste des financements
                $typeouvrages,                   // Type d'ouvrage (remplir ou ajouter dans votre logique)
                $projets,                   // Projet (remplir ou ajouter dans votre logique)
                '',                   // Ouvrage (remplir ou ajouter dans votre logique)
                ''                    // Description (remplir ou ajouter dans votre logique)
            ];
        }

        // Convertir les données en chaîne CSV
        $csvOutput = "\xEF\xBB\xBF"; // UTF-8 BOM pour les logiciels comme Excel
        foreach ($csvData as $row) {
            $csvOutput .= implode(";", $row) . "\n";  // Utiliser un point-virgule pour séparer les colonnes
        }

        // Retourner la réponse avec le fichier CSV
        return Response::make($csvOutput, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="format_csv_ouvrage.csv"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        ini_set('max_execution_time', 36000); // 5 minutes

        $file = $request->file('file');
        $filename = time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads', $filename);

        // Stocker le chemin du fichier dans une table temporaire
        DB::table('temps')->insertGetId(['user_id' => Auth::user()->id, 'file' => $filename, 'path' => $path]);

        // Récupérer le chemin du fichier depuis la table temporaire
        $tempFile = DB::table('temps')->latest('id')->first();

        $filepath = storage_path('app/' . $tempFile->path);
        
        // Traitement du fichier CSV
        $handle = fopen($filepath, 'r');
        $header = fgetcsv($handle, 1000, ',');
        $data = [];
        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $data[] = $row;
        }

        fclose($handle);

        $lignes = [];

        $lignes_excel = [];

        foreach($data as $i => $row){

            $details = explode(";", $row[0]);

            //Pour vérification de l'existance du site d'ouvrage
            $concat = $details[0] . $details[1] . $details[2];

            $doublon = $details[0] . $details[1] . $details[2] . $details[3] . $details[4] . $details[5] . $details[6];

            $verif = new \App\Models\Ouvrage;

            $verification = $verif->verification($concat);

            if (count($verification) > 0) {

                $double = new \App\Models\Ouvrage;
                $doublonVerification = $double->doublon($doublon);

                $id_fin = new \App\Models\Ouvrage;
                $financement_id = $id_fin->financement_id($details[3]);
                $projet_id = $id_fin->projet_id($details[5]);
                $type_ouvrage_id = $id_fin->type_ouvrage_id($details[4]);

                if (count($doublonVerification) == 0) {

                    foreach ($verification as $value) {
                        $lignes = [
                            "nom_ouvrage" => $details[6],
                            "descrip" => $details[7],
                            "site_id" => $value->id,
                            "typeouvrage_id" => $type_ouvrage_id,
                            "financement_id" => $financement_id,
                            "projet_id" => $projet_id
                        ];
                    }

                    $lignes_excel[] = $lignes;

                    $lignes = [];
                }
            }
        }

        if (!empty($lignes_excel)) {
            $rowCount = 0;
            foreach ($lignes_excel as $value) {
                $insertedId = DB::table('ouvrages')->insertGetId([
                    'nom_ouvrage' => $value["nom_ouvrage"],
                    'projet_id' => $value["projet_id"],
                    "descrip" => $value["descrip"],
                    "financement_id" => $value["financement_id"],
                    "site_id" => $value["site_id"],
                    "typeouvrage_id" => $value["typeouvrage_id"]
                ]);

                if ($insertedId) {
                    $rowCount++;
                }
            }

            return redirect()->route('ouvrages.index')
            ->with('success_message', $rowCount.' enregistrements chargés avec succès');
        
        }else {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    public function statistique_ouvrages(Request $request)
    {
        $ouvrages = DB::table('ouvrages')
                    ->join('sites', 'sites.id', '=', 'ouvrages.site_id')
                    ->join('typeouvrages', 'typeouvrages.id', '=', 'ouvrages.typeouvrage_id')
                    ->join('financements', 'ouvrages.financement_id', '=', 'financements.id')
                    ->join('projets', 'ouvrages.projet_id', '=', 'projets.id')
                    ->join('villages', 'villages.id', '=', 'sites.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->selectRaw('
                        COUNT(CASE WHEN statu = "EC" THEN 1 END) as nbr_EC,
                        COUNT(CASE WHEN statu = "RD" THEN 1 END) as nbr_RD,
                        COUNT(CASE WHEN statu = "RP" THEN 1 END) as nbr_RP,
                        COUNT(CASE WHEN statu = "RT" THEN 1 END) as nbr_RT,
                        COUNT(CASE WHEN statu = "SUSPENDU" THEN 1 END) as nbr_SUSPENDU,
                        COUNT(CASE WHEN statu = "NON_DEMARRE" THEN 1 END) as nbr_NON_DEMARRE
                    ')
                    ->when($nom_reg, function ($query) use ($nom_reg) {
                        $query->where('regions.nom_reg', 'like', "%$nom_reg%");
                    })
                    ->when($nom_comm, function ($query) use ($nom_comm) {
                        $query->where('communes.nom_comm', 'like', "%$nom_comm%");
                    })
                    ->when($nom_ouvrage, function ($query) use ($nom_ouvrage) {
                        $query->where('ouvrages.nom_ouvrage', 'like', "%$nom_ouvrage%");
                    })
                    ->when($type_ouvrage, function ($query) use ($type_ouvrage) {
                        $query->where('typeouvrages.nom_type', 'like', "%$type_ouvrage%");
                    })
                    ->when($nom_projet, function ($query) use ($nom_projet) {
                        $query->where('projets.name', 'like', "%$nom_projet%");
                    })
                    ->when($nom_fin, function ($query) use ($nom_fin) {
                        $query->where('financements.nom_fin', 'like', "%$nom_fin%");
                    })
                    ->when($nom_site, function ($query) use ($nom_site) {
                        $query->where('sites.nom_site', 'like', "%$nom_site%");
                    })
                    ->when($statu, function ($query) use ($statu) {
                        $query->where('ouvrages.statu', 'like', "%$statu%");
                    })
                    ->first();


        return response()->json($ouvrages);
    }

}
