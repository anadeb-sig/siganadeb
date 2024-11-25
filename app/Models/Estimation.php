<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    use HasFactory;

    protected $fillable = [
        'design',
        'unite',
        'qte',
        'prix_unit',
        'type_realisation_id',
        'ouvrage_id'
    ];
}
