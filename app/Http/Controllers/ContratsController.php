<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Entreprise;
use App\Models\Ouvrage;
use Carbon\Carbon;
use App\Models\Region;
use App\Models\Site;
use App\Models\Signer;
use Exception;
use DB;

class ContratsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:contrat-create|contrat-edit|contrat-show|contrat-destroy', ['only' => ['index']]);
        $this->middleware('permission:contrat-index', ['only' => ['index']]);
        $this->middleware('permission:contrat-create', ['only' => ['create','store']]);
        $this->middleware('permission:contrat-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:contrat-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:contrat-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the contrats.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $regions = Region::all();
        $contrats = Contrat::all();
        $sites = Site::all();
        $Entreprises = Entreprise::pluck('nom_entrep','id')->all();

        return view('contrats.index', compact('regions', 'contrats','sites','Entreprises'));
    }

    public function fetch(Request $request){
        $perPage = 1000;
        $code = $request->code;
        $date_ordre_debut = $request->date_ordre_debut;
        $date_ordre_fin = $request->date_ordre_fin;
        $date_demarre_debut = $request->date_demarre_debut;
        $date_demarre_fin = $request->date_demarre_fin;
        
        $contrats = Contrat::when($code, function ($query, $code) {
                                return $query->where('code', 'like', "%$code%");
                            })->when($date_ordre_debut && $date_ordre_fin, function ($query) use ($date_ordre_debut, $date_ordre_fin) {
                                return $query->whereBetween('date_sign', [$date_ordre_debut, $date_ordre_fin]);
                            })->when($date_ordre_debut && !$date_ordre_fin, function ($query) use ($date_ordre_debut) {
                                return $query->where('date_sign', '>=', $date_ordre_debut);
                            })->when(!$date_ordre_debut && $date_ordre_fin, function ($query) use ($date_ordre_fin) {
                                return $query->where('date_sign', '<=', $date_ordre_fin);
                            })->when($date_demarre_debut && $date_demarre_fin, function ($query) use ($date_demarre_debut, $date_demarre_fin) {
                                return $query->whereBetween('date_debut', [$date_demarre_debut, $date_demarre_fin]);
                            })->when($date_demarre_debut && !$date_demarre_fin, function ($query) use ($date_demarre_debut) {
                                return $query->where('date_debut', '>=', $date_demarre_debut);
                            })->when(!$date_demarre_debut && $date_demarre_fin, function ($query) use ($date_demarre_fin) {
                                return $query->where('date_debut', '<=', $date_demarre_fin);
                            })->orderByDesc('contrats.created_at')
                    ->paginate($perPage);

        return response()->json($contrats);
    }

    /**
     * Show the form for creating a new contrat.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {   $regions = Region::all();
        $Ouvrages = Ouvrage::all();
        $Entreprises = Entreprise::pluck('nom_entrep','id')->all();
        return view('contrats.create', compact('regions', 'Ouvrages','Entreprises'));
    }

    /**
     * Store a new contrat in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            $date = Carbon::create($data["date_debut"]);

            $entier = $request->entier;
            // Ajouter l'entier à la date
            $nouvelleDate = $date->addDays($entier);

            $data["date_fin"] = $nouvelleDate->toDateString();

            $id = DB::table('contrats')->insertGetId($data);

            $dataa = $this->getDataa($request);

            $ouvrage_id = $request->ouvrage_id;

            $lastInsertedId = null;

            for ($i=0; $i < count($ouvrage_id) ; $i++) { 
                $dataa["ouvrage_id"] = $ouvrage_id[$i];
                $dataa["contrat_id"] = $id;       
                $lastInsertedId = DB::table('signers')->insertGetId($dataa);       
            }

            // Récupérer le site_id basé sur l'ID du dernier signer inséré
            $siteId = DB::table('ouvrages')
            ->where('id', function ($query) use ($lastInsertedId) {
                $query->select('ouvrage_id')
                    ->from('signers')
                    ->where('id', $lastInsertedId);
            })
            ->value('site_id');

            // Mettre à jour le statut du site si le site_id est trouvé
            if ($data["date_debut"] <= now() && $siteId >0) {
            DB::table('sites')
                ->where('statu', 'CONTRAT_NON_SIGNE')
                ->where('id', $siteId)
                ->update(['statu' => 'EC']);
            }else if ($data["date_debut"] > now() && $siteId >0) {
                DB::table('sites')
                ->where('statu', 'CONTRAT_NON_SIGNE')
                ->where('id', $siteId)
                ->update(['statu' => 'NON_DEMARRE']);
            }

            

            return redirect()->route('contrats.index')->with('success_message', 'Contrat ajouté avec succès');
         
            } catch (Exception $exception) {
                return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    /**
     * Display the specified contrat.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $signer = Contrat::join('signers', 'signers.contrat_id', '=', 'contrats.id' )
        ->join('ouvrages', 'ouvrages.id', '=', 'signers.ouvrage_id')
        ->join('entreprises', 'entreprises.id', '=', 'signers.entreprise_id' )
        ->join('sites', 'ouvrages.site_id', '=', 'sites.id' )
        ->join('typeouvrages', 'typeouvrages.id', '=', 'ouvrages.typeouvrage_id' )
        ->join('financements', 'ouvrages.financement_id', '=', 'financements.id' )
        ->join('projets', 'ouvrages.projet_id', '=', 'projets.id' )
        ->join('villages', 'villages.id', '=', 'sites.village_id' )
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->select('contrats.id', 'date_sign', 'date_debut', 'date_fin', 'code','nom_reg', 'nom_pref', 'nom_comm','nom_cant','nom_vill')
        ->findOrFail($id);
        return view('contrats.show', compact('signer'));
    }


    public function email_entreprise($id)
    {
        $email_entreprise = Contrat::join('signers', 'signers.contrat_id', '=', 'contrats.id' )
        ->join('entreprises', 'entreprises.id', '=', 'signers.entreprise_id' )
        ->select('entreprises.email')
        ->findOrFail($id);

        return response()->json($email_entreprise);
    }

    /**
     * Show the form for editing the specified contrat.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $contrat = Contrat::findOrFail($id);
        return response()->json($contrat);
    }

    /**
     * Update the specified contrat in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        try {
            $contrat = Contrat::findOrFail($request->id);

            $rules = [
                'date_sign' => 'required|date',
                'date_debut' => 'required|date',
                'code' => ['required', Rule::unique('contrats', 'code')->ignore($contrat->id)],
            ];

            // Validation des données de la requête
            $data = $request->validate($rules);

            // Calcul de la nouvelle date
            $date = Carbon::create($data["date_debut"]);
            $entier = $request->entier;
            $nouvelleDate = $date->addDays($entier);
            $data["date_fin"] = $nouvelleDate->toDateString();
            
            // Mise à jour du contrat
            $contrat->update($data);

            return redirect()->route('contrats.index')
                ->with('success_message', 'Contrat modifié avec succès');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }


    /**
     * Remove the specified contrat from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $contrat = Contrat::findOrFail($id);
            $contrat->delete();

            return redirect()->route('contrats.contrat.index')
                ->with('success_message', trans('contrats.model_was_deleted'));
            } catch (Exception $exception) {
                return back()->with('error_message', 'Cette information est exploitée ailleur, vous ne pouver donc pas la supprimer.');
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
        // Définition des règles de validation
        $rules = [
            'date_sign' => 'required|date',
            'date_debut' => 'required|date',
            'code' => 'required|unique:contrats,code'
        ];

        // Validation des données de la requête
        $data = $request->validate($rules);
        return $data;
    }


    protected function getDataa(Request $request)
    {
        $rules = [
            'ouvrage_id' => 'required',
            'entreprise_id' => 'required'
        ];

        $data = $request->validate($rules);
        return $data;
    }
}
