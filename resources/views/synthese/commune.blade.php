@extends('layouts.app')

@section('content')
    <style>
        .gridjs-th{
            min-width: 150px;
            width: 200px!important;
        }
    </style>
    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="uil uil-check me-2"></i>
                {!! session('success_message') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(Session::has('error_message'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="uil uil-exclamation-octagon me-2"></i>
                {!! session('error_message') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Synthèse /Commune<br><a href="#!" class="text_export" onclick="ExportTExcel('xlsx')">... Exporter en excel par recherche multiple du tableau!</a></h1>            
                <button class="btn btn-outline-primary export" id="export-btn">Export en excel</button> 
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
                                        <label for="canton_id" class="control-label">Région</label>
                                        <input class="form-control w-100 majuscules" id="nom_reg" name="nom_reg" type="text" placeholder="exemple: KARA..." />
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="canton_id" class="control-label">Commune</label>
                                        <input class="form-control w-100 majuscules" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: BINAH 1..." />
                                    </div>
                                    <div class="col-xl-4 {{ $errors->has('canton_id') ? 'has-error' : '' }}">
                                        <label for="canton_id" class="control-label">Financement</label>
                                        <input class="form-control w-100" id="nom_fin" name="nom_fin" type="text" placeholder="exemple: Etat..." />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <label for="start-date">Date début</label>
                                        <input class="form-control" type="date" id="start" name="start">
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="end-date">Date fin</label>
                                        <input class="form-control" type="date" id='end'  name="end">
                                    </div>
                                </div>            
                                
                                <div class="modal-footer mt-3">
                                    <button id="btnP" type="button" class="btn btn-outline-primary mt-2 recherche">
                                        <i class="fa fa-search"></i> &nbsp;Rechercher
                                    </button>
                                    &nbsp;&nbsp;
                                    <a href="{{ route('syntheses.synthese_commune') }}" id="btnP" type="button" class="btn btn-outline-danger mt-2 recherche">
                                        <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                    </a>
                                </div>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div id="table_parcommune"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- gridjs js-->
    <script type="text/javascript"> 
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauserdefault();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnP').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                const startDate = document.getElementById('start').value;
                const endDate = document.getElementById('end').value;
                const nom_reg = document.getElementById('nom_reg').value;
                const nom_comm = document.getElementById('nom_comm').value;
                const nom_fin = document.getElementById('nom_fin').value;
                rendtableau_comm(startDate,endDate,nom_reg,nom_comm,nom_fin);
            });
        });

        function loaddatauserdefault(){
            const startDate = "2023-01-01";
            const endDate = "2024-09-30";
            const nom_reg = "";
            const nom_comm = "";
            const nom_fin = "";
            rendtableau_comm(startDate,endDate,nom_reg,nom_comm,nom_fin);
        }
    </script>

    <script>
        function hideR(table,rowIndex){
            var elt = document.getElementById('table_parcommune');
            for (var i = 0, row; row = elt.length; i++) {
                if(rowIndex==i){
                    row.className ="hiddenclass";
                }
            }        
        }
        function ExportToxcel(type, fn, dl) {
            var elt = document.getElementById('table_parcommune');
            hideR(  elt,2);
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1", ignoreHiddenRows: true });
            
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('commune.' + (type || 'xlsx')));
        }
    </script>
@endsection