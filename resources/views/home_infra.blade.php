@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="card">
        <div class="card-body">
            <div class="main-content">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>Tableau de bord</h1>
                        <div class="file-export">
                            <button class="btn btn-outline-primary btnform" id="excelButton">
                                <i class="fas fa-file-export"></i> &nbsp;Export les donnes en Excel
                            </button>
                        </div>
                    </div>

                    <div class="accordion mb-4" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size: 1.2em;">
                                    #Filtrer la liste
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form method="get" action="" accept-charset="UTF-8">
                                        <div class="row mt-4">
                                            <div class="col-xl-3">
                                                <label for="nom_reg" class="control-label">Région</label>
                                                <input class="form-control w-100 majuscules" id="nom_reg" name="nom_reg" type="text" placeholder="exemple: CENTRALE ..." />
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="nom_pref" class="control-label">Préfecture</label>
                                                <input class="form-control w-100 majuscules" id="nom_pref" name="nom_pref" type="text" placeholder="exemple: CENTRALE ..." />
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="nom_comm" class="control-label">Commune</label>
                                                <input class="form-control w-100" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: badin 2..." />
                                            </div>
                                            <div class="col-xl-3 {{ $errors->has('nom_cant') ? 'has-error' : '' }}">
                                                <label for="nom_cant" class="control-label">Nom du canton</label>
                                                <input class="form-control w-100" id="nom_cant" name="nom_cant" type="text" placeholder="exemple: badin..." />
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-xl-3 {{ $errors->has('nom_site') ? 'has-error' : '' }}">
                                                <label for="nom_site" class="control-label">Site d'ouvrage</label>
                                                <input class="form-control w-100" id="nom_site" name="nom_site" type="text" placeholder="exemple: ....." />
                                            </div>
                                            <div class="col-xl-2 {{ $errors->has('nom_site') ? 'has-error' : '' }}">
                                                <label for="nom_ouvrage" class="control-label">Ouvrage</label>
                                                <input class="form-control w-100" id="nom_ouvrage" name="nom_ouvrage" type="text" placeholder="exemple: ....." />
                                            </div>
                                            <div class="col-xl-3 {{ $errors->has('nom_site') ? 'has-error' : '' }}">
                                                <label for="nom_site" class="control-label">Projet</label>
                                                <select class="form-control" id="nom_projet" name="nom_projet" required="true">
                                                        <option value="" style="display: none;" disabled selected>{{ __('Selectionner le projet') }}</option>
                                                    @foreach ($projets as $projet)
                                                        <option value="{{ $projet->name }}">
                                                            {{ $projet->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-2 {{ $errors->has('nom_fin') ? 'has-error' : '' }}">
                                                <label for="nom_fin" class="control-label">Financement</label>
                                                <select class="form-control" id="nom_fin" name="nom_fin" value="" required>
                                                    <option value="" style="display: none;" disabled selected>Select financement</option>
                                                    @foreach ($Financements as $Financement)
                                                        <option value="{{ $Financement->nom_fin }}">
                                                            {{ $Financement->nom_fin }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xl-2 modal-footer mt-4">
                                                <a href="{{ Route('home_infra') }}" type="button" class="btn btn-outline-danger">
                                                    <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                                </a>
                                                &nbsp;&nbsp;
                                                <button id="searchbtn" type="button" class="btn btn-outline-primary recherche">
                                                    <i class="fa fa-search"></i> &nbsp;Rechercher
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <span>Nombre de sites par status ...</span>
                    <hr>
                    <div class="row">
                        <div class="col-xl-2">
                            <div class="card">
                                <div class="card-header text-center" style="background-color: #126e5127;">
                                    <b class="">Réception définitive</b>
                                </div>
                                <div class="card-body">
                                  <div class="text-center">
                                    <h5 class="h2  fw-bold"><a href="{{ route('ouvrages.statut', ['statu' => 'RD']) }}" id="rd"></a></h5>                                    
                                  </div>
                                </div>
                            </div>
                        </div>                
                        <div class="col-xl-2">
                            <div class="card">
                                <div class="card-header text-center" style="background-color: #126e5127;">
                                    <b class="">Réception provisoire</b>
                                </div>
                                <div class="card-body">
                                  <div class="text-center">
                                    <h5 class="h2  fw-bold"><a href="{{ route('ouvrages.statut', ['statu' => 'RP']) }}"  id="rp"></a></h5>                                    
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="card">
                                <div class="card-header text-center" style="background-color: #126e5127;">
                                    <b class="">Réception technique</b>
                                </div>
                                <div class="card-body">
                                  <div class="text-center">
                                    <h5 class="h2  fw-bold"><a href="{{ route('ouvrages.statut', ['statu' => 'RT']) }}"  id="rt"></a></h5>                                    
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="card">
                                <div class="card-header text-center" style="background-color: #126e5127;">
                                    <b class="">En cours</b>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                      <h5 class="h2  fw-bold"><a href="{{ route('ouvrages.statut', ['statu' => 'EC']) }}"  id="ec"></a></h5>                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="card">
                                <div class="card-header text-center" style="background-color: #126e5127;">
                                    <b class="">Suspendu</b>
                                </div>
                                <div class="card-body">
                                  <div class="text-center">
                                    <h5 class="h2  fw-bold"><a href="{{ route('ouvrages.statut', ['statu' => 'SUSPENDU']) }}"  id="suspendu"></a></h5>                                    
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="card">
                                <div class="card-header text-center" style="background-color: #126e5127;">
                                    <b class="">Non demarré</b>
                                </div>
                                <div class="card-body">
                                  <div class="text-center">
                                    <h5 class="h2  fw-bold"><a href="{{ route('ouvrages.statut', ['statu' => 'NON_DEMARRE']) }}"  id="nondemarre"></a></h5>                                    
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <canvas class="mt-5" id="progressLineChart"></canvas>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Charger les données totales au démarrage
        loadData();

        // Gestionnaire de clic pour la recherche avec paramètres
        document.getElementById('searchbtn').addEventListener('click', function () {
            const filters = getFilters();
            loadData(filters);
        });
    });

    // Fonction pour récupérer les filtres depuis le formulaire
    function getFilters() {
        return {
            nom_reg: document.getElementById('nom_reg').value || '',
            nom_pref: document.getElementById('nom_pref').value || '',
            nom_comm: document.getElementById('nom_comm').value || '',
            nom_cant: document.getElementById('nom_cant').value || '',
            nom_projet: document.getElementById('nom_projet').value || '',
            nom_fin: document.getElementById('nom_fin').value || '',
            nom_site: document.getElementById('nom_site').value || '',
            nom_ouvrage: document.getElementById('nom_ouvrage').value || ''
        };
    }

    // Fonction pour charger les données
    function loadData(filters = {}) {
        const queryParams = new URLSearchParams(filters).toString();

        // Charger les statistiques
        fetch(`/ouvrages/statistiques?${queryParams}`)
            .then(response => response.json())
            .then(data => updateStatistics(data))
            .catch(error => console.error('Erreur lors du chargement des statistiques :', error));

        // Charger les données du graphe
        fetch(`/progress-data?${queryParams}`)
            .then(response => response.json())
            .then(data => updateChart(data))
            .catch(error => console.error('Erreur lors du chargement des données du graphe :', error));
    }

    // Met à jour les statistiques dans le DOM
    function updateStatistics(data) {
        const statsMapping = {
            rt: data.nbr_RT || 0,
            rp: data.nbr_RP || 0,
            rd: data.nbr_RD || 0,
            ec: data.nbr_EC || 0,
            suspendu: data.nbr_SUSPENDU || 0,
            nondemarre: data.nbr_NON_DEMARRE || 0
        };

        Object.entries(statsMapping).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) element.textContent = Number(value).toLocaleString();
        });

        prepareExcelDownload(statsMapping);
    }

    // Prépare les données pour l'export Excel
    function prepareExcelDownload(stats) {
        const dataa = [
            [
                "Nbre site recep définitive",
                "Nbre site recep provisoire",
                "Nbre site recep technique",
                "Nbre site recep en cours",
                "Nbre site recep suspendu",
                "Nbre site recep non demarre"
            ],
            [
                stats.rd,
                stats.rp,
                stats.rt,
                stats.ec,
                stats.suspendu,
                stats.nondemarre
            ]
        ];

        const excelButton = document.getElementById('excelButton');
        if (excelButton) {
            excelButton.onclick = () => generateExcel(dataa);
        }
    }

    // Génère et télécharge le fichier Excel
    function generateExcel(data) {
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(data);
        XLSX.utils.book_append_sheet(wb, ws, 'Etat_site');

        const today = new Date();
        const filename = `${today.getFullYear()}_${String(today.getMonth() + 1).padStart(2, '0')}_${String(today.getDate()).padStart(2, '0')}_Etat_site.xlsx`;
        XLSX.writeFile(wb, filename);
    }

    // Met à jour le graphique
    // Variable pour stocker l'instance du graphique
    let progressChart = null;

    function updateChart(data) {
        const progressCtx = document.getElementById('progressLineChart').getContext('2d');

        // Détruire l'ancien graphique s'il existe
        if (progressChart) {
            progressChart.destroy();
        }

        // Créer un nouveau graphique
        progressChart = new Chart(progressCtx, {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: data.datasets || []
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: context => `${context.raw}%`
                        }
                    }
                }
            }
        });
    }

</script>

@endsection
