@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <?php
        $user = Auth::user();
        $generalRoles = ['Admin', 'Assistant', 'Hierachie'];
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

                    <!-- Wizard navigation item 3 -->
                    <a class="nav-item nav-link" id="wizard3-tab" href="#wizard3" data-bs-toggle="tab" role="tab" aria-controls="wizard3" aria-selected="true">
                        <div class="wizard-step-icon">2</div>
                        <div class="wizard-step-text">
                            <div class="wizard-step-text-details">Projet fillets sociaux et services de base au fond additionnel (FA-FSB)</div>
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
        
                        <!-- #################################FSB-NOVISSI##################################### -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-2">
                            <span>Indicateurs sur FSB-NOVISSI ...</span>
                        </div>
                        <hr>
                        <div>
                            <div class="row chart">
                                <div class="col-xl-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="sexeHF"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="piechart_3d"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row chart mt-4">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="chart_div"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row chart mt-4">
                                <div class="col-xl-4">
                                    <div class="card mb-4 chart">
                                        <div class="card-body">
                                            <div id="donut_single"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="card mb-4 chart">
                                        <div class="card-body">
                                            <div id="donut_single1"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="card mb-4 chart">
                                        <div class="card-body">
                                            <div id="donut_single2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- #################################CANTINE##################################### -->
                    </div>

                    <!-- Wizard tab pane item 3-->
                    <div class="tab-pane py-2 py-xl-2 fade" id="wizard3" role="tabpanel" aria-labelledby="wizard3-tab">

                        <div class="row d-flex align-items-center">
                            <div class="col-xl-4">
                                <h1 class="h3 text-gray-800 mt-2"></h1>
                            </div>
                            <div class="col-xl-8">
                                <div class="row">
                                    <div class="col-xl-4 d-flex region">
                                        <select type="text" class="form-control w-100 btnform" id="nom_reg" value="">
                                            <option value="">Par région</option>
                                            <option value="CENTRALE">CENTRALE</option>
                                            <option value="KARA">KARA</option>
                                            <option value="MARITIME">MARITIME</option>
                                            <option value="PLATEAUX">PLATEAUX</option>
                                            <option value="SAVANES">SAVANES</option>
                                            <option value="MARITIME(GRAND_LOME)">GRAND LOME</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-6">
                                        <select type="text" class="form-control w-100 ml-0 btnform" id="projet" value="">
                                            <option value="">Par type de projet</option>
                                            <option value="FSB_NOVISSI">FSB NOVISSI</option>
                                            <option value="FSB_NOVISSI_MARITIME">FSB NOVISSI MARITIME</option>
                                            <option value="FSB_NOVISSI_EXT">FSB NOVISSI EXTENSION</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-2">
                                        <button type="submit" id="searchbtn" class="btn btn-outline-primary btnform">
                                            <i class="fa fa-search"></i>  &nbsp; recherche !
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-2">
                        <span>Nombre de ménages désignés ...</span>
                        <div class="row mt-4 mb-4">
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">dirigés par des femmes</b>
                                    </div>
                                    <div class="card-body text-center">
                                        <h5 type="text" class="card-title text-center h2  fw-bold" id="nbrMF"></h5>
                                        <p class="card-text text-center"></p>
                                    </div>
                                </div>
                            </div>                
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class=""> regorgeant au moim une personne agée</b>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-center h2  fw-bold" id="auMoinsUnAge"></h5>
                                        <p class="card-text text-center"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class=""> regorgeant au moim un handicapé</b>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-center h2  fw-bold" id="auMoinsUnDisibility"></h5>
                                        <p class="card-text text-center"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span>Nombre de bénéficiares désignés ...</span>
                        <hr>
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">femmes</b>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-center h2  fw-bold" id="getTotalFemmes"></h5>
                                        <p class="card-text text-center"></p>
                                    </div>
                                </div>
                            </div>                
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">hommes</b>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-center h2  fw-bold" id="getTotalHommes"></h5>
                                        <p class="card-text text-center"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-header text-center" style="background-color: #126e5127;">
                                        <b class="">agés (Superieur à 60ans)</b>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-center h2  fw-bold" id="countBeneficiairesAges60Plus"></h5>
                                        <p class="card-text text-center"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif                
            </div>
        </div>
    </div>
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- FSB-NOVISSI -->

    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            let nom_reg = document.getElementById('nom_reg').value;
            let projet = document.getElementById('projet').value;
            // Récupérer les données du serveur et les préparer pour le Pie Chart
            fetch(`/beneficiaires/getTotalFemmesEtHommes?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    var homme = 0;
                    var femme = 0;
                    homme = parseInt(data['total_hommes_beneficiaires']);
                    femme = parseInt(data['total_femmes_beneficiaires']);

                    var dataa = [
                        ['Category', 'Value'],
                        ['Hommes', homme],
                        ['Femmes', femme]
                    ];
                    var chartData = google.visualization.arrayToDataTable(dataa);

                var options = {
                    title: 'Répartition par sexe des bénéficiaires désignés FA-FSB',
                };
                var chart = new google.visualization.PieChart(document.getElementById('sexeHF'));
                chart.draw(chartData, options);
            });
        }
    </script>

    <!-- Recherches des chiffres par projet et/ou par région -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatatotal_femmes();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('searchbtn').addEventListener('click', function() {
                let nom_reg = document.getElementById('nom_reg').value;
                let projet = document.getElementById('projet').value;
                fetch(`/beneficiaires/getTotalFemmes?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    let total = document.getElementById('nbrMF');
                    total.innerHTML = '';
                    total.textContent = Number(data['total_femmes']).toLocaleString();
                })
                fetch(`/beneficiaires/countBeneficiairesAges60Plus?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    let total = document.getElementById('countBeneficiairesAges60Plus');
                    total.innerHTML = '';
                    total.textContent = Number(data).toLocaleString();
                })

                fetch(`/beneficiaires/getTotalFemmesEtHommes?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    let femmes = document.getElementById('getTotalFemmes');
                    femmes.innerHTML = '';
                    femmes.textContent = Number(data['total_femmes_beneficiaires']).toLocaleString();
                    let hommes = document.getElementById('getTotalHommes');
                    hommes.innerHTML = '';
                    hommes.textContent = Number(data['total_hommes_beneficiaires']).toLocaleString();
                })

                fetch(`/beneficiaires/auMoinsUnAge?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    let total = document.getElementById('auMoinsUnAge');
                    total.innerHTML = '';
                    total.textContent = Number(data["count_age_60_plus"]).toLocaleString();
                })

                fetch(`/beneficiaires/auMoinsUnAge?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    let total = document.getElementById('auMoinsUnDisibility');
                    total.innerHTML = '';
                    total.textContent = Number(data["count_disability_yes"]).toLocaleString();
                });

            });
        });

        function loaddatatotal_femmes(){
            let nom_reg = "";
            let projet = "";
            fetch(`/beneficiaires/getTotalFemmes?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    let total = document.getElementById('nbrMF');
                    total.textContent = Number(data['total_femmes']).toLocaleString();
            });

            fetch(`/beneficiaires/countBeneficiairesAges60Plus?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    let total = document.getElementById('countBeneficiairesAges60Plus');
                    total.textContent = Number(data).toLocaleString();
            });

            fetch(`/beneficiaires/getTotalFemmesEtHommes?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    let femmes = document.getElementById('getTotalFemmes');
                    femmes.innerHTML = '';
                    femmes.textContent = Number(data['total_femmes_beneficiaires']).toLocaleString();
                    let hommes = document.getElementById('getTotalHommes');
                    hommes.innerHTML = '';
                    hommes.textContent = Number(data['total_hommes_beneficiaires']).toLocaleString();
                });

                fetch(`/beneficiaires/auMoinsUnAge?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    let total = document.getElementById('auMoinsUnAge');
                    total.innerHTML = '';
                    total.textContent = Number(data["count_age_60_plus"]).toLocaleString();
                });

                fetch(`/beneficiaires/auMoinsUnAge?projet=${projet}&nom_reg=${nom_reg}`)
                .then(response => response.json())
                .then(data => {
                    let total = document.getElementById('auMoinsUnDisibility');
                    total.innerHTML = '';
                    total.textContent = Number(data["count_disability_yes"]).toLocaleString();
                });
        }
    </script>

    <script>
        function printElementAsPDF(element_id) {
            // Get the element by its ID
            const element = document.getElementById(element_id);


            // Create a new window for printing
            const printWindow = window.open('', '_blank');

            // Add the printable element to the new window's document
            printWindow.document.open();
            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(printableElement.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Wait for the window to finish loading
            printWindow.onload = function() {
                // Print the window
                printWindow.print();
                // Close the window after printing is done
                printWindow.close();
            };
        }
    </script>



    <script>
        
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawMultSeries);

        function drawMultSeries() {
            let data = new google.visualization.DataTable();
            data.addColumn('string', 'Région');
            data.addColumn('number', 'Cible');
            data.addColumn('number', 'Réalisé');

            fetch('/beneficiaires/cible_par_region')
                .then(response => response.json())
                .then(responseData => {
                    let rows = [];
                    let sum = 0;

                    responseData.forEach(item => {
                        let cible = parseInt(item.cible, 10);
                        let realiser = parseInt(item.nbr, 10);
                        sum += realiser; // Calculer la somme des réalisations
                        let nom_reg = item.nom_reg;

                        // Ajouter une ligne de données pour chaque région
                        rows.push([nom_reg, cible, realiser]);
                    });

                    // Ajouter la ligne globale avec la somme des réalisations
                    rows.push(['Global', 135143, sum]);

                    // Ajouter les lignes de données au tableau
                    data.addRows(rows);

                    var options = {
                        title: 'Niveau de réalisation des transferts par région',
                        hAxis: {
                            title: 'Régions',
                        },
                        vAxis: {
                            title: 'Nombre de BD',
                            minValue: 0,
                        }
                    };

                    var chart = new google.visualization.ColumnChart(
                        document.getElementById('chart_div'));

                    chart.draw(data, options);
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des données :', error);
                });
            }


    </script>

    <script>
        google.charts.load('current', {packages: ['corechart', 'bar']});

        let rows = []; // Stocker les lignes de données globalement

        // Fonction pour dessiner le graphique
        function drawBarColors() {
            var data = google.visualization.arrayToDataTable([
                ['Financement', 'Cible', 'Réalisation'],
                ...rows // Utilisation de l'opérateur spread pour insérer les lignes
            ]);

            var options = {
                title: 'Niveau de réalisation des transferts par financement',
                chartArea: {width: '50%'},
                colors: ['#b0120a', '#ffab91'], // Couleurs des colonnes
                hAxis: {
                    title: 'Total bénéficiaires',
                    minValue: 0
                },
                vAxis: {
                    title: 'Financement'
                }
            };

            var chart = new google.visualization.BarChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }

        google.charts.setOnLoadCallback(() => {
            fetch('/beneficiaires/cible_par_financement')
            .then(response => response.json())
            .then(responseData => {
                responseData.forEach(item => {
                    let realiser = parseInt(item.nbr, 10);

                    // Déterminer la cible en fonction du type de financement
                    let cible;
                    if (item.cible_BM > 0) {
                        cible = item.cible_BM;
                    } else if (item.cible_AFD > 0) {
                        cible = item.cible_AFD;                            
                    } else {
                        cible = item.cible_ETAT;                              
                    }

                    // Ajouter une ligne de données pour chaque financement
                    rows.push([item.financement, cible, realiser]);
                });

                drawBarColors(); // Appeler la fonction pour dessiner le graphique une fois les données prêtes
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données :', error);
            });
        });



    </script>

    <script>
        google.charts.load('current', { packages: ['corechart', 'bar'] });
        google.charts.setOnLoadCallback(drawTrendlines);

        function drawTrendlines() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Financement');
            data.addColumn('number', 'Cible');
            data.addColumn('number', 'Réalisé');

            fetch('/beneficiaires/cible_par_financement_boucles')
                .then(response => response.json())
                .then(responseData => {
                    responseData.forEach(item => {
                        let realiser = parseInt(item.nbr, 10);
                        let cible;

                        if (item.cible_BM > 0) {
                            cible = parseInt(item.cible_BM, 10);
                        } else if (item.cible_AFD > 0) {
                            cible = parseInt(item.cible_AFD, 10);
                        } else {
                            cible = parseInt(item.cible_ETAT, 10);
                        }

                        data.addRow([item.financement, cible, realiser]);
                    });

                    var options = {
                        title: 'Niveau de réalisation de la clôture des 6 tranches par financement',
                        trendlines: {
                            0: { type: 'linear', lineWidth: 5, opacity: 0.3 },
                            1: { type: 'exponential', lineWidth: 10, opacity: 0.3 }
                        },
                        hAxis: {
                            title: 'Financements'
                        },
                        vAxis: {
                            title: 'Nombre de BD'
                        },
                        bar: {
                            groupWidth: '75%'  // Ajuster ce pourcentage pour élargir ou rétrécir les barres
                        }
                    };

                    var chart = new google.visualization.ColumnChart(document.getElementById('donut_single2'));
                    chart.draw(data, options);
                })
                .catch(error => console.error('Error fetching data:', error));
        }


    </script>


    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            fetch('/beneficiaires/cible_par_six_tranche')
                .then(response => response.json())
                .then(responseData => {

                    let nbr = 0;

                    responseData.forEach(item => {
                        nbr = item.nbr; // Calculer la somme des réalisations
                    });

                    let diff = 135143 - nbr;

                    var data = google.visualization.arrayToDataTable([
                        ['Libbelés', 'Nombre réalisé'],
                        ['Obtention des 6 tranches',     nbr],
                        ['Non obtention des 6 tranches', diff],
                    ]);

                var options = {
                pieHole: 0.5,
                pieSliceTextStyle: {
                    color: 'black',
                },
                title: 'Taux de réalisation de la clôture des 06 tranches par rapport à cible globale',
                legend: 'bottom'
                };

                var chart = new google.visualization.PieChart(document.getElementById('donut_single'));
                chart.draw(data, options);
            });
        }
    </script>

    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            fetch('/beneficiaires/cible_par_region')
                .then(response => response.json())
                .then(responseData => {
                    let sum = 0;
                    let diff = 0;

                    responseData.forEach(item => {
                        sum += item.nbr; // Calculer la somme des réalisations
                    });

                    diff = 135143 - sum;

                    var data = google.visualization.arrayToDataTable([
                                ['Libbelés', 'Nombre réalisé'],
                                ['Réalisés',     sum],
                                ['Reste à réaliser',     diff],
                                ]);                

                var options = {
                pieHole: 0.5,
                pieSliceTextStyle: {
                    color: 'black',
                },
                title: 'Taux de réalisation des transferts par rapport à la cible globale',
                legend: 'bottom'
                };

                var chart = new google.visualization.PieChart(document.getElementById('donut_single1'));
                chart.draw(data, options);
            });
        }
    </script>
@endsection
