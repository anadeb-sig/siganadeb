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
                                        <td class="fw-bold">
                                            <a href="javascript:void(0);" id='show_site' data-url='/sites/show/{{ $info->ouvrage->site->id }}' data-id='{{ $info->ouvrage->site->id }}'>{{ $info->nom_site }}</a>
                                        </td>
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


    @include('sites.show')

    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('click', '#show_site', function(){
                var url = $(this).data('url');
                var id = $(this).data('id');
                $.get(url, function(data){
                    $('.modal-title').text('Détail du site n°'+id);
                    $('.show_site_modal').modal('show');
                        $('#show_id').text(data.id);
                        $('#show_nom_site').text(data.nom_site);
                        $('#show_nom_vill').text(data.nom_vill);                    
                        $('#show_nom_cant').text(data.nom_cant);                    
                        $('#show_nom_comm').text(data.nom_comm);                   
                        $('#show_descrip_site').text(data.descrip_site);                    
                        $('#show_budget').text(data.budget);
                        $('#show_nom_pref').text(data.nom_pref);
                        $('#show_nom_reg').text(data.nom_reg);
                })
            });
        });
    </script>
@endsection