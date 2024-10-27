<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Demande;
use App\Models\User;
use App\Models\Region;
use App\Models\Signer;
use App\Models\Site;
use Carbon\Carbon;
use App\Models\Contrat;
use App\Notifications\ApproveNotification;
use App\Notifications\ApprobationNotification;
use DB;

class DemandeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:demande-create|demande-edit|demandeJ-show|demande-destroy', ['only' => ['index']]);
        $this->middleware('permission:demande-index', ['only' => ['index']]);
        $this->middleware('permission:demande-create', ['only' => ['create','store']]);
        $this->middleware('permission:demande-approve', ['only' => ['approve']]);
        $this->middleware('permission:demande-detail', ['only' => ['detail']]);
    }
    public function index()
    {
        return view('demandes.index');
    }

    public function fetch(Request $request){
        $perPage = 60;
        $nom_reg = $request->nom_reg;
        $nom_comm = $request->nom_comm;
        $nom_site = $request->nom_site;
        $statu = $request->statu;
        $date_demarre_debut = $request->date_demarre_debut;
        $date_demarre_fin = $request->date_demarre_fin;
        $user = $request->user;
        $titre = $request->titre;
        
        // Afficher toutes les demandes de l'utilisateur connecté
        $demandes = Demande::join('sites', 'demandes.site_id', '=', 'sites.id' )
                    ->join('villages', 'villages.id', '=', 'sites.village_id' )
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('nom_reg','nom_comm', 'nom_site','demandes.id','demandes.date_debut_susp','demandes.statu','sites.id as iid')
                    ->when($nom_reg, function ($query, $nom_reg) {
                        return $query->where('regions.nom_reg', 'like', "%$nom_reg%");
                    })->when($nom_comm, function ($query, $nom_comm) {
                        return $query->where('communes.nom_comm', 'like', "%$nom_comm%");
                    })->when($nom_site, function ($query, $nom_site) {
                        return $query->where('sites.nom_site', 'like', "%$nom_site%");
                    })->when($statu, function ($query, $statu) {
                        return $query->where('demandes.statu', 'like', "%$statu%");
                    })->when($titre, function ($query, $titre) {
                        return $query->where('demandes.titre', 'like', "%$titre%");
                    })->when($user, function ($query, $user) {
                        return $query->whereRaw("CONCAT(users.last_name, ' ', users.first_name) like ?", ["%$user%"]);
                    })->when($date_demarre_debut && $date_demarre_fin, function ($query) use ($date_demarre_debut, $date_demarre_fin) {
                        return $query->whereBetween('date_debut_susp', [$date_demarre_debut, $date_demarre_fin]);
                    })->orderByDesc('demandes.created_at')
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
            $data['statu'] = 'En attente';
            $data['user_id'] = auth()->id();

            // Nombre de jours demandé
            $nbJrDemande = $request->nbJr;
            $data["nbJr"] = $nbJrDemande;

            // Récupérer les contrats associés au site
            $contrats = Contrat::join('signers', 'contrats.id', '=', 'signers.contrat_id')
                ->join('ouvrages', 'ouvrages.id', '=', 'signers.ouvrage_id')
                ->join('sites', 'ouvrages.site_id', '=', 'sites.id')
                ->where('sites.id', $request->site_id)
                ->select('contrats.id', 'contrats.date_fin', 'contrats.date_debut') // Sélectionnez l'ID et la date_fin
                ->get();
        
            // Calculer la différence de jours entre aujourd'hui et la date de fin des contrats
            foreach ($contrats as $contrat) {
                $dateActuelle = Carbon::now(); // Date actuelle
                $dateFinContrat = Carbon::parse($contrat->date_fin); // Convertir date_fin avec Carbon
                $joursRestants = $dateActuelle->diffInDays($dateFinContrat, false); // Différence en jours

                $data['date_debut_old'] = $contrat->date_debut->toDateString();
                $data['date_fin_old'] = $contrat->date_fin->toDateString();

                // Vérification si le nombre de jours demandé dépasse les jours restants
                if ($joursRestants < $nbJrDemande) {
                    return redirect()->route('demandes.index')->with('error_message', 
                        'Pas possible de suspendre les travaux pour ' . $nbJrDemande . ' jours. Le nombre de jours restants est de seulement ' . $joursRestants . ' jours.');
                }
            }

            // Enregistrer la demande
            $id = DB::table('demandes')->insertGetId($data);

            // Notification à l'administrateur
            $admin = User::role('admin')->first(); // Récupérer l'utilisateur avec le rôle admin
            $details = ['id' => $id];
            $sites = ['site_id' => $request->site_id];
            $admin->notify(new ApproveNotification($details, $sites));
            return redirect()->route('sites.index')->with('success_message', 'Demande soumise avec succès');
        } catch (Exception $exception) {
            // Gestion des erreurs
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }


    public function approve($id, $status)
    {
        $demande = Demande::findOrFail($id);
        $demande->statu = $status;
        $demande->read_at = Carbon::now();
        $demande->save();

        

        // Notification à l'utilisateur que la demande a été approuvée
        $user = User::find($demande->user_id);
        $details = ['id' => $demande->id];
        $user->notify(new ApprobationNotification($details));

        return redirect()->route('demandes.index')->with('success', 'Demande approuvée avec succès.');
    }

    protected function getData(Request $request)
    {
        $rules = [
            'site_id' => 'required',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_debut_susp' => 'required|date',
            'nbJr'
        ];

        $data = $request->validate($rules);
        
        return $data;
    }

    public function detail($id,$iid)
    {
        $demande = Demande::join('sites', 'demandes.site_id', '=', 'sites.id' )
        ->join('villages', 'villages.id', '=', 'sites.village_id' )
        ->join('users', 'users.id', '=', 'demandes.user_id' )
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->select('last_name', 'first_name','mobile_number','users.email as email_user','nom_reg','nom_pref','nom_comm', 'nom_cant','nom_vill','titre', 'description', 'date_debut_susp', 'nbJr', 'demandes.statu', 'demandes.id', 'demandes.created_at')
        ->findOrFail($id);

        $site = Site::join('ouvrages', 'ouvrages.site_id', '=', 'sites.id' )
        ->join('signers', 'ouvrages.id', '=', 'signers.ouvrage_id' )

        ->join('entreprises', 'entreprises.id', '=', 'signers.entreprise_id' )
        ->join('contrats', 'signers.contrat_id', '=', 'contrats.id' )

        ->join('typeouvrages', 'typeouvrages.id', '=', 'ouvrages.typeouvrage_id' )
        ->join('financements', 'ouvrages.financement_id', '=', 'financements.id' )
        ->join('projets', 'ouvrages.projet_id', '=', 'projets.id' )
        ->findOrFail($iid);
        return view('demandes.detail', compact('site', 'demande'));
    }

}
