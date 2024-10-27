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
                <h1 class="h3 mb-0 text-gray-800">Liste des bénéficiaires</h1>
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
                                <div class="row">
                                    <div class="col-xl-3 {{ $errors->has('nom') ? 'has-error' : '' }}">
                                        <label for="nom" class="control-label">Nom du bénéficiaire</label>
                                        <input class="form-control w-100" id="nom" name="nom" type="text" placeholder="exemple: Koffi..." />
                                    </div>
                                    <div class="col-xl-4 {{ $errors->has('prenom') ? 'has-error' : '' }}">
                                        <label for="prenom" class="control-label">Prénom du bénéficiaire</label>
                                        <input class="form-control w-100" id="prenom" name="prenom" type="text" placeholder="exemple: Yaya..." />
                                    </div>
                                    <div class="col-xl-2">
                                        <label for="Sexe"  class="control-label">Sexe</label>
                                        <select class="form-control w-100" name="sexe" id="sexe" value="">
                                            <option value="">Sexe du chef de ménage</option>
                                            <option value="H">Homme</option>
                                            <option value="F">Femme</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-3 {{ $errors->has('telephone') ? 'has-error' : '' }}">
                                        <label for="telephone" class="control-label">Numéro de téléphone</label>
                                        <input class="form-control w-100" id="telephone" name="telephone" type="text" placeholder="exemple: 99988720..." />
                                    </div>
                                </div> 
                                <div class="row mt-4">
                                    <div class="col-xl-4">
                                        <label for="nom_reg" class="control-label">Région</label>
                                        <input class="form-control w-100 majuscules" id="nom_reg" name="nom_reg" type="text" placeholder="exemple: KARA..." />
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="commune_id" class="control-label">Commune</label>
                                        <input class="form-control w-100 majuscules" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: KOZAH 4..." />
                                    </div>
                                    <div class="col-xl-4 {{ $errors->has('nom_vill') ? 'has-error' : '' }}">
                                        <label for="nom_vill" class="control-label">Village /Quartier</label>
                                        <input class="form-control w-100" id="nom_vill" name="nom_vill" type="text" placeholder="exemple: Etat..." />
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-4 {{ $errors->has('nom_fin') ? 'has-error' : '' }}">
                                        <label for="nom_fin" class="control-label">Type de financement</label>
                                        <select class="form-control w-100" name="projet_id" id="projet_id" value="">
                                            <option value="">Rechercher par type de financement</option>
                                            <option value="1">FSB NOVISSI</option>
                                            <option value="3">FSB NOVISSI MARITIME</option>
                                            <option value="4">FSB NOVISSI EXTENTION</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="id_menage">Identifiant du ménage</label>
                                        <input class="form-control" type="text" id="idMenage" name="id" placeholder="exemple: 10258934678">
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="rang">Rank PMT</label>
                                        <input class="form-control" type="number" id='rang'  name="rang" placeholder="exemple: 81">
                                    </div>
                                </div>    
                                <div class="row mt-4">
                                    <div class="col-xl-3 {{ $errors->has('date_naiss') ? 'has-error' : '' }}">
                                        <label for="date_naiss" class="control-label">Date de naissance du BD</label>
                                        <input class="form-control w-100" id="date_naiss" name="date_naiss" type="date" placeholder="" />
                                    </div>
                                    <div class="col-xl-2">
                                        <label for="Sexe"  class="control-label">Type de la pièce</label>
                                        <input class="form-control w-100" name="type_carte" id="type_carte" placeholder="Exple:CE, RID, ...">
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="card_number">Numéro de la carte d'identité</label>
                                        <input class="form-control" type="text" id='card_number'  name="card_number" placeholder="Exple: 5-106-02-03-06-01-01-00213">
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="modal-footer mt-4">
                                            <button id="btnMenage" type="button" class="btn btn-outline-primary recherche">
                                                <i class="fa fa-search"></i> &nbsp;Rechercher
                                            </button>
                                            &nbsp;&nbsp;
                                            <a href="{{ route('beneficiaires.index') }}" type="button" class="btn btn-outline-danger">
                                                <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                            </a>
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
                    <div id="table_beneficiaire"></div>
                </div>
            </div>
        </div>
    </div>

    @include('fsb.beneficiaires.show')
    @include('fsb.beneficiaires.crud')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_beneficiaire();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnMenage').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_vill = document.getElementById('nom_vill').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let projet_id = document.getElementById('projet_id').value;
                let id = document.getElementById('idMenage').value;
                let rang = document.getElementById('rang').value;
                let nom = document.getElementById('nom').value;
                let prenom = document.getElementById('prenom').value;
                let sexe = document.getElementById('sexe').value;
                let type_carte = document.getElementById('type_carte').value;
                let card_number = document.getElementById('card_number').value;
                let telephone = document.getElementById('telephone').value;
                let date_naiss = document.getElementById('date_naiss').value;
                rendtableau_beneficiaire(nom_reg, nom_comm, nom_vill, id,projet_id, nom,prenom, rang, telephone, sexe, type_carte, card_number,date_naiss);
                
            });
        });

        function loaddatauser_beneficiaire(){
            let nom_reg = "";
            let nom_vill = "";
            let nom_comm = "";
            let projet_id = "";
            let id = "";
            let rang = "";
            let nom = "";
            let prenom = "";
            let sexe = "";
            let telephone = "";
            let type_carte = "";
            let card_number = "";
            let date_naiss = "";
            rendtableau_beneficiaire(nom_reg, nom_comm, nom_vill, id,projet_id, nom,prenom, rang, telephone, sexe, type_carte, card_number,date_naiss);
        }        
    </script>
@endsection