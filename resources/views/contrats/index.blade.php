@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste de contrats</h1>
                <div class="d-flex align-items-center">
                    <div class="file-export">
                        <button class="btn btn-outline-cyan btnform">
                            <i class="fas fa-file-export"></i> &nbsp;Export
                        </button>
                    </div>

                    @can('contrat-create')
                        <a href="javascript:void(0)" class="btn btn-outline-primary btnform ml-2" id="add_contrat">
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
                                            <button id="btnContrat" type="button" class="btn btn-outline-primary recherche">
                                                <i class="fa fa-search"></i> &nbsp;Rechercher
                                            </button>
                                            &nbsp;&nbsp;
                                            <a href="{{ route('contrats.index') }}" type="button" class="btn btn-outline-danger">
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
                    <div id="table_contrat"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('contrats.demande_jour')
    @include('contrats.create')
    @include('contrats.edit')
    @include('contrats.delete')
    @include('contrats.crud')

    
    <!-- gridjs js-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_contrat();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnContrat').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let code = document.getElementById('code').value;
                let date_ordre_debut = document.getElementById('date_ordre_debut').value;
                let date_ordre_fin = document.getElementById('date_ordre_fin').value;
                let date_demarre_debut = document.getElementById('date_demarre_debut').value;
                let date_demarre_fin = document.getElementById('date_demarre_fin').value;

                rendtableau_contrat(code,date_ordre_debut,date_ordre_fin,date_demarre_debut,date_demarre_fin);
                
            });
        });

        function loaddatauser_contrat(){
            let date_ordre_debut = "";
            let date_ordre_fin = "";
            let date_demarre_debut = "";
            let date_demarre_fin = "";
            let code = "";
            rendtableau_contrat(code,date_ordre_debut,date_ordre_fin,date_demarre_debut,date_demarre_fin);
        }        
    </script>


    <script>
        function ajouterChamp() {
            var nombreChamps = document.querySelectorAll('.groupe-champs').length;
            if (nombreChamps < 11) {
                var nouveauChamp = '<div class="groupe-champs">';
                nouveauChamp += '<div class="row">';
                nouveauChamp += '<div class="col-xl-6">'+
                        '<label for="region_id" class="control-label">Région</label>'+
                        '<select class="form-control region-comm" id="region_comm'+nombreChamps+'" required="true">'+
                            '<option value="" style="display: none;" disabled selected>Selectionner la région</option>'+
                            '@foreach ($regions as $region)'+
                                '<option value="{{ $region->id }}">'+
                                    '{{ $region->nom_reg }}'+
                                '</option>'+
                            '@endforeach'+
                        '</select>'+
                    '</div>'+
                    '<div class="col-xl-6">'+
                        '<label for="commune_id" class="control-label">Commune</label>'+
                        '<select class="form-control commune-comm" id="commune_comm'+nombreChamps+'" required="true" disabled>'+
                            '<option value="" disabled selected>Selectionner la commune</option>'+
                        '</select>'+
                    '</div>'+
                '</div>';

                nouveauChamp += '<div class="row mt-3">'+
                    '<div class="col-xl-12">'+
                        '<label for="site_id" class="control-label">Site à construire</label>'+
                        '<select class="form-control site-comm" id="site_comm'+nombreChamps+'" name="site_id[]" disabled required="true">'+
                            '<option value="" disabled selected>Sélectionnez le site à construire</option>'+
                        '</select>'+
                    '</div>'+
                '</div>';

                nouveauChamp += '<div class="row mt-4">'+
                    '<div class="col-sm-12 mb-sm-0" id="ouvrages_site'+nombreChamps+'">'+
                    '</div>'+
                '</div>';
                nouveauChamp += '<div class="row mt-2"><div class="col-xl-10"></div><div class="col-xl-2">'+
                                    '<a class="btn btn-outline-danger btnform" onclick="supprimerChamp(this)">Supprimer</a>'+
                                '</div></div>';
                $('#container').append(nouveauChamp);
                
                // Désactiver le bouton si 11 champs sont ajoutés
                if (nombreChamps + 1 >= 11) {
                    document.getElementById('btn-ajouter-champ').disabled = true;
                }

                // Ajout de l'événement de changement pour les nouvelles régions ajoutées
                $('#region_comm' + nombreChamps).change(function() {
                    let value = $(this).val();
                    let commune_comm = $('#commune_comm' + nombreChamps);  
                    commune_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
                    if (value) {
                        $.ajax({
                            url: '/communes/' + value,
                            method: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                commune_comm.html('<option value="">Sélectionnez une option</option>');          
                                $.each(data, function(index, option) {
                                    commune_comm.append('<option value="' + option.id + '">' + option.nom_comm + '</option>');
                                });          
                                commune_comm.prop('disabled', false);
                            },
                            error: function() {
                                commune_comm.html('<option value="">Erreur de chargement</option>');
                            }
                        });
                    } else {
                        commune_comm.html('<option value="">Sélectionnez une option</option>');
                        commune_comm.prop('disabled', true);
                    }
                });

                //Sites par rcommune
                $('#commune_comm' + nombreChamps).change(function() {
                    let value = $(this).val();
                    let selectedOption = $(this).find('option:selected');
                    let url = '/sites/site_commune/' + value;
                    let site_comm = $('#site_comm' + nombreChamps);    
                    site_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
                    if (url) {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            site_comm.html('<option value="">Sélectionnez une option</option>');          
                        $.each(data, function(index, option) {
                            site_comm.append('<option value="' + option.id + '" data-vill="' + option.nom_site + '">' + option.nom_site+ '</option>');
                        });          
                        site_comm.prop('disabled', false);
                        },
                        error: function() {
                            site_comm.html('<option value="">Erreur de chargement</option>');
                        }
                    });
                    } else {
                        site_comm.html('<option value="">Sélectionnez une option</option>');
                        site_comm.prop('disabled', true);
                    }
                });

                //Ouvrages par site
                $('#site_comm' + nombreChamps).change(function() {
                    let value = $(this).val();
                    let selectedOption = $(this).find('option:selected');
                    let url = '/sites/ouvrage_site/' + value;
                    let ouvrages_site = $('#ouvrages_site' + nombreChamps);    
                    ouvrages_site.prop('disabled', true).html('<span value="">Chargement en cours...</span>');    

                    if (url) {
                        $.ajax({
                            url: url,
                            method: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                // Réinitialise le contenu
                                ouvrages_site.html('');
                                
                                // Ajouter la case "Sélectionner tout"
                                ouvrages_site.append('<span class="mt-4">Liste d\'ouvrage(s) à construire<hr><label><input type="checkbox" id="checkAllOuvrages"/> Tout sélectionner</label><hr>');
                                // Ajouter les ouvrages récupérés
                                $.each(data, function(index, option) {
                                    ouvrages_site.append(
                                        '<div class="form-check form-check-inline">' +
                                            '<input class="form-check-input ouvrage-input" type="checkbox" name="ouvrage_id[]" id="inlineCheckbox_' + index + '" value="' + option.id + '">' +
                                            '<label class="form-check-label" for="inlineCheckbox_' + index + '">' + option.nom_ouvrage + '</label>' +
                                        '</div><br>'
                                    );
                                });

                                // Réactiver le champ
                                ouvrages_site.prop('disabled', false);

                                // Gérer la sélection de "Tout sélectionner"
                                $('#checkAllOuvrages').change(function() {
                                    if ($(this).is(':checked')) {
                                        $('.ouvrage-input').prop('checked', true);
                                    } else {
                                        $('.ouvrage-input').prop('checked', false);
                                    }
                                });
                            },
                            error: function() {
                                ouvrages_site.html('<span value="">Erreur de chargement</span>');
                            }
                        });
                    } else {
                        ouvrages_site.html('<span value="">Sélectionnez une option</span>');
                        ouvrages_site.prop('disabled', true);
                    }
                });
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