<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscrit extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inscrits';

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
                  'effec_fil',
                  'effec_gar',
                  'date_debut',
                  'date_fin',
                  'status',
                  'classe_id',
                  'nbr_ensg',
                  'nbr_mam',
                  'nbr_gr'
              ];

    public function verif($concat){
        // Concaténer les années de date_debut et date_fin
        $annee_id_concat = "CONCAT(YEAR(date_debut), '', YEAR(date_fin), '', classe_id)";
        // Requête Eloquent
        $classe_1 = Inscrit::whereRaw("$annee_id_concat = ?", [$concat])
                    ->count();
        return $classe_1;
    }

    public function classe_id($id){
        $classe_id = Classe::where("ecole_id", $id)
                    ->get();
        return $classe_id;
    }

    /**
     * Get the Ecole for this model.
     *
     * @return App\Models\Ecole
     */

    public function classe()
    {
        return $this->belongsTo('App\Models\Classe','classe_id','id');
    }
}
