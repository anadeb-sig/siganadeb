@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste de suivis</h1>
                @can('suivi-create')
                    <a href="javascript:void(0)" class="btn btn-outline-primary btnform" id="add_suivi">
                        <i class="fas fa-plus"></i> &nbsp;enregistrement
                    </a>
                @endcan
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
                                        <input class="form-control w-100" id="nom_site" name="nom_site" type="text" placeholder="exemple: batiment scolaire..." />
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-9">
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
                                    <div class="col-xl-3 modal-footer mt-4">
                                        <button id="btnSuivi" type="button" class="btn btn-outline-primary recherche">
                                            <i class="fa fa-search"></i> &nbsp;Rechercher
                                        </button>
                                        &nbsp;&nbsp;
                                        <a href="{{ route('suivis.index') }}" type="button" class="btn btn-outline-danger">
                                            <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_suivi"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('suivis.create')
    @include('suivis.show')
    @include('suivis.edit')
    @include('suivis.delete')
    @include('suivis.crud')

    
    <script>
        function ajouterChamp() {
            var nombreChamps = document.querySelectorAll('.groupe-champs').length;
        
            if (nombreChamps < 2) {
                var nouveauChamp = '<div class="row groupe-champs mt-3">';
                nouveauChamp += '<div class="col-xl-5">'+
                                    '<label for="photo" class="control-label">Photo de l\'ouvrage</label>'+
                                    '<input class="form-control photo" type="file" name="photo[]" id="photos">'+
                                '</div>';
                nouveauChamp += '<div class="col-xl-5">'+
                                    '<label for="descrip" class="control-label">Description</label>'+
                                    '<input class="form-control majuscules descrip" type="text" name="descrip[]" id="descrip">'+
                                '</div>';
                nouveauChamp += '<div class="col-xl-2">'+
                                    '<label for="descrip" class="control-label" style="color:#ffff;"> Titre </label>'+
                                    '<a class="btn btn-success" onclick="supprimerChamp(this)">Supprimer</a>'+
                                '</div>';
                nouveauChamp += '</div>';

                $('#container').append(nouveauChamp);
                
                // Désactiver le bouton si 3 champs sont ajoutés
                if (nombreChamps + 1 >= 2) {
                    document.getElementById('btn-ajouter-champ').disabled = true;
                }
            }
        }

        function supprimerChamp(element) {
            $(element).closest('.groupe-champs').remove();

            // Réactiver le bouton si le nombre de champs est inférieur à 3
            if (document.querySelectorAll('.groupe-champs').length < 2) {
                document.getElementById('btn-ajouter-champ').disabled = false;
            }
        }
    </script>

    <!-- gridjs js-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_suivi();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnSuivi').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_site = document.getElementById('nom_site').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let date_demarre_debut = document.getElementById('date_demarre_debut').value;
                let date_demarre_fin = document.getElementById('date_demarre_fin').value;

                rendtableau_suivi(nom_reg, nom_comm, nom_site,date_demarre_debut,date_demarre_fin);
                
            });
        });

        function loaddatauser_suivi(){
            let nom_reg = "";
            let nom_comm = "";
            let nom_site = "";
            let date_demarre_debut = "";
            let date_demarre_fin = "";
            rendtableau_suivi(nom_reg, nom_comm, nom_site,date_demarre_debut,date_demarre_fin);
        }        
    </script>

@endsection