<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Contrat;
use App\Models\Demande_jour;
use App\Notifications\DemandeJourNotification;
use App\Notifications\ApprobationJourNotification;

use DB;

class Demande_jourController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:demandeJ-create|demandeJ-edit|demandeJ-show|demandeJ-destroy', ['only' => ['index']]);
        $this->middleware('permission:demandeJ-index', ['only' => ['index']]);
        $this->middleware('permission:demandeJ-create', ['only' => ['create','store']]);
        $this->middleware('permission:demandeJ-approve', ['only' => ['approve']]);
        $this->middleware('permission:demandeJ-detail', ['only' => ['detail']]);
    }
    
    public function index()
    {
        return view('demande_jours.index');
    }

    public function fetch(Request $request){
        $perPage = 60;
        $nom_reg = $request->nom_reg;
        $nom_comm = $request->nom_comm;
        $nom_site = $request->nom_site;
        $statu = $request->statu;
        $user = $request->user;
        $titre = $request->titre;
        $code = $request->code;
        
        // Afficher toutes les demandes de l'utilisateur connecté
        $demandes = Demande_jour::join('contrats', 'demande_jours.contrat_id', '=', 'contrats.id' )
                    ->join('users', 'demande_jours.user_id', '=', 'users.id' )
                    ->join('signers', 'contrats.id', '=', 'signers.contrat_id' )
                    ->join('ouvrages', 'signers.ouvrage_id', '=', 'ouvrages.id' )
                    ->join('sites', 'ouvrages.site_id', '=', 'sites.id' )
                    ->join('villages', 'villages.id', '=', 'sites.village_id' )
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('nom_reg','nom_comm', 'titre','demande_jours.id','demande_jours.statu','sites.id as iid','last_name', 'first_name')
                    ->when($nom_reg, function ($query, $nom_reg) {
                        return $query->where('regions.nom_reg', 'like', "%$nom_reg%");
                    })->when($nom_comm, function ($query, $nom_comm) {
                        return $query->where('communes.nom_comm', 'like', "%$nom_comm%");
                    })->when($nom_site, function ($query, $nom_site) {
                        return $query->where('sites.nom_site', 'like', "%$nom_site%");
                    })->when($statu, function ($query, $statu) {
                        return $query->where('demande_jours.statu', 'like', "%$statu%");
                    })->when($titre, function ($query, $titre) {
                        return $query->where('demande_jours.titre', 'like', "%$titre%");
                    })->when($code, function ($query, $code) {
                        return $query->where('contrats.code', 'like', "%$code%");
                    })->when($user, function ($query, $user) {
                        return $query->whereRaw("CONCAT(users.last_name, ' ', users.first_name) like ?", ["%$user%"]);
                    })->orderByDesc('demande_jours.created_at')
                    ->groupBy('nom_reg','nom_comm', 'titre','demande_jours.id','demande_jours.statu','sites.id','last_name', 'first_name') 
                    ->paginate($perPage);

        return response()->json($demandes);
    }

    public function create()
    {
        $regions = Region::all();
        return view('sites.demande_suspension', compact('regions'));
    }

    public function store(Request $request)
    {
        try {
            // Récupérer les données du formulaire
            $data = $this->getData($request);

            // Initialiser les valeurs par défaut
            $data['statu'] = 'en attente';
            $data['user_id'] = auth()->id();

            // Enregistrer la demande
            $id = DB::table('demande_jours')->insertGetId($data);

            // Notification à l'administrateur
            $admin = User::role('admin')->first(); // Récupérer l'utilisateur avec le rôle admin
            $details = ['id' => $id];
            $contrats = ['contrat_id' => $request->contrat_id];
            $date_fin = ['date_fin' => $request->date_fin];
            $admin->notify(new DemandeJourNotification($details,$contrats,$date_fin));
            return redirect()->route('contrats.index')->with('success_message', 'Demande soumise avec succès');
        } catch (Exception $exception) {
            // Gestion des erreurs
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }


    public function approve($id, $status, $date_fin)
    {
        $demande = Demande_jour::findOrFail($id);
        $demande->statu = $status;
        $demande->read_at = Carbon::now();
        $demande->save();

        $entier = $demande->nbJr;
        // Ajouter l'entier à la date
        $nouvelle_date_fin = Carbon::parse($date_fin)->addDays($demande->nbJr);

        $contrat = Contrat::findOrFail($demande->contrat_id);
        $contrat->date_fin = $nouvelle_date_fin->toDateString();
        $contrat->save();

        // Notification à l'utilisateur que la demande a été approuvée
        $user = User::find($demande->user_id);
        $details = ['id' => $demande->id];
        $user->notify(new ApprobationJourNotification($details));

        return redirect()->route('demandejours.index')->with('success', 'Demande approuvée avec succès.');
    }

    protected function getData(Request $request)
    {
        $rules = [
            'contrat_id' => 'required',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'nbJr' => 'required|numeric'
        ];

        $data = $request->validate($rules);
        
        return $data;
    }

    public function detail($id)
    {
        $demande = Demande_jour::join('contrats', 'demande_jours.contrat_id', '=', 'contrats.id' )
        ->join('users', 'demande_jours.user_id', '=', 'users.id' )
        ->join('signers', 'contrats.id', '=', 'signers.contrat_id' )
        ->join('entreprises', 'signers.entreprise_id', '=', 'entreprises.id')
        ->join('ouvrages', 'signers.ouvrage_id', '=', 'ouvrages.id' )
        ->join('sites', 'ouvrages.site_id', '=', 'sites.id' )
        ->join('villages', 'villages.id', '=', 'sites.village_id' )
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->select('nom_reg','nom_comm', 'nom_pref', 'nom_cant', 'nom_vill', 'titre','demande_jours.id','demande_jours.statu','description','nbJr',
        'code', 'date_debut', 'date_fin','date_sign', 'last_name', 'first_name','mobile_number','users.email as email_user', 'nom_entrep','num_id_f','nom_charge','prenom_charge','entreprises.email','tel','addr')
        ->findOrFail($id);

        return view('demande_jours.detail', compact('demande'));
    }
}
