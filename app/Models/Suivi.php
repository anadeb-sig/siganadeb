<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Suivi extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'suivis';

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
                    'niveau_exe',
                    'recomm',
                    'conf_plan',
                    'date_suivi',
                    'site_id',
                    'niv_eval'
              ];


    public function liste_ouvrage($id)
    {
        $ouvrages = DB::table('signers')
        ->join('ouvrages', 'signers.ouvrage_id', '=', 'ouvrages.id')
        ->join('sites', 'ouvrages.site_id', '=', 'sites.id')
        ->join('projets', 'ouvrages.projet_id', '=', 'projets.id')
        ->join('financements', 'ouvrages.financement_id', '=', 'financements.id')
        ->where('sites.id', $id)
        ->get();        
        return $ouvrages;
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
     * Get the Signer for this model.
     *
     * @return App\Models\Signer
     */

    public function Galerie()
    {
        return $this->hasOne('App\Models\Galerie','suivi_id','id');
    }

    public function Site()
    {
        return $this->belongsTo('App\Models\Site','site_id','id');
    }



    /**
     * Set the date_suivi.
     *
     * @param  string  $value
     * @return void
     */
    /*public function setDateSuiviAttribute($value)
    {
        $this->attributes['date_suivi'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y', $value) : null;
    }*/

    /**
     * Get date_suivi in array format
     *
     * @param  string  $value
     * @return array
     */
    /*public function getDateSuiviAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y');
    }*/

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    /*public function getCreatedAtAttribute($value)
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
