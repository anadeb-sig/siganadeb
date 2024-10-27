<?php

namespace App\Models;

use DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande_jour extends Model
{
    use HasFactory;

    protected $fillable = [
        'statu',
        'contrat_id',
        'description',
        'user_id',
        'titre',
        'nbJr'
    ];

    public function notifi(){
        $notes = DB::table('demande_jours')->whereNull('read_at')->get();
        return $notes;
    }
}
