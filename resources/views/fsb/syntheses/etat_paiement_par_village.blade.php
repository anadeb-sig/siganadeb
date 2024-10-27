@extends('layouts.app')

@section('content')
    <style>
        .gridjs-th{
            min-width: 150px;
            width: 200px!important;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Synthèse de paiements des BD par village</h1>
                <div class="file-export">
                    <button class="btn btn-outline-cyan btnform" id="excelButton">
                        <i class="fas fa-file-export"></i> &nbsp;Export
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
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
                                    <div class="col-xl-4 {{ $errors->has('nom_pref') ? 'has-error' : '' }}">
                                        <label for="nom_pref" class="control-label">Préfecture</label>
                                        <input class="form-control w-100" id="nom_pref" name="nom_pref" type="text" placeholder="exemple: MO..." />
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="commune_id" class="control-label">Commune</label>
                                        <input class="form-control w-100 majuscules" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: MO 2  ..." />
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-3">
                                        <label for="nom_cant" class="control-label">Canton</label>
                                        <input class="form-control w-100 majuscules" id="nom_cant" name="nom_cant" type="text" placeholder="exemple: TINDJASSE ..." />
                                    </div>
                                    <div class="col-xl-3 {{ $errors->has('nom_vill') ? 'has-error' : '' }}">
                                        <label for="nom_vill" class="control-label">Village /Quartier</label>
                                        <input class="form-control w-100" id="nom_vill" name="nom_vill" type="text" placeholder="exemple: TINDJA..." />
                                    </div>
                                    <div class="col-xl-3">
                                        <label for="rang">Financement</label>
                                        <input class="form-control" type="text" id='financement'  name="financement" placeholder="exemple: BM">
                                    </div>
                                    <div class="col-xl-3 modal-footer mt-4">
                                        <button id="btnEtatpaiement" type="button" class="btn btn-outline-primary recherche">
                                            <i class="fa fa-search"></i> &nbsp;Rechercher
                                        </button>
                                        &nbsp;&nbsp;
                                        <a href="{{ route('fsb_syntheses.par_village') }}" type="button" class="btn btn-outline-danger">
                                            <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div id="table_etatpaiement_par_village"></div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_paiement_village();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnEtatpaiement').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_pref = document.getElementById('nom_pref').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_cant = document.getElementById('nom_cant').value;
                let nom_vill = document.getElementById('nom_vill').value;
                let financement = document.getElementById('financement').value;
                rendtableau_paiement_village(nom_reg, nom_pref, nom_comm, nom_cant, nom_vill, financement);
                
            });
        });

        function loaddatauser_paiement_village(){
            let nom_reg = "";
            let nom_pref = "";
            let nom_comm = "";
            let nom_cant = "";
            let nom_vill = "";
            let financement = "";
            rendtableau_paiement_village(nom_reg,nom_pref, nom_comm,nom_cant,nom_vill, financement);
        } 
    </script>
@endsection