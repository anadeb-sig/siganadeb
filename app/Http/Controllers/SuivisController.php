<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Suivi;
use App\Models\Typeouvrage;
use App\Models\Region;
use Illuminate\Http\Request;
use Exception;
use App\Models\Galerie;
use Intervention\Image\Facades\Image;  // Import de la façade
use DB;

class SuivisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:suivi-create|suivi-edit|suivi-show|suivi-destroy', ['only' => ['index']]);
        $this->middleware('permission:suivi-index', ['only' => ['index']]);
        $this->middleware('permission:suivi-create', ['only' => ['create','store']]);
        $this->middleware('permission:suivi-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:suivi-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:suivi-show', ['only' => ['show']]);
        $this->middleware('permission:suivi-galerie', ['only' => ['galerie']]);
        $this->middleware('permission:photo-create', ['only' => ['store_photos']]);
        $this->middleware('permission:destroy-photo', ['only' => ['destroy_galerie']]);
    }

    /**
     * Display a listing of the suivis.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $regions = Region::all();
        return view('suivis.index', compact('regions'));
    }

    public function fetch(Request $request){
        $perPage = 60;

        $nom_reg = $request->nom_reg;
        $nom_comm = $request->nom_comm;
        $nom_site = $request->nom_site;
        $date_demarre_debut = $request->date_demarre_debut;
        $date_demarre_fin = $request->date_demarre_fin;

        $suivis = Suivi::join('sites', 'sites.id', '=', 'suivis.site_id')
                    ->join('villages', 'villages.id', '=', 'sites.village_id')
                    ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                    ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                    ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                    ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                    ->select('regions.nom_reg', 'communes.nom_comm', 'suivis.id', 'suivis.recomm', 'suivis.conf_plan', 'suivis.niveau_exe', 'suivis.date_suivi', 'sites.id as site_iid','sites.nom_site','niv_eval')
                    ->when($nom_reg, function ($query, $nom_reg) {
                        return $query->where('regions.nom_reg', 'like', "%$nom_reg%");
                    })->when($nom_comm, function ($query, $nom_comm) {
                        return $query->where('communes.nom_comm', 'like', "%$nom_comm%");
                    })->when($nom_site, function ($query, $nom_site) {
                        return $query->where('sites.nom_site', 'like', "%$nom_site%");
                    })->when($date_demarre_debut && $date_demarre_fin, function ($query) use ($date_demarre_debut, $date_demarre_fin) {
                        return $query->whereBetween('date_suivi', [$date_demarre_debut, $date_demarre_fin]);
                    })->when($date_demarre_debut && !$date_demarre_fin, function ($query) use ($date_demarre_debut) {
                        return $query->where('date_suivi', '>=', $date_demarre_debut);
                    })->when(!$date_demarre_debut && $date_demarre_fin, function ($query) use ($date_demarre_fin) {
                        return $query->where('date_suivi', '<=', $date_demarre_fin);
                    })->orderByDesc('suivis.created_at')                
                    ->paginate($perPage);

        return response()->json($suivis);
    }

    /**
     * Show the form for creating a new suivi.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $regions = Region::all();
        return view('suivis.create', compact('regions'));
    }

    public function create_photos()
    {
        $regions = Region::all();
        return view('galeries.create', compact('regions'));
    }

    /**
     * Store a new suivi in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            $data = $this->getData($request);
            
            $data['niveau_exe'] = mb_strtoupper($request->niveau_exe, 'UTF-8');
            $data['recomm'] = mb_strtoupper($request->recomm, 'UTF-8');
            $data['user_id'] = auth()->id();
            //dd($data);
            DB::table('suivis')->insertGetId($data);


            $dataa = $this->getDataa($request);

            $request->validate([
                'photo.*' => 'required|image|mimes:jpeg,png,jpg|max:2048' // Assurez-vous que ce sont des images et qu'elles respectent les règles de taille/format
            ]);

            $photos = $request->photo;
            if (!empty($photos)) {
                for ($i=0; $i < count($photos); $i++) {
                    if ($photos[$i]->isValid()) {
                        // Récupérer le fichier image
                        $image = $photos[$i];

                        // Redimensionner et compresser l'image
                        $resizedImage = Image::make($image)
                            ->resize(1024, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->encode('jpg', 70);  // Compression de l'image à 75% de qualité
                        // Générer un nom unique pour chaque image
                        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        
                        // Sauvegarder l'image compressée
                        $resizedImage->save(public_path('/images/' . $imageName));
                        
                        // Sauvegarder le nom de l'image dans le tableau des données
                        $dataa['photo'] = $imageName;
                        
                    }                

                    $dataa['descrip'] = mb_strtoupper($request->descrip[$i], 'UTF-8');
                    $dataa['user_id'] = auth()->id();

                    DB::table('galeries')->insertGetId($dataa);
                    //Galerie::create($dataa);
                }
            } else {
                return redirect()->route('suivis.index')
                ->with('error_message', __('Photos non chargées mais la ligne suivi est créée avec succès'));
            }

            return redirect()->route('suivis.index')
                ->with('success_message', __('Enregistrement effectué avec succès'));
        } catch (Exception $exception) {

            return back()->with('error_message', __('Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.'));
        }
    }


    public function store_photos(Request $request)
    {
        try {
            
            $request->validate([
            'photo.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Assurez-vous que ce sont des images et qu'elles respectent les règles de taille/format
            ]);
            

            $dataa = $this->getDataa($request);

            $photos = $request->photo;

            

            for ($i = 0; $i < count($photos); $i++) {
                if ($photos[$i]->isValid()) {
                    // Récupérer le fichier image
                    $image = $photos[$i];

                    // Redimensionner et compresser l'image
                    $resizedImage = Image::make($image)
                        ->resize(1024, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->encode('jpg', 75);  // Compression de l'image à 75% de qualité
                    // Générer un nom unique pour chaque image
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    
                    // Sauvegarder l'image compressée
                    $resizedImage->save(public_path('/images/' . $imageName));
                    
                    // Sauvegarder le nom de l'image dans le tableau des données
                    $dataa['photo'] = $imageName;
                    
                }
                
            $dataa['descrip'] = mb_strtoupper($request->descrip[$i], 'UTF-8');
            }

            
            $dataa['user_id'] = auth()->id();
            DB::table('galeries')->insertGetId($dataa);
        
            return redirect()->route('suivis.galerie')
                ->with('success_message', __('Enregistrement effectué avec succès'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['error_message' => trans('suivis.unexpected_error')]);
        }
    }

    /**
     * Display the specified suivi.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $suivi = Suivi::join('sites', 'sites.id', '=', 'suivis.site_id' )
        ->join('users', 'users.id', '=', 'suivis.user_id' )
        ->join('villages', 'villages.id', '=', 'sites.village_id' )
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id' )
        ->join('communes', 'communes.id', '=', 'cantons.commune_id' )
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->findOrFail($id);

        return view('suivis.detail', compact('suivi'));
    }

    public function show_photos($id)
    {
        $photos = DB::table('galeries')
        ->join('sites', 'galeries.site_id', '=', 'sites.id')
        ->join('villages', 'villages.id', '=', 'sites.village_id')
        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
        ->select('galeries.photo', 'galeries.descrip as description')
        ->where('sites.id', $id)
        ->get();
        return response()->json($photos);
    }

    /**
     * Show the form for editing the specified suivi.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $suivi = Suivi::findOrFail($id);

        return response()->json($suivi);
    }

    /**
     * Update the specified suivi in the storage.
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
            
            $suivi = Suivi::findOrFail($request->id);
            $suivi->update($data);

            return redirect()->route('suivis.index')
                ->with('success_message', __('Modification effectuée avec succès'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('suivis.unexpected_error')]);
        }        
    }

    /**
     * Remove the specified suivi from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $suivi = Suivi::findOrFail($id);
            $suivi->delete();

            return redirect()->route('suivis.index')
                ->with('success_message', __('Suppression effectuée avec succès'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('suivis.unexpected_error')]);
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
            'niveau_exe' => 'required|string|max:250',
            'recomm' => 'required|string|max:250',
            'conf_plan' => 'required',
            'site_id' => 'required',
            'date_suivi' => 'required|date',
            'niv_eval' => 'required|numeric'
        ];

        $data = $request->validate($rules);
        
        return $data;
    }

    protected function getDataa(Request $request)
    {
        $rules = [
            'descrip.*' => 'nullable|string|max:250',
            'site_id' => 'required'
        ];

        $data = $request->validate($rules);
        
        return $data;
    }

    public function galerie() {
        $regions = Region::all();
        $typeouvrages = Typeouvrage::all();
        $projets = DB::table('projets')
                    ->whereIn('name', ['INFRASTRUCTURE/COSO', 'INFRASTRUCTURE/CLASSIQUE'])
                    ->get();

        return view('galeries.galerie', compact('regions','typeouvrages','projets'));
    }

    public function type() {
        $typeouvrages = Typeouvrage::select('nom_type')->distinct()->get();
        return response()->json($typeouvrages);
    }

    public function galeriePost(Request $request) {
        // Pagination
        $perPage = 60;
    
        // Récupération des filtres de la requête
        $nom_reg = $request->input('nom_reg');
        $nom_comm = $request->input('nom_comm');
        $nom_ouvrage = $request->input('nom_ouvrage');
        $nom_projet = $request->input('nom_projet');
        $nom_fin = $request->input('nom_fin');
        $date_demarre_debut = $request->input('date_demarre_debut');
        $date_demarre_fin = $request->input('date_demarre_fin');
        $type_ouvrage = $request->input('nom_type');
        $nom_site = $request->input('nom_site');
    
        // Requête de récupération des suivis avec filtres
        $suivis = Galerie::join('sites', 'galeries.site_id', '=', 'sites.id')
            ->join('ouvrages', 'ouvrages.site_id', '=', 'sites.id')
            ->join('projets', 'ouvrages.projet_id', '=', 'projets.id')
            ->join('financements', 'ouvrages.financement_id', '=', 'financements.id' )
            ->join('suivis', 'sites.id', '=', 'suivis.site_id')
            ->join('typeouvrages', 'typeouvrages.id', '=', 'ouvrages.typeouvrage_id')
            ->join('villages', 'villages.id', '=', 'sites.village_id')
            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
            ->select('galeries.id', 'galeries.photo', 'suivis.recomm', 'suivis.niveau_exe', 'typeouvrages.nom_type', 'ouvrages.nom_ouvrage', 'sites.nom_site')
            ->when($nom_reg, function ($query, $nom_reg) {
                return $query->where('regions.nom_reg', 'like', "%$nom_reg%");
            })
            ->when($nom_comm, function ($query, $nom_comm) {
                return $query->where('communes.nom_comm', 'like', "%$nom_comm%");
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
                return $query->where('financements.nom_fin', 'like', "%$nom_fin%");
            })
            ->when($nom_site, function ($query, $nom_site) {
                return $query->where('sites.nom_site', 'like', "%$nom_site%");
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
            ->distinct()              
            ->paginate($perPage);
    
            return response()->json($suivis);
    }

    
    public function destroy_galerie($id)
    {
        try {
            $galerie = Galerie::findOrFail($id);
            $galerie->delete();

            return redirect()->route('galeries.galerie')
                ->with('success_message', 'Image supprimée avec succès');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('Erreure survenue lors de la suppression')]);
        }
    }

}
