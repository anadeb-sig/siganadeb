<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Inscrit;

class Classe extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'classes';

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
                  'nom_cla',
                  'ecole_id'
              ];

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

    //Recupere id de la cantine
    public function ecole_id($concat){
        $ecole_id = DB::table('ecoles')
            ->join('villages', 'villages.id', '=', 'ecoles.village_id')
            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
            ->select('ecoles.id as ecole_id', 'regions.nom_reg', 'cantons.nom_cant','nom_ecl')
            ->whereRaw("CONCAT(regions.nom_reg,'',cantons.nom_cant, '', ecoles.nom_ecl)= ?", [$concat])          
            ->get();

        return $ecole_id;
    }

    //Recupère les infos de l'école à cantine
    public function classe_id($concat){
        $classe_id = DB::table('inscrits')
            ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
            ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
            ->join('villages', 'villages.id', '=', 'ecoles.village_id')
            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
            ->select('ecoles.id as ecole_id', 'regions.nom_reg', 'cantons.nom_cant','nom_ecl')
            ->whereRaw("CONCAT(regions.nom_reg,'',prefectures.nom_pref,'',communes.nom_comm,'',cantons.nom_cant,'',villages.nom_vill, '', ecoles.nom_ecl)= ?", [$concat])
            ->where('inscrits.status', 1)
            ->select(
                'inscrits.id as classe_id','classes.nom_cla','regions.nom_reg','prefectures.nom_pref','communes.nom_comm','cantons.nom_cant','villages.nom_vill','ecoles.nom_ecl','inscrits.effec_gar','inscrits.effec_fil')          
            ->get();
        return $classe_id;
    }

    //Recupere le id pour charger le fichier excel de plats
    public function classe_id_import($concat){
    $classe = Classe::join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->select('classe_id')
                        ->whereRaw("CONCAT(cantons.nom_cant, '', ecoles.nom_ecl, '', classes.nom_cla)", $concat)
                        ->get();

            return $classe;
    }

    public function verif($concat){
        $classe_1 = Classe::whereRaw("CONCAT(ecole_id,'', nom_cla)= ?", [$concat])
                    ->count();
        return $classe_1;
    }
    
    /**
     * Get the Ecole for this model.
     *
     * @return App\Models\Ecole
     */
    public function Ecole()
    {
        return $this->belongsTo('App\Models\Ecole','ecole_id','id');
    }

    /**
     * Get the repa for this model.
     *
     * @return App\Models\Repa
     */
    public function repa()
    {
        return $this->hasOne('App\Models\Repas','classe_id','id');
    }

    /**
     * Get the classes for this model.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function inscrit()
    {
        return $this->hasOne('App\Models\Inscrit','classe_id','id');
    }


    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    /**public function getCreatedAtAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }*/

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    /**public function getUpdatedAtAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }*/

}
