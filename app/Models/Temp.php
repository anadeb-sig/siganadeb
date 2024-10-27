<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Temp extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'file',
        'path'
  ];

  public function checkDate($datee)
    {
        $date = Carbon::parse($datee);
        $dayOfWeek = $date->dayOfWeek;

        // Carbon::SATURDAY = 6, Carbon::SUNDAY = 0
        if ($dayOfWeek === Carbon::SATURDAY || $dayOfWeek === Carbon::SUNDAY) {
            return 1;
        } else{
          return 2;
        }
    }

  
}
