@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <?php
          $site = new \App\Models\Site;
          $site_sta = $site->statistique_site();
    ?>
    <!-- Page Heading -->
    <div class="card">
        <div class="card-body">
            <div class="main-content">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>Tableau de bord</h1>
                        <div class="file-export">
                            <button class="btn btn-outline-cyan btnform" id="excelButton">
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
                                            <div class="col-xl-4">
                                                <label for="nom_reg" class="control-label">Région</label>
                                                <input class="form-control w-100 majuscules" id="nom_reg" name="nom_reg" type="text" placeholder="exemple: CENTRALE ..." />
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="commune_id" class="control-label">Commune</label>
                                                <input class="form-control w-100" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: badin 2..." />
                                            </div>
                                            <div class="col-xl-4 {{ $errors->has('nom_cant') ? 'has-error' : '' }}">
                                                <label for="nom_cant" class="control-label">Nom du canton</label>
                                                <input class="form-control w-100" id="nom_cant" name="nom_cant" type="text" placeholder="exemple: badin..." />
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-xl-4 {{ $errors->has('nom_site') ? 'has-error' : '' }}">
                                                <label for="nom_site" class="control-label">Site d'ouvrage</label>
                                                <input class="form-control w-100" id="nom_site" name="nom_site" type="text" placeholder="exemple: ....." />
                                            </div>
                                            <div class="col-xl-4 {{ $errors->has('nom_pr') ? 'has-error' : '' }}">
                                                
                                            </div>
                                            <div class="col-xl-4 modal-footer mt-4">
                                                <a href="{{ route('sites.index') }}" type="button" class="btn btn-outline-danger">
                                                    <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                                </a>
                                                &nbsp;&nbsp;
                                                <button id="" type="button" class="btn btn-outline-primary recherche">
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
                                  <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-center h2  fw-bold">{{ $site_sta->nbr_RT }}</h5>
                                    <a href="#"><i class="fa fa-eye"></i></a>
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
                                  <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-center h2  fw-bold">{{ $site_sta->nbr_RP }}</h5>
                                    <a href="#"><i class="fa fa-eye"></i></a>
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
                                  <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-center h2  fw-bold">{{ $site_sta->nbr_RT }}</h5>
                                    <a href="#"><i class="fa fa-eye"></i></a>
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
                                  <div class="d-flex justify-content-between align-items-center">
                                    
                                      <h5 class="card-title text-center h2  fw-bold">{{ $site_sta->nbr_EC }} </h5>
                                    
                                    <a href="#"><i class="fa fa-eye"></i></a>
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
                                  <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-center h2  fw-bold">{{ $site_sta->nbr_SUSPENDU }}</h5>
                                    <a href="#"><i class="fa fa-eye"></i></a>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="card">
                                <div class="card-header text-center" style="background-color: #126e5127;">
                                    <b class="">Contrat non signé</b>
                                </div>
                                <div class="card-body">
                                  <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-center h2  fw-bold">{{ $site_sta->nbr_CONTRAT_NON_SIGNE }}</h5>
                                    <a href="#"><i class="fa fa-eye"></i></a>
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
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil'],
        datasets: [{
          label: 'Revenus',
          data: [3000, 4000, 3200, 5200, 6000, 5800, 7200],
          borderColor: '#17a2b8',
          fill: false
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  </script>



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
