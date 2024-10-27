<?php

namespace App\Imports;

use App\Models\Repas;
use Maatwebsite\Excel\Concerns\ToModel;
//use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RepasImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        return new Repas([
            'user_id' => $row['user_id'],
            'effect_gar' => $row['effect_gar'],
            'effect_fil' => $row['effect_fil'],
            'date_rep' => $row['date_rep'],
            'classe_id' => $row['classe_id']
        ]);
    }
}
