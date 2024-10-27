@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">            
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste des ouvrages</h1>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-teal mr-2 btnform" type="button" id="telecharger_ouvrage">
                        <i class="fas fa-file-export"></i> &nbsp;Format en csv
                    </button>

                    <form method="POST" action="{{ Route('ouvrages.import') }}" class="file-upload-form mr-2" enctype="multipart/form-data">
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
                    @can('ouvrage-create')
                        <a href="javascript:void(0)" class="btn btn-outline-primary btnform ml-2" id="add_ouvrage">
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
                                    <div class="col-xl-3">
                                        <label for="nom_reg" class="control-label">Région</label>
                                        <input class="form-control w-100 majuscules" id="nom_reg" name="nom_reg" type="text" placeholder="exemple: CENTRALE ..." />
                                    </div>
                                    <div class="col-xl-3">
                                        <label for="commune_id" class="control-label">Commune</label>
                                        <input class="form-control w-100 majuscules" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: MO 2  ..." />
                                    </div>
                                    <div class="col-xl-3 {{ $errors->has('nom_ouv') ? 'has-error' : '' }}">
                                        <label for="nom_ouv" class="control-label">Nom de l'ouvrage</label>
                                        <input class="form-control w-100" id="nom_ouv" name="nom_ouv" type="text" placeholder="exemple: batiment scolaire..." />
                                    </div>
                                    <div class="col-xl-3 {{ $errors->has('nom_type') ? 'has-error' : '' }}">
                                        <label for="nom_type" class="control-label">Type de l'ouvrage</label>
                                        <select class="form-control" id="nom_type" name="nom_type">
                                            <option value="" style="display: none;" disabled selected>Selectionner le type de l'ouvrage</option>
                                            @foreach ($Typeouvrages as $Typeouvrage)
                                                <option value="{{ $Typeouvrage->nom_type }}">
                                                    {{ $Typeouvrage->nom_type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-3 {{ $errors->has('nom_pr') ? 'has-error' : '' }}">
                                        <label for="projet_id" class="control-label">Projet / Programme</label>
                                        <select class="form-control" id="projetOuvrage" name="nom_pr">
                                            <option value="" style="display: none;" disabled selected>Selectionner le projet</option>
                                            @foreach ($projets as $projet)
                                                <option value="{{ $projet->name }}">
                                                    {{ $projet->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-3">
                                        <label for="rang">Financement</label>
                                        <input class="form-control" type="text" id='nom_fin'  name="nom_fin" placeholder="exemple: BM">
                                    </div>
                                    <div class="col-xl-3 {{ $errors->has('nom_pr') ? 'has-error' : '' }}">
                                        <label for="statu" class="control-label">Status</label>
                                        <select class="form-control" id="statu" name="statu">
                                            <option value="" style="display: none;" disabled selected>Selectionner le status</option>
                                            <option value="NON_DEMARRE">NON DEMARRE</option>
                                            <option value="EC">EN COURS</option>
                                            <option value="RT">RECEPTION TECHNIQUE</option>
                                            <option value="RP">RECEPTION PROVISOIRE</option>
                                            <option value="RD">RECEPTION DEFINITIVE</option>
                                            <option value="SUSPENDU">SUSPENDU</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-3 modal-footer mt-4">
                                        <a href="{{ route('ouvrages.index') }}" type="button" class="btn btn-outline-danger">
                                            <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                        </a>
                                        &nbsp;&nbsp;
                                        <button id="btnOuvrage" type="button" class="btn btn-outline-primary recherche">
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
                    <div id="table_ouvrage"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('ouvrages.telecharger_format')
    @include('ouvrages.create')
    @include('ouvrages.show')
    @include('ouvrages.edit')
    @include('ouvrages.delete')
    @include('ouvrages.crud')
    @include('ouvrages.demande_suspension')
    @include('ouvrages.apelle_function')
@endsection