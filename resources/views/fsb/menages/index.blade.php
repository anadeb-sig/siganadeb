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
                <h1 class="h3 mb-0 text-gray-800">Liste des ménages</h1>
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
                                <div class="row mb-4">
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
                                <div class="row">
                                    <div class="col-xl-4 {{ $errors->has('nom_fin') ? 'has-error' : '' }}">
                                        <label for="nom_fin" class="control-label">Type de financement</label>
                                        <select class="form-control w-100" name="nature_projet" id="nature_projet" value="">
                                            <option value="">Rechercher par type de financement</option>
                                            <option value="FSB_NOVISSI">FSB NOVISSI</option>
                                            <option value="FSB_NOVISSI_MARITIME">FSB NOVISSI MARITIME</option>
                                            <option value="FSB_NOVISSI_EXT">FSB NOVISSI EXTENTION</option>
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
                                    <div class="col-xl-4 {{ $errors->has('hhead') ? 'has-error' : '' }}">
                                        <label for="hhead" class="control-label">Nom complet du chef de ménage</label>
                                        <input class="form-control w-100" id="hhead" name="hhead" type="text" placeholder="exemple: Koffi Yaya..." />
                                    </div>
                                    <div class="col-xl-2">
                                        <label for="Sexe"  class="control-label">Sexe</label>
                                        <select class="form-control w-100" name="sexe" id="sexe" value="">
                                            <option value="">Sexe du chef</option>
                                            <option value="H">Homme</option>
                                            <option value="F">Femme</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-3">
                                        <label for="phone_number">Numéro de téléphone</label>
                                        <input class="form-control" type="text" id='phone_member1'  name="phone_number" placeholder="Exple: 90654796">
                                    </div>
                                    <div class="col-xl-3">
                                        <div class="modal-footer mt-4">
                                            <button id="btnMenage" type="button" class="btn btn-outline-primary recherche">
                                                <i class="fa fa-search"></i> &nbsp;Rechercher
                                            </button>
                                            &nbsp;&nbsp;
                                            <a href="{{ route('menages.index') }}" type="button" class="btn btn-outline-danger">
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
                    <div id="table_menage"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="membres_liste_modal" tabindex="-1" aria-labelledby="show_financement_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="table_liste_membre"></table>
                </div>
            </div>
        </div>
    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_menage();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnMenage').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_vill = document.getElementById('nom_vill').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nature_projet = document.getElementById('nature_projet').value;
                let id = document.getElementById('idMenage').value;
                let rang = document.getElementById('rang').value;
                let hhead = document.getElementById('hhead').value;
                let sexe = document.getElementById('sexe').value;
                let phone_member1 = document.getElementById('phone_member1').value;
                rendtableau_menage(nom_reg, nom_comm, nom_vill, id, nature_projet, rang, hhead, sexe, phone_member1);
                
            });
        });

        function loaddatauser_menage(){
        let nom_reg = "";
            let nom_vill = "";
            let nom_comm = "";
            let nature_projet = "";
            let id = "";
            let rang = "";
            let hhead = "";
            let sexe = "";
            let phone_member1 = "";
            rendtableau_menage(nom_reg, nom_comm, nom_vill, id, nature_projet, rang, hhead, sexe, phone_member1);
        }        
    </script>
@endsection