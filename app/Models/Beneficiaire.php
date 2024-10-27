<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Beneficiaire extends Model
{
    use HasFactory;
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'beneficiaires';

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
        'rang',
        'nom',
        'prenom',
        'sexe',
        'date_naiss',
        'telephone',
        'url_photo',
        'type_carte',
        'card_number',
        'menage_id',
        'kb_id',
        'type_attrib',
        'village_id'
    ];
    
    public function Village()
    {
        return $this->belongsTo('App\Models\Village','village_id','id');
    }

}
