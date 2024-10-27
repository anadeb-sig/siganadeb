<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'communes';

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
                  'prefecture_id',
                  'nom_comm'
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
     * Get the Prefecture for this model.
     *
     * @return App\Models\Prefecture
     */
    public function Prefecture()
    {
        return $this->belongsTo('App\Models\Prefecture','prefecture_id','id');
    }

    /**
     * Get the canton for this model.
     *
     * @return App\Models\Canton
     */
    public function canton()
    {
        return $this->hasOne('App\Models\Canton','commune_id','id');
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
