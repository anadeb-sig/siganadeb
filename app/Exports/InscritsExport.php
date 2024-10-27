<?php
    namespace App\Exports;

    use App\Models\Inscrit;
    use Maatwebsite\Excel\Concerns\FromArray;
    use Maatwebsite\Excel\Concerns\WithHeadings;

    class InscritsExport implements FromArray, WithHeadings
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
            $data = Inscrit::join('classes', 'classes.id', '=', 'inscrits.classe_id')
                ->join('ecoles', 'ecoles.id', '=', 'classes.ecole_id')
                ->join('villages', 'villages.id', '=', 'ecoles.village_id')
                ->join('cantons', 'cantons.id', '=', 'villages.canton_id')
                ->join('communes', 'communes.id', '=', 'cantons.commune_id')
                ->join('prefectures', 'prefectures.id', '=', 'communes.prefecture_id')
                ->join('regions', 'regions.id', '=', 'prefectures.region_id')
                ->select(
                    'regions.nom_reg as Region',
                    'prefectures.nom_pref as Prefecture',
                    'communes.nom_comm as Commune',
                    'cantons.nom_cant as Canton',
                    'villages.nom_vill as Village',
                    'ecoles.nom_ecl as Ecole'
                )
                ->distinct()  // Ajoute DISTINCT pour ne renvoyer que des enregistrements uniques
                ->where('inscrits.status', 1)
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
                ->orderBy('ecoles.nom_ecl')
                ->get();

            $lignes_excel = [];

            foreach ($data as $donnees) {
                $lignes_excel[] = [
                    $donnees->Region,
                    $donnees->Prefecture,
                    $donnees->Commune,
                    $donnees->Canton,
                    $donnees->Village,
                    $donnees->Ecole,
                    "2024-01-12",   // date repas
                    0,              // Nombre repas garsons primaire
                    0,              // Nombre repas fille primaire
                    115,            // Coût unitaire
                    0,              // Nombre repas garsons préscolaire
                    0,              // Nombre repas fille préscolaire
                    115             // Coût unitaire préscolaire
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
                'Ecole',
                'date repas',
                'Nombre repas garsons primaire',
                'Nombre repas fille primaire',
                'Coût unitaire',
                'Nombre repas garsons préscolaire',
                'Nombre repas fille préscolaire',
                'Coût unitaire'
            ];
        }
    }