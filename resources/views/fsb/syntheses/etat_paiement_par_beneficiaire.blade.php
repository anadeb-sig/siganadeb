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
                <h1 class="h3 mb-0 text-gray-800">Synthèse de paiements par bénéficiaire</h1>
                <div class="file-export">
                    <button class="btn btn-outline-cyan btnform" onclick="fetchAllDataForExport()">
                        <i class="fas fa-file-export"></i> &nbsp;Export
                    </button>
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
                                    <div class="col-xl-4 {{ $errors->has('nom_pref') ? 'has-error' : '' }}">
                                        <label for="nom_pref" class="control-label">Préfecture</label>
                                        <input class="form-control w-100" id="nom_pref" name="nom_pref" type="text" placeholder="exemple: MO..." />
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="commune_id" class="control-label">Commune</label>
                                        <input class="form-control w-100 majuscules" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: MO 2  ..." />
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-4">
                                        <label for="nom_cant" class="control-label">Canton</label>
                                        <input class="form-control w-100 majuscules" id="nom_cant" name="nom_cant" type="text" placeholder="exemple: TINDJASSE ..." />
                                    </div>
                                    <div class="col-xl-4 {{ $errors->has('nom_vill') ? 'has-error' : '' }}">
                                        <label for="nom_vill" class="control-label">Village /Quartier</label>
                                        <input class="form-control w-100" id="nom_vill" name="nom_vill" type="text" placeholder="exemple: TINDJA..." />
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="rang">Financement</label>
                                        <input class="form-control" type="text" id='financement'  name="financement" placeholder="exemple: BM">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-4">
                                        <label for="nom" class="control-label">Nom du BD</label>
                                        <input class="form-control w-100 majuscules" id="nom" name="nom" type="text" placeholder="exemple: Koffi ..." />
                                    </div>
                                    <div class="col-xl-4 {{ $errors->has('prenom') ? 'has-error' : '' }}">
                                        <label for="prenom" class="control-label">Prenom du BD</label>
                                        <input class="form-control w-100" id="prenom" name="prenom" type="text" placeholder="exemple: Kodjo..." />
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="rang">Numéro de téléphone</label>
                                        <input class="form-control" type="text" id='telephone'  name="telephone" placeholder="exemple: 90654796">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-4">
                                        <label for="rang">Numéro de la pièce d'identité</label>
                                        <input class="form-control" type="text" id='cardNum'  name="cardNum" placeholder="exemple: 00090654796">
                                    </div>
                                    <div class="col-xl-5"></div>
                                    <div class="col-xl-3 modal-footer mt-4">
                                        <button id="btnEtatpaiement" type="button" class="btn btn-outline-primary recherche">
                                            <i class="fa fa-search"></i> &nbsp;Rechercher
                                        </button>
                                        &nbsp;&nbsp;
                                        <a href="{{ route('fsb_syntheses.par_beneficiaire') }}" type="button" class="btn btn-outline-danger">
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
                    <div id="table_etat_par_bd"></div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_paiement_beneficiaire();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnEtatpaiement').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_pref = document.getElementById('nom_pref').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_cant = document.getElementById('nom_cant').value;
                let nom_vill = document.getElementById('nom_vill').value;
                let financement = document.getElementById('financement').value;
                let nom = document.getElementById('nom').value;
                let prenom = document.getElementById('prenom').value;
                let telephone = document.getElementById('telephone').value;
                let cardNum = document.getElementById('cardNum').value;
                rendtableau_paiement_beneficiaire(nom_reg,nom_pref, nom_comm,nom_cant,nom_vill, financement,nom,prenom, telephone,cardNum);
                
            });
        });

        function loaddatauser_paiement_beneficiaire(){
            let nom_reg = "";
            let nom_pref = "";
            let nom_comm = "";
            let nom_cant = "";
            let nom_vill = "";
            let financement = "";
            let nom= "";
            let prenom= ""; 
            let telephone= "";
            let cardNum= "";

            rendtableau_paiement_beneficiaire(nom_reg,nom_pref, nom_comm,nom_cant,nom_vill, financement,nom,prenom, telephone,cardNum);
        } 
    </script>

    <script>
        function fetchAllDataForExport() {
            // Récupération des valeurs des filtres
            let nom_reg = document.getElementById('nom_reg').value;
                let nom_pref = document.getElementById('nom_pref').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_cant = document.getElementById('nom_cant').value;
                let nom_vill = document.getElementById('nom_vill').value;
                let financement = document.getElementById('financement').value;
                let nom = document.getElementById('nom').value;
                let prenom = document.getElementById('prenom').value;
                let telephone = document.getElementById('telephone').value;
                let cardNum = document.getElementById('cardNum').value;

            // Appel à l'API pour récupérer les données
            fetch(`/fsb_syntheses/fetch_beneficiaire?nom_reg=${nom_reg}&nom_pref=${nom_pref}&nom_comm=${nom_comm}&nom_cant=${nom_cant}&nom_vill=${nom_vill}&financement=${financement}&nom=${nom}&prenom=${prenom}&telephone=${telephone}&cardNum=${cardNum}&export=true`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP ! statut : ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data.data.length); // Affiche les données brutes
                    if (data && data.data) {
                        exporterVersExcel(data.data); // Exporter toutes les données récupérées
                    } else {
                        console.warn("Aucune donnée à exporter ou data.data n'est pas défini.");
                    }
                })
                .catch(error => console.error('Erreur de récupération des données:', error));
        }

        // Fonction d'exportation vers Excel
        function exporterVersExcel(data) {
            if (!data || data.length === 0) {
                console.warn("Aucune donnée à exporter.");
                return;
            }

            // Créer un nouveau classeur (workbook)
            const workbook = XLSX.utils.book_new();
            
            // Convertir les données JSON en une feuille de calcul (worksheet)
            const worksheet = XLSX.utils.json_to_sheet(data);
            
            // Ajouter la feuille de calcul au classeur
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Données');
            
            // Obtenir la date actuelle pour le nom du fichier
            let today = new Date();
            let yyyy = today.getFullYear();
            let mm = String(today.getMonth() + 1).padStart(2, '0');
            let dd = String(today.getDate()).padStart(2, '0');
            let filename = `${yyyy}_${mm}_${dd}_etatpaiement_par_bd.xlsx`;

            // Exporter le classeur en fichier Excel
            XLSX.writeFile(workbook, filename);
        }

    </script>
@endsection