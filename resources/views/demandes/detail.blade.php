@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-3 mb-4">
            <!-- Report summary card example-->
            <div class="card mb-4">
                <div class="card-header">Information sur le contrat</div>
                <div class="list-group list-group-flush small">
                    <a class="list-group-item list-group-item-action" href="#!">
                        Code : {{ $ouvrage->code }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Date signature : {{ $ouvrage->date_sign }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Date demarrage des travaux : {{ $ouvrage->date_debut }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Date fin des travaux : {{ $ouvrage->date_fin }}
                    </a>
                </div>
                <div class="card-header">Information sur l'entreprise</div>
                <div class="list-group list-group-flush small">
                    <a class="list-group-item list-group-item-action" href="#!">
                        Entreprise : {{ $ouvrage->nom_entrep }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Numro NIF : {{ $ouvrage->num_id_f }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Nom du charger : {{ $ouvrage->nom_charge }}   {{ $ouvrage->prenom_charge }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Email : {{ $ouvrage->email }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Tléphone : {{ $ouvrage->tel }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Adresse : {{ $ouvrage->addr }}
                    </a>
                </div>
                <div class="card-header">Information sur la localisation gographique</div>
                <div class="list-group list-group-flush small">
                    <a class="list-group-item list-group-item-action" href="#!">
                        Village : {{ $demande->nom_vill }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Canton : {{ $demande->nom_cant }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Commune : {{ $demande->nom_comm }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Préfecture : {{ $demande->nom_pref }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Région : {{ $demande->nom_reg }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-9 mb-4">
            <div class="card">
                <div class="card-header p-4 p-md-5 border-bottom-0" style="background-color: #f8f8f8;">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-12 col-lg-auto mb-5 mb-lg-0 text-center text-lg-start">
                            <!-- Invoice branding-->
                            <img class="invoice-brand-img rounded-circle mb-4" width="50" src="/assets/images/default-avatar.jpg" alt="">
                            <div class="h2 text-black mb-0">{{ $demande->last_name }}  {{ $demande->first_name }}</div>
                            <!-- Web Design &amp; Development -->
                        </div>
                        <div class="col-12 col-lg-auto text-center text-lg-end">
                            <!-- Invoice details-->
                            <div class="h3 text-black">{{ $demande->mobile_number }}</div>
                            {{ $demande->email_user }}
                            <!-- <br>
                            {{ $demande->addr }} -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card invoice">
                <div class="card-header">Inormations sur la demande de suspension</div>
                <div class="card-body p-4 p-md-5">
                    <!-- Invoice table-->
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="border-bottom">
                                <tr class="small text-uppercase text-muted">
                                    <th class="text-left" scope="col">Titre</th>
                                    <th class="text-left" scope="col">Description de la demande</th>
                                    <th class="text-left" scope="col">Nombre de jours sollicité</th>
                                    <th class="text-left" scope="col">Approbation</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $demande->titre }}</td>
                                <td>{{ $demande->description }}</td>
                                <td>{{ $demande->nbJr }}</td>
                                <td>
                                    @if($demande->statu != "Approuvé")
                                        <a href='/demandes/approvals/{{ $demande->id }}/Approuvé'>
                                            <span class="badge bg-danger">Validez!</span>
                                        </a>
                                    @else
                                        <span class="badge bg-info">Déjà apprové!</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-left">
                                        <a class="" href="{{ Route('demandes.index') }}">
                                            <i class="fas fa-long-arrow-alt-left"></i>
                                            Retour aux demandes
                                        </a>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-left">
                                        <span class="small text-muted">Déposée le : {{ $demande->created_at }}</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>     
            </div>
        </div>
    </div>
@endsection