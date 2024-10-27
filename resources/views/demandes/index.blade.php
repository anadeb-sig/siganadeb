@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste des demandes de suspension des sites</h1>
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
                                    <div class="col-xl-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-xl-6 {{ $errors->has('date_fin') ? 'has-error' : '' }}">
                                                        <label for="code" class="control-label">Date début</label>
                                                        <input class="form-control" type="date" id="date_ordre_debut" placeholder="exemple: BM">
                                                    </div>
                                                    <div class="col-xl-6 {{ $errors->has('date_fin') ? 'has-error' : '' }}">
                                                        <label for="code" class="control-label">Date fin</label>
                                                        <input class="form-control w-100" id="date_ordre_fin" type="date" placeholder="exemple: batiment..." />
                                                    </div>
                                                    <label class="control-label mt-2 mb-0 text-center">Donner une période à la quelle le contrat a été signé</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-xl-6 {{ $errors->has('date_fin') ? 'has-error' : '' }}">
                                                        <label for="code" class="control-label">Date début</label>
                                                        <input class="form-control" type="date" id='date_demarre_debut'  placeholder="exemple: BM">
                                                    </div>
                                                    <div class="col-xl-6 {{ $errors->has('date_fin') ? 'has-error' : '' }}">
                                                        <label for="code" class="control-label">Date fin</label>
                                                        <input class="form-control w-100" id="date_demarre_fin" type="date" placeholder="exemple: batiment..." />
                                                    </div>
                                                    <label class="control-label mt-2 mb-0 text-center">Donner une période à la quelle l'exécution de l'ouvrage a démarré </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-6 {{ $errors->has('code') ? 'has-error' : '' }}">
                                        <input class="form-control w-100" id="code" name="code" type="text" placeholder="Code du contrat" />
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="modal-footer">
                                            <a href="{{ route('demandes.index') }}" type="button" class="btn btn-outline-danger">
                                                <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                            </a>
                                            &nbsp;&nbsp;
                                            <button id="btnDemande" type="button" class="btn btn-outline-primary recherche">
                                                <i class="fa fa-search"></i> &nbsp;Rechercher
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_demande"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>

    
    <!-- gridjs js-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_demande();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnDemande').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let titre = document.getElementById('titre').value;
                let user = document.getElementById('user').value;
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_site = document.getElementById('nom_site').value;
                let statu = document.getElementById('statu').value;
                let date_demarre_debut = document.getElementById('date_demarre_debut').value;
                let date_demarre_fin = document.getElementById('date_demarre_fin').value;

                rendtableau_demande(nom_reg, nom_comm, nom_site,statu,user,titre,date_demarre_debut,date_demarre_fin);
                
            });
        });

        function loaddatauser_demande(){
            
            let nom_reg = "";
            let nom_comm = "";
            let nom_site = "";
            let statu = "";
            let date_demarre_debut = "";
            let date_demarre_fin = "";
            let user = "";
            let titre = "";
            rendtableau_demande(nom_reg, nom_comm, nom_site,statu,user,titre,date_demarre_debut,date_demarre_fin);
        }        
    </script>
@endsection