<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typeouvrage extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'typeouvrages';

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
                  'nom_type'
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
     * Get the ouvrage for this model.
     *
     * @return App\Models\Ouvrage
     */
    public function ouvrage()
    {
        return $this->hasOne('App\Models\Ouvrage','typeouvrage_id','id');
    }

    /**
     * Set the nom_type.
     *
     * @param  string  $value
     * @return void
     */
    /**public function setNomTypeAttribute($value)
    {
        $this->attributes['nom_type'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y g:i A', $value) : null;
    }*/

    /**
     * Get nom_type in array format
     *
     * @param  string  $value
     * @return array
     */
    /**public function getNomTypeAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
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
