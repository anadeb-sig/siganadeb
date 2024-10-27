<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class Ouvrage extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ouvrages';

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
                  'nom_ouvrage',
                  'descrip',
                  'site_id',
                  'projet_id',
                  'financement_id',
                  'typeouvrage_id',
                  'statu'
              ];

      // Function de vérification 
      public function verification($concat){
        $verif = DB::table('sites')
                ->join('villages', 'sites.village_id', '=', 'villages.id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select('sites.id')
                ->whereRaw("CONCAT(regions.nom_reg,'',communes.nom_comm,'',sites.nom_site)= ?", [$concat])
                ->get();
        return $verif;
    }

    // Function de vérification de doublons
    public function doublon($conca){
        $verif = DB::table('ouvrages')
                ->join('typeouvrages', 'ouvrages.typeouvrage_id', '=', 'typeouvrages.id')
                ->join('financements', 'ouvrages.financement_id', '=', 'financements.id')
                ->join('projets', 'ouvrages.projet_id', '=', 'projets.id')
                ->join('sites', 'ouvrages.site_id', '=', 'sites.id')
                ->join('villages', 'villages.id', '=', 'sites.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                //->select('nom_reg','nom_pref','nom_comm','nom_cant','nom_vill','villages.id')
                ->whereRaw("CONCAT(regions.nom_reg,'',communes.nom_comm,'',sites.nom_site,'',financements.nom_fin,'',typeouvrages.nom_type,'',projets.name,'',ouvrages.nom_ouvrage)= ?", [$conca])
                ->get();
        return $verif;
    }

    public function financement_id($nom){
        return DB::table('financements')->where('nom_fin', $nom)->value('id');
    }

    public function projet_id($nom){
        return DB::table('projets')->where('name', $nom)->value('id');
    }

    public function type_ouvrage_id($nom){
        return DB::table('typeouvrages')->where('nom_type', $nom)->value('id');
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
    
    
    public function Site()
    {
        return $this->belongsTo('App\Models\Site','site_id','id');
    }

    /**
     * Get the Typeouvrage for this model.
     *
     * @return App\Models\Typeouvrage
     */
    public function Typeouvrage()
    {
        return $this->belongsTo('App\Models\Typeouvrage','typeouvrage_id','id');
    }

    
    public function Projet()
    {
        return $this->belongsTo('App\Models\Projet','projet_id','id');
    }

    public function Financement()
    {
        return $this->belongsTo('App\Models\Financement','financement_id','id');
    }

    public function Signer()
    {
        return $this->hasOne('App\Models\Signer','ouvrage_id','id');
    }

    public function Contact()
    {
        return $this->hasOne('App\Models\Contact','ouvrage_id','id');
    }


}
