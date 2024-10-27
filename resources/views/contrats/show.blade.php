@extends('layouts.app')

@section('content')


    <?php
        $contrat = new App\Models\Contrat;
        $info = $contrat->ouvrage_contrat($signer->id);
    ?>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <!-- Report summary card example-->
            <div class="card mb-4">
                <div class="card-header">Information sur le contrat</div>
                <div class="list-group list-group-flush small">
                    <a class="list-group-item list-group-item-action" href="#!">
                        Code : {{ $signer->code }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Date signature : {{ $signer->date_sign }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Date demarrage des travaux : {{ $signer->date_debut }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Date fin des travaux : {{ $signer->date_fin }}
                    </a>
                </div>
                <div class="card-header">Information sur la localisation gographique</div>
                <div class="list-group list-group-flush small">
                    <a class="list-group-item list-group-item-action" href="#!">
                        Village : {{ $signer->nom_vill }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Canton : {{ $signer->nom_cant }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Commune : {{ $signer->nom_comm }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Préfecture : {{ $signer->nom_pref }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Région : {{ $signer->nom_reg }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-9 mb-4">
            <div class="card invoice">
                
                <div class="card-body p-4 p-md-5">
                    <!-- Invoice table-->
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead class="border-bottom">
                                <tr class="small text-uppercase text-muted">
                                    <th scope="col">Site</th>
                                    <th scope="col">Liste d'ouvrages</th>
                                    <th class="text-end" scope="col">Projet</th>
                                    <th class="text-end" scope="col">Type</th>
                                    <th class="text-end" scope="col">Status</th>
                                    <th class="text-end" scope="col">Financement</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Invoice item 1-->
                                @foreach($info as $info)
                                    <tr class="border-bottom">
                                        <td class="fw-bold">{{ $info->nom_site }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $info->nom_ouvrage }}</div>
                                            <div class="small text-muted d-none d-md-block" style="font-size: 0.6em;">{{ $info->descrip }}</div>
                                        </td>
                                        <td class="text-end fw-bold">{{ $info->name }}</td>
                                        <td class="text-end fw-bold">{{ $info->nom_type }}</td>
                                        <td class="text-end fw-bold">{{ $info->statu }}</td>
                                        <td class="text-end fw-bold">{{ $info->nom_fin }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer position-relative border-top-0">
                    <a class="stretched-link" href="{{ Route('contrats.index') }}">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Retour aux contrats
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection