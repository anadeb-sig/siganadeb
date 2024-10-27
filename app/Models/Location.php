<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'accuracy',
        'site_id'
    ];

    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; // Rayon de la Terre en kilomètres
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }


    public function Site() 
    {
        return $this->belongsTo('App\Models\Site','site_id','id');
    }

    public function suivi_id($id){
        $suivi_id = Suivi::where('site_id', $id)
                ->first();
                return $suivi_id;
    }

}
