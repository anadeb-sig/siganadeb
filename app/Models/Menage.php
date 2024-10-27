<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menage extends Model
{
    use HasFactory;
    protected $fillable = [
        'rang','score','hhead','sexe_cm','age_cm','study_level','marital_status','phone_member1','phone_member2','replacer','replacer_phone','member1','member1_phone','member2','member2_phone','taille_menage','men_count','women_count','card_elec_count','card_elec_seen','card_bio_count','phone_count','network_menage','network_localite','mobile_money','pmt_phase','village_id'
    ];
    
    public function Village()
    {
        return $this->belongsTo('App\Models\Village','village_id','id');
    }
}
