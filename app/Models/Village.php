<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'villages';

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
                  'canton_id',
                  'nom_vill'
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
     * Get the Canton for this model.
     *
     * @return App\Models\Canton
     */
    public function Canton()
    {
        return $this->belongsTo('App\Models\Canton','canton_id','id');
    }

    /**
     * Get the ecole for this model.
     *
     * @return App\Models\Ecole
     */
    public function site()
    {
        return $this->hasOne('App\Models\Site','village_id','id');
    }

    /**
     * Get the ecole for this model.
     *
     * @return App\Models\Ecole
     */
    public function ecole()
    {
        return $this->hasOne('App\Models\Ecole','village_id','id');
    }

    public function menage()
    {
        return $this->hasOne('App\Models\Menage','menage_id','id');
    }

    public function beneficiaire()
    {
        return $this->hasOne('App\Models\Beneficiaire','beneficiaire_id','id');
    }
}
