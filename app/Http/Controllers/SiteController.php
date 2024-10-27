<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Typeouvrage;
use App\Models\Ouvrage;
use App\Models\Village;
use App\Models\Region;
use App\Models\Site;
use App\Models\Signer;
use App\Models\Financement;
use App\Exports\SiteExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

use Auth;

class SiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:site-create|site-edit|site-show|site-destroy', ['only' => ['index']]);
        $this->middleware('permission:site-index', ['only' => ['index']]);
        $this->middleware('permission:site-create', ['only' => ['create','store']]);
        $this->middleware('permission:site-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:site-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:site-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the site.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $regions = Region::all();
        $Villages = Village::pluck('nom_vill','id')->all();
        $Typeouvrages = Typeouvrage::pluck('nom_type','id')->all();
        $projets = DB::table('projets')
                ->whereIn('name', ['INFRASTRUCTURE/COSO', 'INFRASTRUCTURE/CLASSIQUE'])
                ->get();
        $Financements = Financement::all();
        
        return view('sites.index', compact('regions', 'Villages','Typeouvrages', 'projets','Financements'));
    }

    public function fetch(Request $request){
        //$perPage = 1000000;
        $nom_reg = $request->nom_reg;
        $nom_comm = $request->nom_comm;
        $nom_cant = $request->nom_cant;
        $nom_site = $request->nom_site;
        $statu = $request->statu;

        $sites = Site::join('villages', 'villages.id', '=', 'sites.village_id' )
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('nom_reg','nom_pref','nom_comm', 'nom_cant','nom_vill','sites.nom_site', 'sites.descrip_site','sites.id','sites.statu','budget')
                    ->when($nom_reg, function ($query, $nom_reg) {
                        return $query->where('regions.nom_reg', 'like', "%$nom_reg%");
                    })->when($nom_comm, function ($query, $nom_comm) {
                        return $query->where('communes.nom_comm', 'like', "%$nom_comm%");
                    })->when($nom_cant, function ($query, $nom_cant) {
                        return $query->where('cantons.nom_cant', 'like', "%$nom_cant%");
                    })->when($nom_site, function ($query, $nom_site) {
                        return $query->where('sites.nom_site', 'like', "%$nom_site%");
                    })->when($statu, function ($query, $statu) {
                        return $query->where('sites.statu', 'like', "%$statu%");
                    })->orderByDesc('sites.created_at')
                    ->get();

        return response()->json($sites);
    }

    public function create()
    {
        $Villages = Village::pluck('nom_vill','id')->all();
        $Typeouvrages = Typeouvrage::pluck('nom_type','id')->all();
        $regions = Region::all();
        $Financements = Financement::all();
        $projets = DB::table('projets')->get();
        return view('sites.create', compact('Villages', 'regions', 'Typeouvrages','projets','Financements'));
    }

    // Fonction de création du nouvel enregistrement
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);

            $data['nom_site'] = mb_strtoupper($request->nom_site, 'UTF-8');
            $data['descrip_site'] = mb_strtoupper($request->descrip_site, 'UTF-8');

            $id_site = DB::table('sites')->insertGetId($data);

            $dataa = $this->getData_ouvrage($request);

            if ($request->nom_ouvrage) {
                $nom_ouvrage = $request->nom_ouvrage;
                for ($i=0; $i < count($nom_ouvrage) ; $i++) {
                    $dataa['nom_ouvrage'] = mb_strtoupper($request->nom_ouvrage[$i], 'UTF-8');
                    $dataa['descrip'] = mb_strtoupper($request->descrip[$i], 'UTF-8');
                    $dataa['site_id'] = $id_site;
                    $dataa['financement_id'] = $request->financement_id[$i];
                    $dataa['typeouvrage_id'] = $request->typeouvrage_id[$i];
                    $dataa['projet_id'] = $request->projet_id[$i];
    
                    Ouvrage::create($dataa);
                }
            }

            return redirect()->route('sites.index')
                ->with('success_message', 'Enregistrements ajoutés avec succès');
            } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }


    /**
     * Display the specified site.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $ouvrage = Site::join('villages', 'villages.id', '=', 'sites.village_id')
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->findOrFail($id);

        return response()->json($ouvrage);
    }

    public function detail($id)
    {
        $sitelocs = Site::join('villages', 'villages.id', '=', 'sites.village_id')
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->findOrFail($id);

        $sites = Signer::join('ouvrages', 'ouvrages.id', '=', 'signers.ouvrage_id' )
        ->join('entreprises', 'entreprises.id', '=', 'signers.entreprise_id' )
        ->join('contrats', 'signers.contrat_id', '=', 'contrats.id' )
        ->join('sites', 'ouvrages.site_id', '=', 'sites.id' )
        ->join('typeouvrages', 'typeouvrages.id', '=', 'ouvrages.typeouvrage_id' )
        ->join('financements', 'ouvrages.financement_id', '=', 'financements.id' )
        ->join('projets', 'ouvrages.projet_id', '=', 'projets.id' )
        ->join('villages', 'villages.id', '=', 'sites.village_id' )
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->where('sites.id', $id)
        ->get();
        return view('sites.detail', compact('sites', 'sitelocs'));
    }

    /**
     * Show the form for editing the specified site.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $site = Site::findOrFail($id);
        return response()->json($site);
    }

    /**
     * Update the specified site in the storage.
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
            
            $site = Site::findOrFail($request->id);

            $data['nom_site'] = mb_strtoupper($request->nom_site, 'UTF-8');
            $data['descrip_site'] = mb_strtoupper($request->descrip_site, 'UTF-8');
            
            $site->update($data);

            return redirect()->route('sites.index')
                ->with('success_message', 'Site mis à jour avec succès');
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
    public function destroy(Site $site)
    {
        try {

            $site->delete();

            return redirect()->route('sites.index')
            ->with('success_message', 'Suppression effectue avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    protected function getData(Request $request)
    {
        $rules = [
            'nom_site' => 'required|string',
            'descrip_site' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'village_id' => 'required'
        ];
        $data = $request->validate($rules);
        return $data;
    }

    protected function getData_ouvrage(Request $request)
    {
        //dd($request->all());
        $rules = [
            'nom_ouvrage.*' => 'required|string',
            'descrip.*' => 'nullable|string',
            'projet_id.*' => 'required',
            'financement_id.*' => 'required',
            'typeouvrage_id.*' => 'required'
        ];
        $data = $request->validate($rules);
        return $data;
    }

    //Liste de sites par commune
    public function site_commune($id){
        $site = Site::join('villages', 'sites.village_id', '=', 'villages.id')
                     ->join('cantons', 'villages.canton_id', '=', 'cantons.id')
                     ->join('communes', 'cantons.commune_id', '=', 'communes.id')
                     ->select('sites.nom_site', 'sites.id')
                     ->where('communes.id', $id)
                     ->get();
        return response()->json($site);
    }

    //Liste d'ouvrages par site et non encore attribués aux contrats
    public function ouvrage_site($siteId){
        $ouvrages = DB::table('ouvrages')
                    ->join('sites', 'ouvrages.site_id', '=', 'sites.id')
                    ->where('sites.id', $siteId)
                    ->whereNotIn('ouvrages.id', function($query) {
                        $query->select('ouvrage_id')
                            ->from('signers');
                    })
                    ->select('ouvrages.nom_ouvrage', 'ouvrages.id')
                    ->get();
        return response()->json($ouvrages);
    }

    public function verification(Request $request)
    {
        $id = $request->query('id');

        $verification = Signer::join('ouvrages', 'ouvrages.id', '=', 'signers.ouvrage_id' )
        ->join('entreprises', 'entreprises.id', '=', 'signers.entreprise_id' )
        ->join('contrats', 'signers.contrat_id', '=', 'contrats.id' )
        ->join('sites', 'ouvrages.site_id', '=', 'sites.id' )
        ->where('sites.id',$id)
        ->count();

        return response()->json($verification);
    }

    //Modification du status du site
    public function updateStatus($id, $statu)
    {
        // Validation
        $validate = Validator::make([
            'id'   => $id,
            'statu'    => $statu
        ], [
            'id'   =>  'required|exists:sites,id',
            'statu'    =>  'required',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('site.index')->with('error', $validate->errors()->first());
        }else{

            DB::beginTransaction();

            // Update Status
            Site::whereId($id)->update(['statu' => $statu]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('sites.index')->with('success_message','Status du site modifié avec succès!');
        }
    }

    //Liste des ouvrages signés par commune
    public function ouvcontrat_sign($id)
    {
        $ouvrage = Site::select('sites.id', 'sites.nom_site')
                        ->join('ouvrages', 'sites.id', '=', 'ouvrages.site_id')
                        ->join('signers', 'ouvrages.id', '=', 'signers.ouvrage_id')
                        ->join('financements', 'ouvrages.financement_id', '=', 'financements.id' )
                        ->join('projets', 'ouvrages.projet_id', '=', 'projets.id' )
                        ->join('villages', 'villages.id', '=', 'sites.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->where('communes.id', $id)
                        ->distinct()
                        ->get();
        return response()->json($ouvrage);
    }
    
    // Chargement de modale d'exportation du format csv
    public function telecharger()
    {
        $regions = Region::all();
        return view('sites.telecharger_site', compact('regions'));
    }


    // Exportation du format csv à exploiter pour charger des données du site d'ouvrages
    public function format_csv(Request $request)
    {
        // Récupérer les paramètres de la requête
        $region_id = $request->query('region_id');
        $prefecture_id = $request->query('prefecture_id');
        $commune_id = $request->query('commune_id');
        $canton_id = $request->query('canton_id');

        // Requête pour récupérer les données des villages avec jointures
        $data = Village::join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select(
                        'regions.nom_reg',
                        'prefectures.nom_pref',
                        'communes.nom_comm',
                        'cantons.nom_cant',
                        'villages.nom_vill'
                    )
                    ->when($region_id, function ($query, $region_id) {
                        return $query->where('regions.id', $region_id);
                    })
                    ->when($prefecture_id, function ($query, $prefecture_id) {
                        return $query->where('prefectures.id', $prefecture_id);
                    })
                    ->when($commune_id, function ($query, $commune_id) {
                        return $query->where('communes.id', $commune_id);
                    })
                    ->when($canton_id, function ($query, $canton_id) {
                        return $query->where('cantons.id', $canton_id);
                    })
                    ->orderBy('regions.nom_reg')
                    ->orderBy('prefectures.nom_pref')
                    ->orderBy('communes.nom_comm')
                    ->orderBy('cantons.nom_cant')
                    ->orderBy('villages.nom_vill')
                    ->get();

        // Préparer les données pour le CSV
        $csvData = [];
        // En-têtes
        $csvData[] = ['Region', 'Prefecture', 'Commune', 'Canton', 'Village', 'Site', 'Description', 'Budget'];
        // Lignes des données
        foreach ($data as $item) {
            $csvData[] = [
                $item->nom_reg,   // Région
                $item->nom_pref,  // Préfecture
                $item->nom_comm,  // Commune
                $item->nom_cant,  // Canton
                $item->nom_vill
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
            'Content-Disposition' => 'attachment; filename="format_csv_site.csv"',
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

            $concat = $details[0] . $details[1] . $details[2] .$details[3] . $details[4];
            $doublon = $details[0] . $details[1] . $details[2] .$details[3] . $details[4] . $details[5];

            $verif = new \App\Models\Site;
            $verification = $verif->verification($concat);

            if (count($verification) > 0) {

                $double = new \App\Models\Site;
                $doublonVerification = $double->doublon($doublon);

                if (count($doublonVerification) == 0) {
                    foreach ($verification as $value) {
                        $lignes = [
                            "nom_site" => $details[5],
                            "descrip_site" => $details[6],
                            "budget" => $details[7],
                            "village_id" => $value->id,
                            "statu" => "CONTRAT_NON_SIGNE"
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
                $insertedId = DB::table('sites')->insertGetId([
                    'village_id' => $value["village_id"],
                    'nom_site' => $value["nom_site"],
                    "descrip_site" => $value["descrip_site"],
                    "budget" => $value["budget"],
                    "statu" => $value["statu"]
                ]);

                if ($insertedId) {
                    $rowCount++;
                }
            }
            return redirect()->route('sites.index')
            ->with('success_message', $rowCount.' enregistrements chargés avec succès');
        
        }else {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }
}
