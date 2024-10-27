<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Location;
use App\Models\Region;
use App\Models\Typeouvrage;
use App\Models\Financement;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:location-create|location-edit|location-show|location-destroy', ['only' => ['index']]);
        $this->middleware('permission:location-index', ['only' => ['index']]);
        $this->middleware('permission:location-create', ['only' => ['create','store']]);
        $this->middleware('permission:location-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:location-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:location-show', ['only' => ['show']]);
    }

    public function index(){
        $regions = Region::all();
        $ouvrages = DB::table('ouvrages')->get();

        return view('gps.index', compact('regions', 'ouvrages'));
    }

    public function fetch(){
        $gps = Location::join('sites', 'locations.site_id', '=', 'sites.id')
                        ->join('villages', 'villages.id', '=', 'sites.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('locations.id','regions.nom_reg', 'communes.nom_comm', 'villages.nom_vill','locations.latitude', 'locations.longitude', 'locations.accuracy', 'sites.nom_site')
                        ->orderByDesc('locations.created_at')
                        ->get();
                return response()->json($gps);
    }

    public function fetch_map(Request $request) {    
        $nom_reg = $request->input('nom_reg');
        $nom_comm = $request->input('nom_comm');
        $nom_ouvrage = $request->input('nom_ouvrage');
        $nom_projet = $request->input('nom_projet');
        $nom_fin = $request->input('nom_fin');
        $date_demarre_debut = $request->input('date_demarre_debut');
        $date_demarre_fin = $request->input('date_demarre_fin');
        $type_ouvrage = $request->input('nom_type');
        $nom_site = $request->input('nom_site');

        $gps = Location::join('sites', 'locations.site_id', '=', 'sites.id')
                ->join('ouvrages', 'sites.id', '=', 'ouvrages.site_id')
                ->join('suivis', 'sites.id', '=', 'suivis.site_id')
                ->join('financements', 'ouvrages.financement_id', '=', 'financements.id' )
                ->join('projets', 'ouvrages.projet_id', '=', 'projets.id' )
                ->join('typeouvrages', 'typeouvrages.id', '=', 'ouvrages.typeouvrage_id')
                ->join('villages', 'villages.id', '=', 'sites.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select('suivis.id as iiid','sites.id as iid','locations.id','regions.nom_reg', 'communes.nom_comm', 'villages.nom_vill','locations.latitude', 'locations.longitude', 'locations.accuracy', 'ouvrages.nom_ouvrage')
                ->when($nom_reg, function ($query, $nom_reg) {
                    return $query->where('regions.nom_reg', "$nom_reg");
                })
                ->when($nom_comm, function ($query, $nom_comm) {
                    return $query->where('communes.nom_comm', 'like', "%$nom_comm%");
                })
                ->when($nom_site, function ($query, $nom_site) {
                    return $query->where('sites.nom_site', 'like', "%$nom_site%");
                })
                ->when($nom_ouvrage, function ($query, $nom_ouvrage) {
                    return $query->where('ouvrages.nom_ouvrage', 'like', "%$nom_ouvrage%");
                })
                ->when($type_ouvrage, function ($query, $type_ouvrage) {
                    return $query->where('typeouvrages.nom_type', 'like', "%$type_ouvrage%");
                })
                ->when($nom_projet, function ($query, $nom_projet) {
                    return $query->where('projets.name', 'like', "%$nom_projet%");
                })
                ->when($nom_fin, function ($query, $nom_fin) {
                    return $query->where('nom_fin', 'like', "%$nom_fin%");
                })
                ->when($date_demarre_debut && $date_demarre_fin, function ($query) use ($date_demarre_debut, $date_demarre_fin) {
                    return $query->whereBetween('suivis.date_suivi', [$date_demarre_debut, $date_demarre_fin]);
                })
                ->when($date_demarre_debut && !$date_demarre_fin, function ($query) use ($date_demarre_debut) {
                    return $query->where('suivis.date_suivi', '>=', $date_demarre_debut);
                })
                ->when(!$date_demarre_debut && $date_demarre_fin, function ($query) use ($date_demarre_fin) {
                    return $query->where('suivis.date_suivi', '<=', $date_demarre_fin);
                })          
                ->get();

           return response()->json($gps);
    }

    public function cartographie()
    {
        $locations = Location::all();
        $regions = Region::all();
        $projets = DB::table('projets')->get();
        $typeouvrages = Typeouvrage::all();
        $financements = Financement::all();
        return view('gps.cartographie', compact('regions','locations','projets','typeouvrages','financements'));
    }

    public function create()
    {
        $regions = Region::all();
        return view('gps.create', compact('regions'));
    }

    public function store(Request $request)
    {
        try {
            // Valider les données
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'accuracy' => 'required|numeric|min:0|max:100',
                'site_id' => 'required|integer'
            ]);

            // Vérifier si le champ ouvrage_id est bien saisi
            if (empty($validated['site_id'])) {
                return response()->json([
                    'error' => 'Veuillez renseigner le site d\'ouvrages.'
                ], 422); // 422 Unprocessable Entity pour indiquer une erreur de validation
            }

            // Vérifier la précision
            if ($validated['accuracy'] > 100) {
                return response()->json([
                    'error' => 'L\'enregistrement non réussi, la précision n\'est pas bonne.'
                ], 422); 
            }

            // Sauvegarder les coordonnées dans la base de données
            Location::create($validated);
            
            return response()->json([
                'message' => 'Enregistrement réussi avec succès!'
            ], 201); // 201 Created pour indiquer une création réussie
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retourner les erreurs de validation
            return response()->json([
                'error' => 'L\'enregistrement non réussi, tous les champs doivent être renseignés et/ou la précision prise n\'est pas bonne.',
                'details' => $e->errors() // Ajout des détails de validation
            ], 422);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error saving coordinates: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'error' => 'Quelque chose a mal tourné sur le serveur.',
                'message' => $e->getMessage(), // Ajout du message d'erreur ici
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    
    public function destroy(Location $location)
    {
        try {
            $location->delete();

            return redirect()->route('locations.index')
                ->with('success_message', 'Coordonnées gps ont été supprimées avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

}

?>
