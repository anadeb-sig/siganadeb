@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste des sites</h1>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-teal mr-2 btnform" type="button" id="telecharger_site">
                        <i class="fas fa-file-export"></i> &nbsp;Format en csv
                    </button>

                    <form method="POST" action="{{ Route('sites.import') }}" class="file-upload-form mr-2" enctype="multipart/form-data">
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

                    <div class="file-export">
                        <button class="btn btn-outline-cyan btnform" id="excelButton">
                            <i class="fas fa-file-export"></i> &nbsp;Export
                        </button>
                    </div>
                    @can('site-create')
                        <a href="javascript:void(0)" class="btn btn-outline-primary btnform ml-2" id="add_site">
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
                                    <div class="col-xl-4 modal-footer mt-4">
                                        <a href="{{ route('sites.index') }}" type="button" class="btn btn-outline-danger">
                                            <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                        </a>
                                        &nbsp;&nbsp;
                                        <button id="btnSite" type="button" class="btn btn-outline-primary recherche">
                                            <i class="fa fa-search"></i> &nbsp;Rechercher
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_site"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('sites.create')
    @include('sites.show')
    @include('sites.edit')
    @include('sites.delete')
    @include('sites.crud')
    @include('sites.telecharger_site')


    <script>
        function ajouterChamp() {
            var nombreChamps = document.querySelectorAll('.groupe-champs').length;
            if (nombreChamps < 11) {
                var nouveauChamp = '<div class="groupe-champs mt-4">';
                nouveauChamp += '<div class="row"><div class="col-xl-6">'+
                                    '<label for="nom_ouvrage" class="control-label">Nom de l\'ouvrage</label>'+
                                    '<input class="form-control majuscules" name="nom_ouvrage[]" type="text" id="nom_ouvrage" value="" min="0" max="150" placeholder="Entrer le nom de l\'ouvrage">'+
                                '</div>'+
                                '<div class="col-xl-6">'+
                                    '<label for="typeouvrage_id" class="control-label">Type de l\'ouvrage</label>'+
                                    '<select class="form-control" id="typeouvrage_id" name="typeouvrage_id[]">'+
                                        '<option value="" style="display: none;" disabled selected>Selectionner le type de l\'ouvrage</option>'+
                                        '@foreach ($Typeouvrages as $key => $Typeouvrage)'+
                                            '<option value="{{ $key }}">'+
                                                '{{ $Typeouvrage }}'+
                                            '</option>'+
                                        '@endforeach'+
                                    '</select>'+
                                '</div></div>';
                nouveauChamp += '<div class="row mt-4"><div class="col-xl-6">'+
                                    '<label for="projet_id" class="control-label">Projet / Programme</label>'+
                                    '<select class="form-control" id="projet_id" name="projet_id[]">'+
                                        '<option value="" style="display: none;" disabled selected>Selectionner le projet</option>'+
                                        '@foreach ($projets as $projet)'+
                                            '<option value="{{ $projet->id }}">'+
                                                '{{ $projet->name }}'+
                                            '</option>'+
                                        '@endforeach'+
                                    '</select>'+
                                '</div><div class="col-xl-6">'+
                                    '<label for="financement_id" class="control-label">Financement</label>'+
                                    '<select class="form-control" name="financement_id[]" value="">'+
                                        '<option value="" style="display: none;" disabled selected>Select financement</option>'+
                                        '@foreach ($Financements as $Financement)'+
                                            '<option value="{{ $Financement->id }}">'+
                                                '{{ $Financement->nom_fin }}'+
                                            '</option>'+
                                        '@endforeach'+
                                    '</select>'+
                                '</div></div>';
                nouveauChamp += '<div class="row mt-4">'+
                                    '<div class="col-xl-12">'+
                                        '<label for="descrip" class="control-label">Description</label>'+
                                        '<textarea class="form-control majuscules" name="descrip[]" type="text" id="descrip" rows="5" value="" maxlength="250" placeholder="Entrer la description"></textarea>'+
                                    '</div>'+
                                '</div>';
                nouveauChamp += '<div class="row mt-2"><div class="col-xl-10"></div><div class="col-xl-2">'+
                                    '<label for="descrip" class="control-label" style="color:#ffff;"> Titre </label>'+
                                    '<a class="btn btn-success" onclick="supprimerChamp(this)">Supprimer</a>'+
                                '</div>';
                nouveauChamp += '</div>';

                $('#container').append(nouveauChamp);
                
                // Désactiver le bouton si 3 champs sont ajoutés
                if (nombreChamps + 1 >= 11) {
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
            loaddatauser_site();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnSite').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_cant = document.getElementById('nom_cant').value;
                let nom_site = document.getElementById('nom_site').value;

                rendtableau_site(nom_reg, nom_comm, nom_cant,nom_site);
                
            });
        });

        function loaddatauser_site(){
            let nom_reg = "";
            let nom_comm = "";
            let nom_cant = "";
            let nom_site = "";
            rendtableau_site(nom_reg, nom_comm, nom_cant,nom_site);
        }        
    </script>

    <script>
        $(document).ready(function(){

            $(document).on('click', '.csvButton', function(e){
                e.preventDefault();

                let selectElement = document.getElementById("region_id");
                // Récupérer l'option sélectionnée
                let selectedOption = selectElement.options[selectElement.selectedIndex];
                // Récupérer la valeur de l'attribut
                let region_id = selectedOption.value;
                // Récupérer la Prefecture
                // Récupérer l'élément select
                let selectPrefecture = document.getElementById("prefecture_format");
                // Récupérer l'option sélectionnée
                let optionPrefecture = selectPrefecture.options[selectPrefecture.selectedIndex];
                // Récupérer la valeur de l'attribut data-nom
                let prefecture_id = optionPrefecture.value;
                // Récupérer la commune
                // Récupérer l'élément select
                let selectCommune = document.getElementById("commune_edit_site");
                // Récupérer l'option sélectionnée
                let optionCommune = selectCommune.options[selectCommune.selectedIndex];
                // Récupérer la valeur de l'attribut data-nom
                let commune_id = optionCommune.value;
                // Récupérer la canton
                // Récupérer l'élément select
                let selectCanton = document.getElementById("canton_comm_site");
                // Récupérer l'option sélectionnée
                let optionCanton = selectCanton.options[selectCanton.selectedIndex];
                // Récupérer la valeur de l'attribut data-nom
                let canton_id = optionCanton.value;

                // Construire l'URL avec les paramètres GET
                let url = `sites/format_csv?region_id=${region_id}&prefecture_id=${prefecture_id}&commune_id=${commune_id}&canton_id=${canton_id}`;

                // Créer un lien pour déclencher le téléchargement
                const a = document.createElement('a');
                a.href = url;
                a.download = 'format_csv_site.csv';  // Nom du fichier CSV
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        });
    </script>

                
@endsection