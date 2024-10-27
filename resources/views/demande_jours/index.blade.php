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
                                    <div class="col-xl-3">
                                        <label for="nom_reg" class="control-label">Région</label>
                                        <input class="form-control w-100 majuscules" id="nom_reg" name="nom_reg" type="text" placeholder="exemple: CENTRALE ..." />
                                    </div>
                                    <div class="col-xl-3">
                                        <label for="commune_id" class="control-label">Commune</label>
                                        <input class="form-control w-100 majuscules" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: MO 2  ..." />
                                    </div>
                                    <div class="col-xl-3 {{ $errors->has('nom_site') ? 'has-error' : '' }}">
                                        <label for="nom_site" class="control-label">Site</label>
                                        <input class="form-control w-100" id="nom_site" name="nom_site" type="text" placeholder="exemple: bado..." />
                                    </div>
                                    <div class="col-xl-3 {{ $errors->has('statu') ? 'has-error' : '' }}">
                                        <label for="projet_id" class="control-label">Status</label>
                                        <select class="form-control" id="statu" name="statu">
                                            <option value="" style="display: none;" disabled selected>Selectionner le status</option>
                                            <option value="En attente">En attente</option>
                                            <option value="Approuvé">Approuvé</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-xl-3 {{ $errors->has('code') ? 'has-error' : '' }}">
                                        <label for="code" class="control-label">Code du contrat</label>
                                        <input class="form-control w-100" id="code" name="code" type="text" placeholder="Code du contrat" />
                                    </div>
                                    <div class="col-xl-3 {{ $errors->has('user') ? 'has-error' : '' }}">
                                        <label for="user" class="control-label">Non et prénoms du demandeur</label>
                                        <input class="form-control w-100" id="user" name="user" type="text" placeholder="Exemple: Yaya Koi" />
                                    </div>
                                    <div class="col-xl-3 {{ $errors->has('titre') ? 'has-error' : '' }}">
                                        <label for="titre" class="control-label">Titre de la demande</label>
                                        <input class="form-control w-100" id="titre" name="titre" type="text" placeholder="Exemple: ........." />
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="modal-footer mt-4">
                                            <a href="{{ route('demandejours.index') }}" type="button" class="btn btn-outline-danger">
                                                <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                            </a>
                                            &nbsp;&nbsp;
                                            <button id="btnDemandejour" type="button" class="btn btn-outline-primary recherche">
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
                    <div id="table_demande_jour"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>

    
    <!-- gridjs js-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_demande_jour();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnDemandejour').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let titre = document.getElementById('titre').value;
                let user = document.getElementById('user').value;
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_site = document.getElementById('nom_site').value;
                let statu = document.getElementById('statu').value;
                let code = document.getElementById('code').value;
                
                rendtableau_demande_jour(nom_reg, nom_comm, nom_site,statu,user,titre,code);
                
            });
        });

        function loaddatauser_demande_jour(){
            
            let nom_reg = "";
            let nom_comm = "";
            let nom_site = "";
            let statu = "";
            let code = "";
            let user = "";
            let titre = "";
            rendtableau_demande_jour(nom_reg, nom_comm, nom_site,statu,user,titre,code);
        }        
    </script>
@endsection