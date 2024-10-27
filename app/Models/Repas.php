<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Ecole;
use App\Models\Menu;
use App\Models\Classe;
use Auth;

class Repas extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'repas';

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
                  'inscrit_id',
                  'menu_id',
                  'user_id',
                  'effect_gar',
                  'effect_fil',
                  'descrip',
                  'date_rep'
              ];

    // Méthode pour vérifier si des données existent pour le jour précédent
    public static function dataExistsForPreviousDay($concat, $date)
    {
        $dateSaisie = Carbon::parse($date); // Supposons que 'date' soit le nom du champ de saisie dans votre formulaire

        $previousDay = $dateSaisie->subDay(); // Obtenez la date précédente en soustrayant un jour à la date saisie

        return self::where(DB::raw('CONCAT(regions.nom_reg, cantons.nom_cant, ecoles.nom_ecl, classes.nom_cla)'), $concat)
                    ->whereDate('date_rep', $previousDay)->exists();
    }

    public function header_stat(){
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant')  || Auth::user()->hasRole('Hierachie')){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                ->join('menus', 'menus.id', '=', 'repas.menu_id')
                ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                ->select(DB::raw('
                    SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                    SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                    SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                    SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                    ->where('inscrits.status', '=', 1)
                ->get();
        }else{
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                ->join('menus', 'menus.id', '=', 'repas.menu_id')
                ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                ->select(DB::raw('
                    SUM(IF(nom_cla = "Primaire", effect_gar,0)) as "prim_gar",
                    SUM(IF(nom_cla = "Primaire", effect_fil,0)) as "prim_fil",
                    SUM(IF(nom_cla = "Pré_scolaire", effect_gar,0)) as "pres_gar",
                    SUM(IF(nom_cla = "Pré_scolaire", effect_fil,0)) as "pres_fil"'))
                    ->where('inscrits.status', '=', 1)
                    ->where('user_id', Auth::user()->id)
                ->get();
            }
            return $repas;
    }

    public function cout(){
        $repas = Menu::select('cout_unt')
                //->where('statut', 1)
                ->get();
                $cout = 0;
            foreach ($repas as $value) {
                $cout = $value->cout_unt;
            }
            return $cout;
    }

    public function header_stat_nb(){
        $repas = Inscrit::join('classes', 'classes.id', '=', 'inscrits.classe_id')
                ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select(DB::raw('
                SUM(nbr_gr) as "somme"'))
                ->where('inscrits.status', '=', 1)
                
                ->get();
            return $repas;

        return $totalSum;

        }
    
        public function nb_inscris(){
            $repas = Inscrit::select(DB::raw('
                                SUM(effec_gar) as "gar_inscri",
                                SUM(effec_fil) as "fil_inscri"'))
                            ->where('inscrits.status', '=', 1)
                            ->get();
                return $repas;
            }

        public function nb_par_ensg(){
            $repas =  Inscrit::join('classes', 'classes.id', '=', 'inscrits.classe_id')
                            ->select(DB::raw('
                                SUM(IF(nom_cla = "Primaire", effec_gar,0)) as "prim_gar",
                                SUM(IF(nom_cla = "Primaire", effec_fil,0)) as "prim_fil",
                                SUM(IF(nom_cla = "Pré_scolaire", effec_gar,0)) as "pres_gar",
                                SUM(IF(nom_cla = "Pré_scolaire", effec_fil,0)) as "pres_fil"'))
                                ->where('inscrits.status', '=', 1)
                            ->get();
                return $repas;
        }
    //Vérifier si le nombre de repas dans cette cantine est déjà ajouté à la date
        public function verifLigne($id){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        
                        ->where(DB::raw('CONCAT(regions.nom_reg, communes.nom_comm, ecoles.nom_ecl, classes.nom_cla, repas.date_rep)'), $id)
                        ->count();
            return $repas;
        }

        //Vérifier si le nombre de repas dans cette cantine est déjà ajouté à la date
        public function verifLigneUpdate($concat, $id){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                        ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                        ->join('menus', 'menus.id', '=', 'repas.menu_id')
                        ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                        ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                        ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                        ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                        ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                        ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                        ->where('repas.id', $id)
                        
                        ->where(DB::raw('CONCAT(regions.nom_reg, cantons.nom_cant, ecoles.nom_ecl, classes.nom_cla, repas.date_rep)'), $concat)
                        ->count();
            return $repas;
        }


        /**Nombre de plats par région */

        function par_region(){
            $repas = Repas::join('inscrits', 'inscrits.id', '=', 'repas.inscrit_id')
                ->join('classes', 'classes.id', '=', 'inscrits.classe_id')
                ->join('menus', 'menus.id', '=', 'repas.menu_id')
                //->join('users', 'users.id', '=', 'repas.user_id')
                ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                ->join('financements', 'financements.id', '=', 'ecoles.financement_id')
                ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select('nom_reg', DB::raw('
                    SUM(effect_gar) as gar,
                    SUM(effect_fil) as fil
                    '))
                ->groupBy('regions.nom_reg')
                ->where('inscrits.status', 1)
                
                ->get();

            return $repas;
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
     * Get the Class for this model.
     *
     * @return App\Models\Classe
     */
    public function Classe()
    {
        return $this->belongsTo('App\Models\Classe','classe_id','id');
    }

    /**
     * Get the Menu for this model.
     *
     * @return App\Models\Menu
     */
    public function Menu()
    {
        return $this->belongsTo('App\Models\Menu','menu_id','id');
    }

    /**
     * Get the user for this model.
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    /**
     * Set the date_rep.
     *
     * @param  string  $value
     * @return void
     */
    /*public function setDateRepAttribute($value)
    {
        $this->attributes['date_rep'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y', $value) : null;
    }*/

    /**
     * Get date_rep in array format
     *
     * @param  string  $value
     * @return array
     */
    /**public function getDateRepAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y');
    }*/

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
