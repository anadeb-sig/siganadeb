@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-xl-12">
                <div class="accordion mb-4" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size: 1.2em;">
                                #Filtrer les photos
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <form method="get" action="" accept-charset="UTF-8">    
                                    <div class="row mt-4">
                                        <div class="col-xl-3">
                                            <label for="region_id" class="control-label">Région</label>
                                            <select class="form-control nom_reg" id="nom_reg" name="nom_reg" required="true">
                                                <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->nom_reg }}">
                                                        {{ $region->nom_reg }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="commune_id" class="control-label">Commune</label>
                                            <input class="form-control w-100 majuscules" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: MO 2  ..." />
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="nom_site" class="control-label">Site</label>
                                            <input class="form-control w-100" id="nom_site" name="nom_site" type="text" placeholder="exemple: Site de bado..." />
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="type_ouvrag" class="control-label">Type d'ouvrage</label>
                                            <select class="form-control nom_type" id="nom_type" name="nom_type" required="true">
                                                <option value="" style="display: none;" disabled selected>Selectionner le type d'ouvrage</option>
                                                @foreach ($typeouvrages as $typeouvrage)
                                                    <option value="{{ $typeouvrage->nom_type }}">
                                                        {{ $typeouvrage->nom_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-xl-4 {{ $errors->has('nom_ouv') ? 'has-error' : '' }}">
                                            <label for="nom_ouv" class="control-label">Nom de l'ouvrage</label>
                                            <input class="form-control w-100" id="nom_ouv" name="nom_ouv" type="text" placeholder="exemple: batiment scolaire..." />
                                        </div>
                                        <div class="col-xl-4 {{ $errors->has('nom_pr') ? 'has-error' : '' }}">
                                            <label for="projetOuvrage" class="control-label">{{ __('Projet / Programme') }}</label>
                                            <select class="form-control" id="projetOuvrage" name="projetOuvrage" required="true">
                                                <option value="" style="display: none;" disabled selected>{{ __('Selectionner le projet') }}</option>
                                                @foreach ($projets as $projet)
                                                    <option value="{{ $projet->name }}">
                                                        {{ $projet->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="rang">Financement</label>
                                            <input class="form-control" type="text" id='nom_fin'  name="nom_fin" placeholder="exemple: BM">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-xl-9">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xl-6 {{ $errors->has('date_fin') ? 'has-error' : '' }}">
                                                            <label for="date_demarre_debut" class="control-label">Date début</label>
                                                            <input class="form-control" type="date" id="date_demarre_debut" name="date_demarre_debut"  placeholder="exemple: BM">
                                                        </div>
                                                        <div class="col-xl-6 {{ $errors->has('date_fin') ? 'has-error' : '' }}">
                                                            <label for="date_demarre_fin" class="control-label">Date fin</label>
                                                            <input class="form-control w-100" id="date_demarre_fin" name="date_demarre_fin" type="date" placeholder="exemple: batiment..." />
                                                        </div>
                                                        <label class="control-label mt-2 mb-0 text-center">Donner une période du suivi de l'ouvrage</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 modal-footer mt-4">
                                            <a href="{{ route('suivis.galerie') }}" type="button" class="btn btn-outline-danger">
                                                <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                            </a>
                                            &nbsp;&nbsp;
                                            <button type="button" class="btn btn-outline-primary recherche" id="btnGalerie">
                                                <i class="fa fa-search"></i> &nbsp;Filtrer
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="All" role="tabpanel" aria-labelledby="All-tab">
                            <div class="tab-content" id="table_galerie">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('galeries.create')
    @include('galeries.crud')
    @include('galeries.delete')

        <!-- GLightbox JS -->
        <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_galerie();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnGalerie').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_projet = document.getElementById('projetOuvrage').value;
                let nom_type = document.getElementById('nom_type').value;
                let nom_fin = document.getElementById('nom_fin').value;
                let nom_site = document.getElementById('nom_site').value;
                // let code = document.getElementById('code').value;
                let date_demarre_debut = document.getElementById('date_demarre_debut').value;
                let date_demarre_fin = document.getElementById('date_demarre_fin').value;
                let nom_ouvrage = document.getElementById('nom_ouv').value;

                rendtableau_galerie(nom_reg, nom_comm, nom_ouvrage, nom_projet, nom_type, nom_fin,date_demarre_debut,date_demarre_fin,nom_site);
                
            });
        });

        function loaddatauser_galerie(){
            let nom_reg = "";
            let nom_ouvrage = "";
            let nom_comm = "";
            let nom_projet = "";
            let nom_fin = "";
            let date_demarre_debut = "";
            let date_demarre_fin = "";
            let nom_type = "";
            let nom_site = "";
            rendtableau_galerie(nom_reg, nom_comm, nom_ouvrage, nom_projet, nom_type, nom_fin,date_demarre_debut,date_demarre_fin,nom_site);
        }        
    </script>

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
@endsection