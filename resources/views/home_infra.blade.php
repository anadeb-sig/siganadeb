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
                                            <div class="col-xl-3 {{ $errors->has('nom_fin') ? 'has-error' : '' }}">
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
                                            <div class="col-xl-3 modal-footer mt-4">
                                                <a href="{{ route('sites.index') }}" type="button" class="btn btn-outline-danger">
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



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatatotal();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('searchbtn').addEventListener('click', function() {
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_pref = document.getElementById('nom_pref').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_cant = document.getElementById('nom_cant').value;
                let nom_fin = document.getElementById('nom_fin').value;
                let nom_site = document.getElementById('nom_site').value;
                let nom_projet = document.getElementById('nom_projet').value;
                fetch(`/ouvrages/statistiques?nom_reg=${nom_reg}&nom_pref=${nom_pref}&nom_comm=${nom_comm}&nom_cant=${nom_cant}&nom_projet=${nom_projet}&nom_fin=${nom_fin}&nom_site=${nom_site}`)
                .then(response => response.json())
                .then(data => {
                    // Mise à jour des éléments DOM avec les valeurs formatées
                    let total_rt = document.getElementById('rt');
                    total_rt.textContent = Number(data.nbr_RT || 0).toLocaleString();

                    let total_rp = document.getElementById('rp');
                    total_rp.textContent = Number(data.nbr_RP || 0).toLocaleString();

                    let total_rd = document.getElementById('rd');
                    total_rd.textContent = Number(data.nbr_RD || 0).toLocaleString();

                    let total_ec = document.getElementById('ec');
                    total_ec.textContent = Number(data.nbr_EC || 0).toLocaleString();

                    let total_suspendu = document.getElementById('suspendu');
                    total_suspendu.textContent = Number(data.nbr_SUSPENDU || 0).toLocaleString();

                    let total_nondemarre = document.getElementById('nondemarre');
                    total_nondemarre.textContent = Number(data.nbr_NON_DEMARRE || 0).toLocaleString();

                    // Préparation des données pour le fichier Excel
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
                            data.nbr_RD || 0,
                            data.nbr_RP || 0,
                            data.nbr_RT || 0,
                            data.nbr_EC || 0,
                            data.nbr_SUSPENDU || 0,
                            data.nbr_NON_DEMARRE || 0
                        ]
                    ];

                    let excelButton = document.getElementById('excelButton');
                    if (excelButton) {
                        // Fonction de génération Excel au clic
                        excelButton.addEventListener('click', () => {
                            const wb = XLSX.utils.book_new();
                            const ws = XLSX.utils.aoa_to_sheet(dataa);

                            XLSX.utils.book_append_sheet(wb, ws, 'Etat_site');

                            // Obtenir la date actuelle pour le nom du fichier
                            let today = new Date();
                            let yyyy = today.getFullYear();
                            let mm = String(today.getMonth() + 1).padStart(2, '0');
                            let dd = String(today.getDate()).padStart(2, '0');

                            // Créer le nom du fichier au format aaaa-mm-jj
                            let filename = `${yyyy}_${mm}_${dd}_Etat_site.xlsx`;
                            XLSX.writeFile(wb, filename);
                        });
                    }
                })
                .catch(error => {
                    console.error("Erreur lors de la récupération des données :", error);
                });
            });
        });

        function loaddatatotal(){
            let nom_reg = "";
            let nom_pref = "";
            let nom_comm = "";
            let nom_cant = "";
            let nom_projet = "";
            let nom_fin = "";
            let nom_site = "";
            fetch(`/ouvrages/statistiques?nom_reg=${nom_reg}&nom_pref=${nom_pref}&nom_comm=${nom_comm}&nom_cant=${nom_cant}&nom_projet=${nom_projet}&nom_fin=${nom_fin}&nom_site=${nom_site}`)
            .then(response => response.json())
            .then(data => {
                // Mise à jour des éléments DOM avec les valeurs formatées
                let total_rt = document.getElementById('rt');
                total_rt.textContent = Number(data.nbr_RT || 0).toLocaleString();

                let total_rp = document.getElementById('rp');
                total_rp.textContent = Number(data.nbr_RP || 0).toLocaleString();

                let total_rd = document.getElementById('rd');
                total_rd.textContent = Number(data.nbr_RD || 0).toLocaleString();

                let total_ec = document.getElementById('ec');
                total_ec.textContent = Number(data.nbr_EC || 0).toLocaleString();

                let total_suspendu = document.getElementById('suspendu');
                total_suspendu.textContent = Number(data.nbr_SUSPENDU || 0).toLocaleString();

                let total_nondemarre = document.getElementById('nondemarre');
                total_nondemarre.textContent = Number(data.nbr_NON_DEMARRE || 0).toLocaleString();

                // Préparation des données pour le fichier Excel
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
                        data.nbr_RD || 0,
                        data.nbr_RP || 0,
                        data.nbr_RT || 0,
                        data.nbr_EC || 0,
                        data.nbr_SUSPENDU || 0,
                        data.nbr_NON_DEMARRE || 0
                    ]
                ];

                let excelButton = document.getElementById('excelButton');
                if (excelButton) {
                    // Fonction de génération Excel au clic
                    excelButton.addEventListener('click', () => {
                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.aoa_to_sheet(dataa);

                        XLSX.utils.book_append_sheet(wb, ws, 'Etat_site');

                        // Obtenir la date actuelle pour le nom du fichier
                        let today = new Date();
                        let yyyy = today.getFullYear();
                        let mm = String(today.getMonth() + 1).padStart(2, '0');
                        let dd = String(today.getDate()).padStart(2, '0');

                        // Créer le nom du fichier au format aaaa-mm-jj
                        let filename = `${yyyy}_${mm}_${dd}_Etat_site.xlsx`;
                        XLSX.writeFile(wb, filename);
                    });
                }
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des données :", error);
            });

        }
    </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
  <!--  -->
  <script>
    const progressCtx = document.getElementById('progressLineChart').getContext('2d');
    const progressLineChart = new Chart(progressCtx, {
      type: 'line',
      data: {
        labels: ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4', 'Semaine 5', 'Semaine 6'],
        datasets: [{
          label: 'Avancement prévu (%)',
          data: [10, 20, 30, 50, 70, 100],
          borderColor: 'rgba(75, 192, 192, 1)',
          fill: false
        }, {
          label: 'Avancement réel (%)',
          data: [5, 15, 25, 40, 60, 85],
          borderColor: 'rgba(255, 99, 132, 1)',
          fill: false
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            max: 100
          }
        }
      }
    });
  </script>

@endsection
