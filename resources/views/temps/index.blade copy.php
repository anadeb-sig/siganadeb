
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>ANADEB</title>
        <!-- App favicon -->
            
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
        <link href="{{ asset('css/styles.css')}}" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
            
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <style>

            body{
                color: #000000!important;
            }
            @keyframes blink {
                    0% { background-color: yellow; }
                    50% { background-color: red; }
                    100% { background-color: yellow; }
                }

                /* Appliquer l'animation à l'élément */
                .blinking-row {
                    animation: blink 2s infinite;
                }
        </style>
    </head> 

    <body class="nav-fixed">
        <!-- Begin page -->
        <div id="layout-wrapper">

            <div class="alert alert-success alert-dismissible fade show mt-3 text-center" id="message" role="alert" style="display:none;">
                <i class="uil uil-check me-2"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="alert alert-danger alert-dismissible fade show mt-3" id="errors" role="alert" style="display:none;">
                <i class="uil uil-exclamation-octagon me-2"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="card mt-4">
                <div class="card-header justify-content-between d-flex align-items-center">
                    <h4 class="card-title">Données de cantine à charger</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="datatable-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ecole</th>
                                    <th>Date</th>
                                    <th>Plats des garçons</th>
                                    <th>Plats des filles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                    $filteredData = [];

                                    $lignes = [];

                                    $lignes_1 = [];
                                    $lignes_2 = [];

                                foreach($data as $i => $row){
                                    
                                    $details = explode(";", $row[0]);

                                    $col1 = [];
                                    $col2 = [];

                                    $dateTime = DateTime::createFromFormat('d/m/Y', $details[6]);
                                    $date_repas = null;
                                    if ($dateTime && $dateTime->format('d/m/Y') === $details[6]) {
                                        $date_repas = $dateTime->format('Y-m-d');
                                    } else {
                                        $date_repas = $details[6];
                                    }
                                    

                                    // $repas_date = $details[6];  // Date au format DD/MM/YYYY
                                    // $dateTime = DateTime::createFromFormat('d/m/Y', $repas_date);
                                    // $date_repas = $dateTime->format('Y-m-d');

                                    //$concat = $details[0] . $details[2] . $details[5];
                                    $concat = $details[0] . $details[1] . $details[2] .$details[3] . $details[4] . $details[5];

                                    $temp = new App\Models\Temp();

                                    $week = $temp->checkDate($date_repas);

                                    $ecole = new App\Models\Classe();
                                    $ecoles = $ecole->classe_id($concat);

                                    // Date de début (2 mois avant la date système)
                                    $dateDebut = Carbon\Carbon::now()->subMonths(360);

                                    // Date fin (la date système)
                                    $dateFin = Carbon\Carbon::now();

                                    // Date "x" à vérifier
                                    $dateX = Carbon\Carbon::parse($date_repas);

                                    if (count($ecoles) > 0) {                                
                                        foreach ($ecoles as $value) {
                                            $concat0 = $details[0] . $details[2] . $details[5] . $value->nom_cla . $date_repas;
                                            $verif = new App\Models\Repas();
                                            if ($verif->verifLigne($concat0) == 0) {
                                                if ($value->nom_cla == "Primaire" && $details[7] <= $value->effec_gar && $details[8] <= $value->effec_fil && $dateX->between($dateDebut, $dateFin) && $week === 2) { ?>
                                                    <tr data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Primaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[7] }} </td>
                                                        <td>{{ $details[8] }}</td>
                                                    </tr>
                                                    <?php 

                                                    $menu_id = 0;

                                                    if ($details[9] == 115) {
                                                        $menu_id = 1;
                                                    }elseif ($details[9] == 200) {
                                                        $menu_id = 2;
                                                    }

                                                    $col1 = [
                                                        "effect_gar" => $details[7],
                                                        "effect_fil" => $details[8],
                                                        "date_rep" => $date_repas,
                                                        "classe_id" => $value->classe_id,
                                                        "menu_id" => $menu_id
                                                    ];
                                            
                                                } elseif ($value->nom_cla == "Pré_scolaire" && $details[10] <= $value->effec_gar && $details[11] <= $value->effec_fil && $dateX->between($dateDebut, $dateFin) && $week === 2) { ?>
                                                    <tr data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Pré_scolaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[10] }} </td>
                                                        <td>{{ $details[11] }}</td>
                                                    </tr>
                                                    <?php 

                                                    $menu2_id = 0;

                                                    if ($details[12] == 115) {
                                                        $menu2_id = 1;
                                                    }elseif ($details[12] == 200) {
                                                        $menu2_id = 2;
                                                    }

                                                    $col2 = [
                                                        "effect_gar" => $details[10],
                                                        "effect_fil" => $details[11],
                                                        "date_rep" => $date_repas,
                                                        "classe_id" => $value->classe_id,
                                                        "menu_id" => $menu2_id
                                                    ];

                                                } elseif ($value->nom_cla == "Primaire" && $details[7] > $value->effec_gar && $details[8] <= $value->effec_fil && $dateX->between($dateDebut, $dateFin) && $week === 2) { ?>
                                                    <tr class="blinking-row" title="Nombre de plas fournis aux garçons dépassent celui des inscrits"  data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Primaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[7] }} </td>
                                                        <td>{{ $details[8] }}</td>
                                                    </tr>
                                                <?php } elseif ($value->nom_cla == "Primaire" && $details[7] <= $value->effec_gar && $details[8] > $value->effec_fil && $dateX->between($dateDebut, $dateFin) && $week === 2) { ?>
                                                    <tr class="blinking-row" title="Nombre de plas fournis aux filles dépassent celui des inscrits"  data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Primaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[7] }} </td>
                                                        <td>{{ $details[8] }}</td>
                                                    </tr>
                                                <?php } elseif ($value->nom_cla == "Pré_scolaire" && $details[10] > $value->effec_gar && $details[11] <= $value->effec_fil && $dateX->between($dateDebut, $dateFin) && $week === 2) { ?>
                                                    <tr class="blinking-row" title="Nombre de plas fournis aux garçons dépassent celui des inscrits"  data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Pré_scolaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[10] }} </td>
                                                        <td>{{ $details[11] }}</td>
                                                    </tr>
                                                <?php } elseif ($value->nom_cla == "Pré_scolaire" && $details[10] <= $value->effec_gar && $details[11] > $value->effec_fil && $dateX->between($dateDebut, $dateFin) && $week === 2) { ?>
                                                    <tr class="blinking-row"  title="Nombre de plas fournis aux filles dépassent celui des inscrits" data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Pré_scolaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[10] }} </td>
                                                        <td>{{ $details[11] }}</td>
                                                    </tr>
                                                <?php } elseif (!$dateX->between($dateDebut, $dateFin) && $details[7] <= $value->effec_gar && $details[8] <= $value->effec_fil && $week === 2){ ?>
                                                    <tr class="blinking-row"  title="La date renseignée n\'est pas dans la marge de 7 mois avant la date d\'aujourd\'hui." data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Primaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[7] }} </td>
                                                        <td>{{ $details[8] }}</td>
                                                    </tr>
                                                    <?php } elseif (!$dateX->between($dateDebut, $dateFin) && $details[10] <= $value->effec_gar && $details[11] <= $value->effec_fil && $week === 2){ ?>
                                                    <tr class="blinking-row"  title="La date renseignée n\'est pas dans la marge de 7 mois avant la date d\'aujourd\'hui." data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Pré_scolaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[10] }} </td>
                                                        <td>{{ $details[11] }}</td>
                                                    </tr>
                                                    <?php }elseif ($value->nom_cla == "Pré_scolaire" && $details[7] <= $value->effec_gar && $details[8] <= $value->effec_fil && $week === 1){ ?>
                                                    <tr class="blinking-row"  title="La date doit être un jour de la semaine (lundi à vendredi)." data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Primaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[7] }} </td>
                                                        <td>{{ $details[8] }}</td>
                                                    </tr>
                                                    <?php }elseif ($value->nom_cla == "Primaire" && $details[10] <= $value->effec_gar && $details[11] <= $value->effec_fil && $week === 1){ ?>
                                                    <tr class="blinking-row"  title="La date doit être un jour de la semaine (lundi à vendredi)." data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Pré_scolaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[10] }} </td>
                                                        <td>{{ $details[11] }}</td>
                                                    </tr>
                                                    <?php }
                                            }else{
                                                if ($value->nom_cla == "Primaire") { ?>
                                                    <tr class="blinking-row" title="Nombre de repas sur cette date a été déjà fourni" data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Primaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[7] }} </td>
                                                        <td>{{ $details[8] }}</td>
                                                    </tr>
                                                    <?php } elseif ($value->nom_cla == "Pré_scolaire") { ?>
                                                    <tr class="blinking-row" title="Nombre de repas sur cette date a été déjà fourni" data-index="<?php echo $i; ?>">
                                                        <th data-sortable="true">{{ $value->nom_ecl }}</th>
                                                        <td>{{ __('Pré_scolaire') }}</td>
                                                        <td>{{ $date_repas }} </td>
                                                        <td>{{ $details[10] }} </td>
                                                        <td>{{ $details[11] }}</td>
                                                    </tr>
                                                <?php }
                                            }
                                            
                                        }  
                                    } else{ ?>
                                    <tr class="blinking-row" data-index="<?php echo $i; ?>">
                                        <th data-sortable="true">{{ __('Votre fichier ne contient pas de données valides pour une cantine disponible!') }}</th>
                                    </tr>
                                    <?php }
                                    
                                    $lignes_1[] = $col1;     
                                    $lignes_2[] = $col2;

                                    $lignes = array_merge($lignes_1, $lignes_2);

                                    $filteredData = array_filter($lignes, function($item) {
                                        return !empty($item);
                                    });

                                    $jsonData = json_encode($filteredData);  
                                    
                                }
                                ?> 
                            </tbody>
                        </table>
                        <!-- end card body -->
                    </div>

                    
                    <input type="hidden" id="phpArray" value="{{ $jsonData }}">

                    <div class="row" style="float: right;">
                        <div class="col-ms-12 mt-2">
                            <a href="{{ route('repas.index') }}" type="button" class="btn btn-outline-danger">Retour</a>
                            <button id="submitData" type="submit" class="btn btn-outline-primary">Valider</button>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
        </div>
        @include('temps.crud')

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <!-- <script src="/js/datatables/datatables-simple-demo.js"></script> -->
    </body>
</html>



