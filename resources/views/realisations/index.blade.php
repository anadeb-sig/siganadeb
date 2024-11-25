@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste des réalisations</h1>
                <div class="d-flex align-items-center">
                    <a href="javascript:void(0)" class="btn btn-outline-primary btnform ml-2" id="add_realisation">
                        <i class="fas fa-plus"></i> &nbsp;enregistrement
                    </a>
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
                                            <a href="{{ route('realisations.index') }}" type="button" class="btn btn-outline-danger">
                                                <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                            </a>
                                            &nbsp;&nbsp;
                                            <button id="btnrealisation" type="button" class="btn btn-outline-primary recherche">
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
                    <div id="table_realisation"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('realisations.create')
    @include('realisations.edit')
    @include('realisations.crud')
    @include('realisations.delete')

    <!-- gridjs js-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_realisation();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnrealisation').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_site = document.getElementById('nom_site').value;
                let nom_ouvrage = document.getElementById('nom_ouvrage').value;
                
                rendtableau_realisation(nom_reg, nom_comm, nom_site,nom_ouvrage);
                
            });
        });

        function loaddatauser_realisation(){
            
            let nom_reg = "";
            let nom_comm = "";
            let nom_site = "";
            let nom_ouvrage = "";
            rendtableau_realisation(nom_reg, nom_comm, nom_site,nom_ouvrage);
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
                let url = `/realisations/import-realisation?ouvrage_id=${ouvrage_id}`;

                // Créer un lien pour déclencher le téléchargement
                const a = document.createElement('a');
                a.href = url;
                a.download = 'format_realisation.csv';  // Nom du fichier CSV
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        });
    </script>
@endsection