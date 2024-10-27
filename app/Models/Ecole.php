<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecole extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ecoles';

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
                  'village_id',
                  'financement_id',
                  'nom_ecl'
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


    public function verif($concat){
        $classe_1 = Ecole::whereRaw("CONCAT(village_id,'', nom_ecl)= ?", [$concat])
                    ->count();
        return $classe_1;
    }

    
    /**
     * Get the Village for this model.
     *
     * @return App\Models\Village
     */
    public function Village()
    {
        return $this->belongsTo('App\Models\Village','village_id','id');
    }

    /**
     * Get the Financement for this model.
     *
     * @return App\Models\Financement
     */
    public function Financement()
    {
        return $this->belongsTo('App\Models\Financement','financement_id','id');
    }

    /**
     * Get the classes for this model.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function classes()
    {
        return $this->hasMany('App\Models\Classe','ecole_id','id');
    }

    public function visites()
    {
        return $this->hasMany('App\Models\Visite','ecole_id','id');
    }

    /**
     * Get the jardin for this model.
     *
     * @return App\Models\Jardin
     */
    public function jardin()
    {
        return $this->hasOne('App\Models\Jardin','ecole_id','id');
    }

    /**
     * Set the date_debut.
     *
     * @param  string  $value
     * @return void
     */
    /**public function setDateDebutAttribute($value)
    {
        $this->attributes['date_debut'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y', $value) : null;
    }*/

    /**
     * Set the date_fin.
     *
     * @param  string  $value
     * @return void
     */
    /**public function setDateFinAttribute($value)
    {
        $this->attributes['date_fin'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y', $value) : null;
    }*/
    
}
