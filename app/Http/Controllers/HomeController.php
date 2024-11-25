<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Beneficiaire;
use App\Models\Financement;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function index_infra()
    {
        
        $projets = DB::table('projets')
                ->whereIn('name', ['INFRASTRUCTURE/COSO', 'INFRASTRUCTURE/CLASSIQUE'])
                ->get();

        $Financements = Financement::all();
        return view('home_infra', compact('projets','Financements'));
    }

    public function getProgressData(Request $request)
    {
        // Récupération des filtres
        $filters = [
            'regions.nom_reg' => $request->nom_reg,
            'prefectures.nom_pref' => $request->nom_pref,
            'communes.nom_comm' => $request->nom_comm,
            'cantons.nom_cant' => $request->nom_cant,
            'ouvrages.nom_ouvrage' => $request->nom_ouvrage,
            'projets.name' => $request->nom_projet,
            'financements.nom_fin' => $request->nom_fin,
            'sites.nom_site' => $request->nom_site,
        ];

        // Méthode pour générer une sous-requête avec filtres
        $generateSubQuery = function ($table) use ($filters) {
            return DB::table($table)
                ->select('ouvrage_id', DB::raw('SUM(qte * prix_unit) AS global_total'))
                ->join('ouvrages', 'ouvrages.id', '=', "{$table}.ouvrage_id")
                ->join('financements', 'ouvrages.financement_id', '=', 'financements.id')
                ->join('projets', 'ouvrages.projet_id', '=', 'projets.id')
                ->join('sites', 'sites.id', '=', 'ouvrages.site_id')
                ->join('villages', 'villages.id', '=', 'sites.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->when(array_filter($filters), function ($query) use ($filters) {
                    foreach ($filters as $column => $value) {
                        if ($value) {
                            $query->where($column, 'like', "%$value%");
                        }
                    }
                })
                ->groupBy('ouvrage_id');
        };

        // Requête principale pour les estimations
        $estimations = DB::table('estimations as e')
            ->joinSub($generateSubQuery('estimations'), 'global_totals', 'e.ouvrage_id', '=', 'global_totals.ouvrage_id')
            ->select(
                'e.ouvrage_id',
                'e.id AS record_id',
                DB::raw('SUM(e.qte * e.prix_unit) AS sous_total'),
                DB::raw('(SUM(e.qte * e.prix_unit) / global_totals.global_total) * 100 AS pourcentage'),
                'e.design'
            )
            ->groupBy('e.ouvrage_id', 'e.id', 'global_totals.global_total', 'e.design')
            ->get();

        // Requête principale pour les réalisations
        $realisations = DB::table('realisations as r')
            ->joinSub($generateSubQuery('realisations'), 'global_totals', 'r.ouvrage_id', '=', 'global_totals.ouvrage_id')
            ->select(
                'r.ouvrage_id',
                'r.estimation_id',
                'r.date_real',
                DB::raw('SUM(r.qte * r.prix_unit) AS sous_total'),
                DB::raw('(SUM(r.qte * r.prix_unit) / global_totals.global_total) * 100 AS pourcentage')
            )
            ->groupBy('r.ouvrage_id', 'r.estimation_id', 'r.date_real', 'global_totals.global_total')
            ->get();

        // Préparation des données pour le graphique
        $labels = $estimations->pluck('design')->toArray();
        $estimationsData = $estimations->pluck('pourcentage')->map(fn($val) => number_format($val, 2))->toArray();
        $realisationsData = $realisations->pluck('pourcentage')->map(fn($val) => number_format($val, 2))->toArray();

        // Structure des données pour le graphique
        $data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Estimations',
                    'data' => $estimationsData,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'fill' => false,
                ],
                [
                    'label' => 'Réalisations',
                    'data' => $realisationsData,
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'fill' => false,
                ],
            ],
        ];

        return response()->json($data);
    }




    public function index_fsb()
    {
        return view('home_fsb');
    }

    public function index_cantine()
    {
        return view('home_cantine');
    }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     * @author Shani Singh
     */
    public function getProfile()
    {
        return view('profile');
    }

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function updateProfile(Request $request)
    {
        #Validations
        $request->validate([
            'email'         => 'required|unique:users,email,'.auth()->user()->id.',id',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_number' => 'required|numeric|digits:8',
        ]);

        try {
            DB::beginTransaction();
            
            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
            ]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success_message', 'Profile est modifié avec succès.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error_message', $th->getMessage());
        }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        try {
            
            DB::beginTransaction();

            #Update Password
            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
            
            #Commit Transaction
            DB::commit();

            return redirect('/logout');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
