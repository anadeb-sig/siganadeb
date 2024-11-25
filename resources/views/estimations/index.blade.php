@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste des réalisations provisoirs</h1>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-teal mr-2 btnform" type="button" id="telecharger_estimation">
                        <i class="fas fa-file-export"></i> &nbsp;Format en csv
                    </button>

                    <form method="POST" action="{{ Route('estimations.import') }}" class="file-upload-form mr-2" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="file" class="form-control file-input" name="file" class="ml-2" accept=".csv" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-yellow btnform" type="submit">
                                    <i class="fas fa-file-import"></i> &nbsp;Import
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- <div class="file-export">
                        <button class="btn btn-outline-cyan btnform" id="excelButton">
                            <i class="fas fa-file-export"></i> &nbsp;Export
                        </button>
                    </div> -->
                    @can('entreprise-create')
                        <a href="javascript:void(0)" class="btn btn-outline-primary btnform ml-2" id="add_estimation">
                            <i class="fas fa-plus"></i> &nbsp;enregistrement
                        </a>
                    @endcan
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
                                    <div class="col-xl-4">
                                        <label for="commune_id" class="control-label">Commune</label>
                                        <input class="form-control w-100 majuscules" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: MO 2  ..." />
                                    </div>
                                    <div class="col-xl-4 {{ $errors->has('nom_site') ? 'has-error' : '' }}">
                                        <label for="nom_site" class="control-label">Site</label>
                                        <input class="form-control w-100" id="nom_site" name="nom_site" type="text" placeholder="exemple: bado..." />
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-xl-9 {{ $errors->has('nom_ouvrage') ? 'has-error' : '' }}">
                                        <label for="nom_ouvrage" class="control-label">Nom ouvrage</label>
                                        <input class="form-control w-100" id="nom_ouvrage" name="nom_ouvrage" type="text" placeholder="exemple: batiment scolaire..." />
                                    </div>                                    
                                    <div class="col-xl-3">
                                        <div class="modal-footer mt-4">
                                            <a href="{{ route('estimations.index') }}" type="button" class="btn btn-outline-danger">
                                                <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                            </a>
                                            &nbsp;&nbsp;
                                            <button id="btnEstimation" type="button" class="btn btn-outline-primary recherche">
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
                    <div id="table_estimation"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('estimations.create')
    @include('estimations.edit')
    @include('estimations.crud')
    @include('estimations.delete')
    @include('estimations.telecharger_format')

    <!-- gridjs js-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_estimation();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnEstimation').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_site = document.getElementById('nom_site').value;
                let nom_ouvrage = document.getElementById('nom_ouvrage').value;
                
                rendtableau_estimation(nom_reg, nom_comm, nom_site,nom_ouvrage);
                
            });
        });

        function loaddatauser_estimation(){
            
            let nom_reg = "";
            let nom_comm = "";
            let nom_site = "";
            let nom_ouvrage = "";
            rendtableau_estimation(nom_reg, nom_comm, nom_site,nom_ouvrage);
        }
        
        $(document).ready(function(){

            $(document).on('click', '.csvButton', function(e){
                e.preventDefault();

                let selectElement = document.getElementById("ouvrage_comm");
                // Récupérer l'option sélectionnée
                let selectedOption = selectElement.options[selectElement.selectedIndex];
                // Récupérer la valeur de l'attribut
                let ouvrage_id = selectedOption.value;
                // Construire l'URL avec les paramètres GET
                let url = `/estimations/import-estimation?ouvrage_id=${ouvrage_id}`;

                // Créer un lien pour déclencher le téléchargement
                const a = document.createElement('a');
                a.href = url;
                a.download = 'format_estimation.csv';  // Nom du fichier CSV
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        });
    </script>
@endsection