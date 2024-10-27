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
                <h1 class="h3 mb-0 text-gray-800">Etat de paiements des BD</h1>
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
                                        <input type="text" class="form-control w-100 majuscules" id="nom" name="nom" placeholder="exemple: Koffi..." />
                                    </div>
                                    <div class="col-xl-4 {{ $errors->has('prenom') ? 'has-error' : '' }}">
                                        <label for="prenom" class="control-label">Prénom du bénéficiaire</label>
                                        <input class="form-control w-100 majuscules" id="prenom" name="prenom" type="text" placeholder="exemple: Yaya..." />
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
                                    <div class="col-xl-4">
                                        <label for="Sexe"  class="control-label">Type de la pièce d'identité</label>
                                        <input class="form-control w-100" name="type_card" id="type_card" placeholder="Exple:CE, RID, ...">
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="card_number">Numéro de la carte d'identité</label>
                                        <input class="form-control" type="text" id='card_number'  name="card_number" placeholder="Exple: 5-106-02-03-06-01-01-00213">
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="rang">Financement</label>
                                        <input class="form-control" type="text" id='financement'  name="financement" placeholder="exemple: BM">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-3">
                                        <label for="">Selon le nombre de fois payé!</label>
                                        <select class="form-control" name="SommeTM" id="SommeTM" value="">
                                            <option  value="">Selectionnez une option</option>
                                            <option value="0">Non encore payés</option>
                                            <option value="1">Payés au moins une fois</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-3">
                                        <label for="">Selon le montant reçu par le BD TM</label>
                                        <select class="form-control" name="montant" id="montant" value="">
                                            <option  value="">Selectionnez une option</option>
                                            <option  value="0">Rien reçu (0)</option>
                                            <option  value="15000">Une tranche (15 000)</option>
                                            <option  value="30000">Deux tranches (30 000)</option>
                                            <option value="45000">Trois trancches (45 000)</option>
                                            <option value="60000">Quatre tranches (60 000)</option>
                                            <option value="75000">Cinq tranches (75 000)</option>
                                            <option value="90000">Six tranches (90 000)</option>
                                            <option value="100000">Reçu plus de 100 000</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-3">
                                        <label for="">Type de transfert!</label>
                                        <select class="form-control" name="type_transfert" id="type_transfert" value="">
                                            <option  value="">Selectionnez le type de transfert</option>
                                            <option value="TM">TM</option>
                                            <option value="MIE">MIE</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-3 modal-footer mt-4">
                                        <button id="btnEtatpaiement" type="button" class="btn btn-outline-primary recherche">
                                            <i class="fa fa-search"></i> &nbsp;Rechercher
                                        </button>
                                        &nbsp;&nbsp;
                                        <a href="{{ route('beneficiaires.etat_paiements') }}" type="button" class="btn btn-outline-danger">
                                            <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div id="table_etatpaiements"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paiement_liste_modal" tabindex="-1" aria-labelledby="show_paiement_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="table_liste_paiement"></table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_paiement();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnEtatpaiement').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let financement = document.getElementById('financement').value;
                let nom_vill = document.getElementById('nom_vill').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom = document.getElementById('nom').value;
                let prenom = document.getElementById('prenom').value;
                let sexe = document.getElementById('sexe').value;
                let type_card = document.getElementById('type_card').value;
                let card_number = document.getElementById('card_number').value;
                let telephone = document.getElementById('telephone').value;
                let SommeTM = document.getElementById('SommeTM').value;
                let montant = document.getElementById('montant').value;
                let type_transfert = document.getElementById('type_transfert').value;
                rendtableau_paiement(nom_reg, nom_comm, nom_vill, nom,prenom, telephone, sexe, type_card, card_number,financement,SommeTM,montant,type_transfert);
                
            });
        });

        function loaddatauser_paiement(){
            let nom_reg = "";
            let nom_vill = "";
            let nom_comm = "";
            let financement = "";
            let nom = "";
            let prenom = "";
            let sexe = "";
            let telephone = "";
            let type_card = "";
            let card_number = "";
            let SommeTM = "";
            let montant = "";
            let type_transfert = "";
            rendtableau_paiement(nom_reg, nom_comm, nom_vill, nom,prenom, telephone, sexe, type_card, card_number,financement,SommeTM,montant,type_transfert);
        } 
        
        
        var modal = document.getElementById("paiement_liste_modal");
        // Récupération du bouton de fermeture
        var span = document.getElementsByClassName("close")[0];

        // Lorsque l'utilisateur clique sur le bouton de fermeture, fermer la modale
        span.onclick = function() {
        modal.style.display = "none";
        }

            // Lorsque l'utilisateur clique en dehors de la modale, fermer la modale
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endsection