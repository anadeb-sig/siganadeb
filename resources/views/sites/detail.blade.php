@extends('layouts.app')

@section('content')

    <div class="card card-waves mb-4 mt-5">
        <div class="card-body p-5">
            <div class="row align-items-center justify-content-between">
                <div class="col">
                    <h2 class="text-primary">{{ $sitelocs->nom_site }}</h2>
                    <p class="text-gray-700">{{ $sitelocs->descrip_site }}</p>
                    <a class="btn btn-outline-primary p-2" href="{{ Route('sites.index') }}">
                        <i class="fa fa-arrow-left ms-1 text-white-900"></i>
                    </a>
                </div>
                <div class="col d-none d-lg-block mt-xxl-n4 mb-5">
                    <div class="card-header">Localisation géographique du site</div>
                    <div class="list-group list-group-flush small">
                        <a class="list-group-item list-group-item-action" href="#!">
                            Village : {{ $sitelocs->nom_vill }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Canton : {{ $sitelocs->nom_cant }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Commune : {{ $sitelocs->nom_comm }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Préfecture : {{ $sitelocs->nom_pref }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Région : {{ $sitelocs->nom_reg }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach($sites as $site)
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">Information sur l'ouvrage : {{ $site->nom_ouvrage }}</div>
                    <div class="list-group list-group-flush small">
                        <a class="list-group-item list-group-item-action" href="#!">
                            Description sur l'ouvrage : {{ $site->descrip }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Status : {{ $site->statu }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Type d'ouvrage : {{ $site->nom_type }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Financement : {{ $site->nom_fin }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Projet : {{ $site->name }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Entreprise attributaire : {{ $site->nom_entrep }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Numéro nif : {{ $site->num_id_f }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Nom et prénom du chargé : {{ $site->nom_charge }} {{ $site->prenom_charge }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Téléphone : {{ $site->tel }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Email : {{ $site->email }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Adresse : {{ $site->addr }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            Contrat numéro : {{ $site->code }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    
@endsection