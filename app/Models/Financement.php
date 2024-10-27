<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financement extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'financements';

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
                  'nom_fin',
                  'commentaire',
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
    
    /**
     * Get the ecole for this model.
     *
     * @return App\Models\Ecole
     */
    public function ecole()
    {
        return $this->hasOne('App\Models\Ecole','financement_id','id');
    }
    public function ouvrage()
    {
        return $this->hasOne('App\Models\Ouvrage','financement_id','id');
    }
}
