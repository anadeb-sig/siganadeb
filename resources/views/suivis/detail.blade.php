@extends('layouts.app')

@section('content')

    <?php 
            $list_ouv = new App\Models\Suivi;
            $list_ouvrages = $list_ouv->liste_ouvrage($suivi->site->id);
    ?>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <!-- Report summary card example-->
            <div class="card mb-4">
                <div class="card-header">Information sur le site</div>
                <div class="list-group list-group-flush small">
                    <a class="list-group-item list-group-item-action" href="#!">
                        Site : {{ $suivi->nom_site }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Status : {{ $suivi->statu }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Budget : {{ $suivi->budget }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Niveau des travaux : {{ $suivi->niv_eval }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Description : {{ $suivi->descrip_site }}
                    </a>
                </div>
                <div class="card-header">Localisation géographique</div>
                <div class="list-group list-group-flush small">
                    <a class="list-group-item list-group-item-action" href="#!">
                        Village : {{ $suivi->nom_vill }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Canton : {{ $suivi->nom_cant }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Commune : {{ $suivi->nom_comm }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Préfecture : {{ $suivi->nom_pref }}
                    </a>
                    <a class="list-group-item list-group-item-action" href="#!">
                        Région : {{ $suivi->nom_reg }}
                    </a>
                </div>
                <div class="card-header">
                    <a href="javascript:void(0)" id="show_suivi"  data-url_photo="/locations/galerie/{{$suivi->site->id}}">
                        Cliquez pour visiter les photos
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
                            <div class="h2 text-black mb-0">{{ $suivi->last_name }}  {{ $suivi->first_name }}</div>
                            <!-- Web Design &amp; Development -->
                        </div>
                        <div class="col-12 col-lg-auto text-center text-lg-end">
                            <!-- Invoice details-->
                            <div class="h3 text-black">{{ $suivi->mobile_number }}</div>
                            {{ $suivi->email }}
                            <!-- <br>
                            {{ $suivi->addr }} -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card invoice">
                <div class="card-header">Liste des ouvrages</div>
                <div class="card-body p-4 p-md-5">
                    <!-- Invoice table-->
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="border-bottom">
                                <tr class="small text-uppercase text-muted">
                                    <th class="text-left" scope="col">Ouvrage</th>
                                    <th class="text-left" scope="col">Description</th>
                                    <th class="text-left" scope="col">Financement</th>
                                    <th class="text-left" scope="col">Projet</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($list_ouvrages as $liste)
                                    <tr>
                                        <td>{{ $liste->nom_ouvrage }}</td>
                                        <td>{{ $liste->descrip }}</td>
                                        <td>{{ $liste->nom_fin }}</td>
                                        <td>{{ $liste->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-left">
                                        <a class="" href="{{ Route('suivis.index') }}">
                                            <i class="fas fa-long-arrow-alt-left"></i>
                                            Retour aux suivis
                                        </a>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-left">
                                        <span class="small text-muted">Déposée le : {{ $suivi->created_at }}</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>     
            </div>
        </div>
    </div>
    @include('suivis.show')
    @include('suivis.crud')
@endsection