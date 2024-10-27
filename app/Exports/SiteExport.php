<?php

namespace App\Exports;

use App\Models\Site;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiteExport implements FromArray, WithHeadings
{
    protected $region_id, $prefecture_id, $commune_id, $canton_id;

    public function __construct($region_id, $prefecture_id, $commune_id, $canton_id)
    {
        $this->region_id = $region_id;
        $this->prefecture_id = $prefecture_id;
        $this->commune_id = $commune_id;
        $this->canton_id = $canton_id;
    }

    public function array(): array
    {
        $data = Site::join('villages', 'villages.id', '=', 'sites.village_id')
            ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
            ->join('communes', 'communes.id', '=', 'cantons.commune_id')
            ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
            ->join('regions', 'regions.id', '=', 'prefectures.region_id')
            ->select(
                'regions.nom_reg',
                'prefectures.nom_pref',
                'communes.nom_comm',
                'cantons.nom_cant',
                'villages.nom_vill',
                'sites.nom_site',
                'sites.descrip_site',
                'sites.statu',
                'sites.budget'
            )
            ->distinct()
            ->when($this->region_id, function ($query, $region_id) {
                return $query->where('regions.id', $region_id);
            })->when($this->prefecture_id, function ($query, $prefecture_id) {
                return $query->where('prefectures.id', $prefecture_id);
            })->when($this->commune_id, function ($query, $commune_id) {
                return $query->where('communes.id', $commune_id);
            })->when($this->canton_id, function ($query, $canton_id) {
                return $query->where('cantons.id', $canton_id);
            })
            ->orderBy('regions.nom_reg')
            ->orderBy('prefectures.nom_pref')
            ->orderBy('communes.nom_comm')
            ->orderBy('cantons.nom_cant')
            ->orderBy('villages.nom_vill')
            ->orderBy('sites.nom_site')
            ->orderBy('sites.descrip_site')
            ->orderBy('sites.statu')
            ->orderBy('sites.budget')
            ->get();

        $lignes_excel = [];

        foreach ($data as $donnees) {
            $lignes_excel[] = [
                $donnees->nom_reg,
                $donnees->nom_pref,
                $donnees->nom_comm,
                $donnees->nom_cant,
                $donnees->nom_vill,
                $donnees->nom_site,
                $donnees->descrip_site,
                $donnees->statu,
                $donnees->budget
            ];
        }

        return $lignes_excel;
    }

    public function headings(): array
    {
        return [
            'Region',
            'Prefecture',
            'Commune',
            'Canton',
            'Village',
            'Site',
            'Description',
            'Status',
            'Budget'
        ];
    }
}
