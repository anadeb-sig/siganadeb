<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contrats';

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
                  'date_debut',
                  'date_fin',
                  'entreprise_id',
                  'ouvrage_id',
                  'date_sign',
                  'code'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // Spécifie le format de la date dans la base de données
    protected $dates = ['date_debut', 'date_fin'];

    
    public function ouvrage_contrat($id)
    {
        $signer = Signer::join('contrats', 'contrats.id', '=', 'signers.contrat_id' )
        ->join('ouvrages', 'signers.ouvrage_id', '=', 'ouvrages.id' )
        ->join('projets', 'ouvrages.projet_id', '=', 'projets.id' )
        ->join('sites', 'ouvrages.site_id', '=', 'sites.id' )
        ->join('typeouvrages', 'typeouvrages.id', '=', 'ouvrages.typeouvrage_id' )
        ->join('financements', 'ouvrages.financement_id', '=', 'financements.id' )
        ->where('contrats.id', $id)
        ->get();
        return $signer;
    }

    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    
    

    /**
     * Get the Ouvrage for this model.
     *
     * @return App\Models\Ouvrage
     */
    public function signer()
    {
        return $this->hasOne('App\Models\Signer','ouvrage_id','id');
    }
}
