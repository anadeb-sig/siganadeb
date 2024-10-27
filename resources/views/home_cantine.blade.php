@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <?php

            $user = Auth::user();
            $generalRoles = ['Admin', 'Assistant', 'Hierachie'];
            
            $stat = new App\Models\Repas;
            $stat_count = $stat->header_stat();

            $stat = new App\Models\Repas;
            $stat_count_nb = $stat->header_stat_nb();
            
            $gar_fil_i = new App\Models\Repas;
            $nb_inscris = $gar_fil_i->nb_inscris();

            $effec = new App\Models\Repas;
            $nb_par_ensg = $effec->nb_par_ensg();

            $cout_val = new App\Models\Repas;
            $cout = $cout_val->cout();

            $par_region = new App\Models\Repas;
            $par_regions = $par_region->par_region();
         
    ?>
    <!-- Page Heading -->
    <div class="card">
        <div class="card-header border-bottom">
            <!-- Wizard navigation-->
            <div class="nav nav-pills nav-justified flex-column flex-xl-row nav-wizard" id="cardTab" role="tablist">
                @if ($user->hasAnyRole($generalRoles))
                    <!-- Wizard navigation item 1 -->
                    <a class="nav-item nav-link active" id="wizard1-tab" href="#wizard1" data-bs-toggle="tab" role="tab" aria-controls="wizard1" aria-selected="true">
                        <div class="wizard-step-icon">1</div>
                        <div class="wizard-step-text">
                            <div class="wizard-step-text-details">Graphes des données</div>
                        </div>
                    </a>

                    <!-- Wizard navigation item 2 -->
                    <a class="nav-item nav-link" id="wizard2-tab" href="#wizard2" data-bs-toggle="tab" role="tab" aria-controls="wizard2" aria-selected="true">
                        <div class="wizard-step-icon">2</div>
                        <div class="wizard-step-text">
                            <div class="wizard-step-text-details">Cantines scolaires</div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content" id="cardTabContent">
                @if ($user->hasAnyRole($generalRoles))
                    <!-- Wizard tab pane item 1-->
                    <div class="tab-pane py-2 py-xl-2 fade active show" id="wizard1" role="tabpanel" aria-labelledby="wizard1-tab">
                        <!-- #################################CANTINE##################################### -->                        
                        <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                            <span>Indicateurs sur Cantines scolaires</span>
                        </div>
                        <hr>
                        <div>
                            <div class="row chart">
                                <div class="col-xl-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="piechart"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="columnchart_material"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row chart mt-4">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="topXChart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row chart mt-4">
                                <div class="col-xl-12">
                                    <div class="card mb-4 chart">
                                        <div class="card-body">
                                            <div id="chartContainer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Wizard tab pane item 2-->
                    <div class="tab-pane py-2 py-xl-2 fade" id="wizard2" role="tabpanel" aria-labelledby="wizard2-tab">
                        <span>Nombre total ...</span>
                        <hr>
                        <div class="row">
                            <div class="col-xl-3">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">des garçons Inscrits</b>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($nb_inscris as $value)
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format($value->gar_inscri, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>                
                            <div class="col-xl-3">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">des filles Inscrites</b>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($nb_inscris as $value)
                                            <h5 class="card-title text-center h2  fw-bold"><?php echo number_format($value->fil_inscri, 0, ',', ' ') ?></h5>
                                            <p class="card-text text-center"></p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">des inscrits au cours pré-scolaire</b>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($nb_par_ensg as $value)
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format($value->pres_gar + $value->pres_fil, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">des inscrits au cours primaire</b>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($nb_par_ensg as $value)
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format($value->prim_fil + $value->prim_gar, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">des écoles démarrées</b>
                                    </div>
                                    <div class="card-body">
                                        @foreach($stat_count_nb as $stat_count_nb)
                                            <h5 class="card-title text-center h2  fw-bold"><?php echo number_format($stat_count_nb->somme, 0, ',', ' ') ?></h5>
                                        @endforeach
                                        <p class="card-text text-center"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">                                    
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">des élèves inscrits sur le plan notional</b>
                                    </div>
                                    @foreach ($nb_par_ensg as $value)
                                        <div class="card-body">
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format($value->pres_gar + $value->pres_fil + $value->prim_gar + $value->prim_fil, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <span>Total de plats chauds fournis aux ...</span>
                        <hr>
                        <div class="row chart mt-4">
                            @foreach ($stat_count as $value)
                                <div class="col-xl-3 mb-4">
                                    <div class="card">
                                        <div class="card-header text-center" style="background-color: #126e5127;">
                                            <b class="">garçons du cours primaire</b>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format($value->prim_gar, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Earnings (Monthly) Card Example -->
                                <div class="col-xl-3 mb-4">
                                    <div class="card">
                                        <div class="card-header text-center" style="background-color: #126e5127;">
                                            <b class="">filles du cours primaire</b>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format($value->prim_fil, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Earnings (Monthly) Card Example -->
                                <div class="col-xl-3 mb-4">                                    
                                    <div class="card">
                                        <div class="card-header text-center" style="background-color: #126e5127;">
                                            <b class="">garçons du cours pré-scolaire</b>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format($value->pres_gar, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pending Requests Card Example -->
                                <div class="col-xl-3 mb-4">                                    
                                    <div class="card">
                                        <div class="card-header text-center" style="background-color: #126e5127;">
                                            <b class="">filles du cours pré-scolaire</b>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format($value->pres_fil, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row chart mt-4">
                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-4 mb-4">                                    
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">élèves sur le plan national</b>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($stat_count as $value)
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format($value->prim_gar + $value->prim_fil + $value->pres_gar + $value->pres_fil, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8 mb-4">                                                                     
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">Montant global décaissé pour la fourniture de repas chauds</b>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($stat_count as $value)
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format(($value->prim_gar + $value->prim_fil + $value->pres_gar + $value->pres_fil)*$cout, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <span>Total plats chauds fournis aux élèves de la région ...</span>
                        <hr>
                        <div class="row chart mt-4">
                            <!-- Pending Requests Card Example -->
                            @foreach($par_regions as $region)
                                <div class="col-xl-4 mb-4">                                    
                                    <div class="card">
                                        <div class="card-header text-center" style="background-color: #126e5127;">
                                            <b class=""> {{ $region->nom_reg }}</b>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title text-center h2  fw-bold">{{ number_format($region->gar + $region->fil, 0, ',', ' ') }}</h5>
                                            <p class="card-text text-center"></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif                
            </div>
        </div>
    </div>
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
       
    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
        // Récupérer les données du serveur et les préparer pour le Pie Chart
        fetch('/repas/par_sexe')
            .then(response => response.json())
            .then(data => {
            
            var gar = 0;
            var fil = 0;
            data.forEach(item => {
                gar = parseInt(item.prim_gar) + parseInt(item.pres_gar);
                fil = parseInt(item.prim_fil) + parseInt(item.pres_fil);
            });

            var dataa = [
                ['Category', 'Value'],
                ['Garçons', gar],
                ['Filles', fil]
            ];
            var chartData = google.visualization.arrayToDataTable(dataa);

            var options = {
                title: 'Répartition de repas chauds fournis par sexe',
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(chartData, options);
            });
        }
    </script>

    <script>
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(fetchDataAndDrawChart);
        function fetchDataAndDrawChart() {
            fetch('/repas/par_fin')
                .then(response => response.json())
                .then(data => {
                // Création d'un tableau contenant les données du graphique
                var chartData = new google.visualization.DataTable();
                chartData.addColumn('string', 'Financement');
                chartData.addColumn('number', 'Nombre de plats');

                // Remplissage du tableau avec les données récupérées du serveur
                data.forEach(item => {
                    var rr = parseInt(item.gar) + parseInt(item.fil);
                    chartData.addRow([item.nom_fin, rr]);
                });
                // Options de configuration du graphique
                var options = {
                    chart: {
                    title: 'Nombre de plats fournis par financement',
                    //subtitle: 'Valeurs par financement',
                    },
                    bars: 'vertical',
                    //height: 400,
                    colors: ['#3366cc'], // Couleur des colonnes du graphique
                    vAxis: {
                    title: 'Nombre de plats',
                    },
                    hAxis: {
                    title: 'Financement',
                    },
                };
                // Création de l'instance du graphique en colonnes
                var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                // Dessin du graphique en colonnes
                chart.draw(chartData, google.charts.Bar.convertOptions(options));
            })
            .catch(error => {
            console.error('Erreur lors de la récupération des données du serveur:', error);
            });
        }
    </script>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
        fetch('/repas/char_parregion')
            .then(response => response.json())
            .then(data => {
            // Construction du tableau de données pour le graphique
            const chartData = [
                ['Label', 'Nombre de plats']
            ];
            var nom = "";
            var val = 0;
            data.forEach(item => {
                nom = item.nom_reg;
                val = parseInt(item.gar) + parseInt(item.fil);
                chartData.push([nom, val]);
            });
            // Création du graphique
            const dataTable = google.visualization.arrayToDataTable(chartData);
            const options = {
                title: 'Répartition de repas chauds par région',
                // Autres options de configuration du graphique
            };
            const chart = new google.visualization.BarChart(document.getElementById('topXChart'));
            chart.draw(dataTable, options);
            });
        }
    </script>

    <script>
        // Charger les données du serveur et générer le graphique à colonnes empilées
        function loadChartData() {
            // Effectuer une requête pour récupérer les données du serveur
            fetch('/repas/par_fin_date')
                .then(response => response.json())
                .then(data => {
                // Créer un tableau avec les données dans le format attendu par Google Charts
                const chartData = [['']];
                // Obtenir la liste des séries de données uniques
                const series = Array.from(new Set(data.map(row => row.nom_fin)));
                // Ajouter les séries de données au tableau chartData
                series.forEach(financement => chartData[0].push(financement));
                // Remplir le tableau avec les valeurs des séries
                const dates = Array.from(new Set(data.map(row => row.year)));
                dates.forEach(date => {
                    const chartRow = [date];
                    series.forEach(financement => {
                    const value = data.find(row => row.year === date && row.nom_fin === financement);
                    chartRow.push(value ? (parseInt(value.gar) + parseInt(value.fil)) : 0);
                    });
                    chartData.push(chartRow);
                });
                // Charger Google Charts et appeler la fonction de création du graphique
                google.charts.load('current', { packages: ['corechart'] });
                google.charts.setOnLoadCallback(() => drawChart(chartData));
            });
        }
        // Créer le graphique à colonnes empilées avec les données fournies
        function drawChart(data) {
            const dataTable = google.visualization.arrayToDataTable(data); 
            const options = {
                title: 'Répartition de repas chauds par année',
                isStacked: true,
                hAxis: {
                title: 'Année de fourniture'
                },
                vAxis: {
                title: 'Nombre de plats'
                },
                legend: { position: 'rigth', maxLines: 3 },
                bar: { groupWidth: '75%' },
            };
            const chart = new google.visualization.ColumnChart(document.getElementById('chartContainer'));
            chart.draw(dataTable, options);
        }
        // Appeler la fonction pour charger les données et générer le graphique
        loadChartData();
    </script>

@endsection
