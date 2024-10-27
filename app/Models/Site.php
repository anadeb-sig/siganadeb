<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Site extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sites';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                    'nom_site',
                    'descrip_site',
                    'budget',
                    'village_id',
                    'statu'
                ];

    // Function de vérification 
    public function verification($concat){
        $verif = DB::table('villages')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select('nom_reg','nom_pref','nom_comm','nom_cant','nom_vill','villages.id')
                ->whereRaw("CONCAT(regions.nom_reg,'',prefectures.nom_pref,'',communes.nom_comm,'',cantons.nom_cant,'',villages.nom_vill)= ?", [$concat])
                ->get();
        return $verif;
    }

    // Function de vérification 
    public function doublon($conca){
        $verif = DB::table('sites')
                ->join('villages', 'villages.id', '=', 'sites.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select('nom_reg','nom_pref','nom_comm','nom_cant','nom_vill','villages.id')
                ->whereRaw("CONCAT(regions.nom_reg,'',prefectures.nom_pref,'',communes.nom_comm,'',cantons.nom_cant,'',villages.nom_vill,'',sites.nom_site)= ?", [$conca])
                ->get();
        return $verif;
    }


    
    public function statistique_site()
    {
        $site = DB::table('ouvrages')
            ->selectRaw('
                COUNT(CASE WHEN statu = "EC" THEN 1 END) as nbr_EC,
                COUNT(CASE WHEN statu = "RD" THEN 1 END) as nbr_RD,
                COUNT(CASE WHEN statu = "RP" THEN 1 END) as nbr_RP,
                COUNT(CASE WHEN statu = "RT" THEN 1 END) as nbr_RT,
                COUNT(CASE WHEN statu = "SUSPENDU" THEN 1 END) as nbr_SUSPENDU,
                COUNT(CASE WHEN statu = "NON_DEMARRE" THEN 1 END) as nbr_NON_DEMARRE
            ')
            ->first(); // Utilisation de `first()` car un seul résultat est attendu

        return $site;
    }


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    
    /**
     * Get the Village for this model.
     *
     * @return App\Models\Village
     */
    public function Village()
    {
        return $this->belongsTo('App\Models\Village','village_id','id');
    }
}
