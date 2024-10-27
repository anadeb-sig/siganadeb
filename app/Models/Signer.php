<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signer extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'signers';

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
                  'ouvrage_id',
                  'contrat_id',
                  'entreprise_id'
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
     * Get the Village for this model.
     *
     * @return App\Models\Village
     */
    public function Ouvrage()
    {
        return $this->belongsTo('App\Models\Ouvrage','ouvrage_id','id');
    }

    public function Entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise','entreprise_id','id');
    }

    public function Contrat()
    {
        return $this->belongsTo('App\Models\Contrat','contrat_id','id');
    }
}
