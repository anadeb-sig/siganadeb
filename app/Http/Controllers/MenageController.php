<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menage;
use DB;

class MenageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:canton-create|canton-edit|canton-show|canton-destroy', ['only' => ['index']]);
        $this->middleware('permission:menage-index', ['only' => ['index']]);
        $this->middleware('permission:menage-liste_membre', ['only' => ['liste_membres']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fsb.menages.index');
    }

    public function fetch(Request $request)
    {
        $perPage = 30; // Nombre de lignes par page
        //$page = $request->query('page', 1); // Page actuelle
        $nom_reg = $request->nom_reg;
        $nom_comm = $request->nom_comm;
        $nom_vill = $request->nom_vill;
        $sexe = $request->sexe;
        $hhead = $request->hhead;
        $phone_member1 = $request->phone_member1; 
        $rang = $request->rang;
        $id = $request->id;
        $nature_projet = $request->nature_projet;

        //dd($request->all());

        $menages = Menage::join('villages', 'villages.id', '=', 'menages.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select('regions.nom_reg', 'communes.nom_comm', 'villages.nom_vill', 'rang', 'hhead', 'sexe_cm', 'age_cm','menages.id')
                ->when($nom_reg, function ($query, $nom_reg) {
                    return $query->where('regions.nom_reg',  'like', "%$nom_reg%");
                })
                ->when($nom_comm, function ($query, $nom_comm) {
                    return $query->where('communes.nom_comm',  'like', "%$nom_comm%");
                })
                ->when($nom_vill, function ($query, $nom_vill) {
                    return $query->where('villages.nom_vill',  'like', "%$nom_vill%");
                })->when($nature_projet, function ($query, $nature_projet) {
                    return $query->where('menages.nature_projet', $nature_projet);
                })->when($id, function ($query, $id) {
                    return $query->where('menages.id',  'like', "%$id%");
                })->when($rang, function ($query, $rang) {
                    return $query->where('menages.rang', $rang);
                })->when($hhead, function ($query, $hhead) {
                    return $query->where('menages.hhead',  'like', "%$hhead%");
                })->when($sexe, function ($query, $sexe) {
                    return $query->where('menages.sexe_cm', $sexe);
                })->when($phone_member1, function ($query, $phone_member1) {
                    return $query->where('menages.phone_member1',  'like', "%$phone_member1%");
                })->paginate($perPage);
        
        return response()->json($menages);
    }

    public function liste_membres($id_menage) {
        // Utiliser la pagination pour limiter le nombre de résultats renvoyés à la fois
        $membres = DB::table('membres')
                    ->select('id', 'nom', 'prenom', 'sexe', 'age', 'lien_parent', 'phone_number1') // Sélectionner uniquement les colonnes nécessaires
                    ->where('menage_id', $id_menage)
                    ->paginate(30); // Pagination avec un maximum de 30 résultats par page
        return response()->json($membres);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
