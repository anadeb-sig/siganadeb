<?php

namespace App\Imports;

use App\Models\Site;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SitesImport implements ToModel, WithHeadingRow
{
    /**
     * Cette méthode est appelée pour chaque ligne de votre fichier Excel.
     * Nous vérifions ici si le site existe déjà avant de l'ajouter.
     */
    public function model(array $row)
    {
        // Vérification si le site existe déjà par nom et par commune, ou selon une clé unique (à ajuster selon vos critères)
        $existingSite = Site::join('villages', 'villages.id', '=', 'sites.village_id' )
                            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                            ->where('nom_reg', $row[0])
                            ->where('nom_pref', $row[1])
                            ->where('nom_comm', $row[2])
                            ->where('nom_cant', $row[3])
                            ->where('nom_vill', $row[4])
                            ->where('nom_site', $row[5])
                            ->first();

        // Si le site existe déjà, on peut soit le mettre à jour, soit l'ignorer
        if (!$existingSite) {

            $id_village = Site::join('villages', 'villages.id', '=', 'sites.village_id' )
                            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                            ->where('nom_reg', $row[0])
                            ->where('nom_pref', $row[1])
                            ->where('nom_comm', $row[2])
                            ->where('nom_cant', $row[3])
                            ->where('nom_vill', $row[4])
                            ->select('villages.id')
                            ->get();
            // Sinon, créer un nouveau site
            return new Site([
                'village_id'     => $id_village,
                'nom_site'  => $row[5],
                'descript_site'  => $row[6],
                'budget'  => $row[7],
                'statu'     => $row[8]
            ]);
        }
        // Option 1 : Ignorer l'insertion si le site existe déjà
            // return null;

            // Option 2 : Mettre à jour le site existant avec les nouvelles données
            // $existingSite->update([
            //     'statu' => $row['statu'], // On peut mettre à jour d'autres champs si nécessaire
            // ]);

            return null; // Ne pas créer de nouveau site
    }
}
