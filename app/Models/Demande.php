<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'statu',
        'site_id',
        'description',
        'date_debut_old',
        'date_fin_old',
        'date_debut_susp',
        'user_id',
        'titre',
        'nbJr'
    ];

    public function notifi(){
        $notes = DB::table('demandes')->whereNull('read_at')->get();
        return $notes;
    }
}
