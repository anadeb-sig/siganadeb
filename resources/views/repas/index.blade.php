@extends('layouts.app')

@section('content')

<style>
    @media screen and (min-width: 769px) {
        .header-search .search-widget {
            max-width: 50rem!important;
            gap: 0rem!important;
        }
    }
</style>
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">
                    Liste de repas
                </h1>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-teal mr-2 btnform" type="button" id="format_charger">
                        <i class="fas fa-file-export"></i> &nbsp;Exemple de format
                    </button>

                    <form action="{{ route('temps.import') }}" method="post" class="file-upload-form mr-2" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <form action="{{ route('temps.import') }}" method="post" class="search-form search-widget" enctype="multipart/form-data">
                            {{ csrf_field() }}
                                <input type="file" class="form-control file-input" name="file" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-yellow btnform" type="submit">
                                        <i class="fas fa-file-import"></i> &nbsp;Import
                                    </button>
                                </div>
                            </form>
                        </div>
                    </form>

                    <div class="file-export">
                        <button class="btn btn-outline-cyan btnform">
                            <i class="fas fa-file-export" id="export-btn"></i> &nbsp;Export
                        </button>
                    </div>
                    @can('repas-create')
                        <a href="javascript:void(0)" class="btn btn-outline-primary btnform ml-2" id="add_repas">
                            <i class="fas fa-plus"></i> &nbsp;enregistrement
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body">        
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_repas"></div>
                </div>
                <!-- end col -->
            </div>            
        </div>
    </div>
    <!-- end row -->
    @include('repas.create')
    @include('repas.format_charger')
    @include('repas.edit')
    @include('repas.show')
    @include('repas.delete')
    @include('repas.crud')
    
    <script>
    //Liste les enseignements en arrière plan
        $(document).ready(function() {
            $('#ecole_rep').change(function() {
                let value = $(this).val();
                let selectedOption = $(this).find('option:selected');
                let url = '/inscrits/get-options/' + value;
                let inscrit_comm = $('#inscrit_rep'); 
                let scolaireForm = $('#scolaireForm');
                //console.log(url);
                inscrit_comm.empty();   
                if (url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.length == 1) {
                            $.each(data, function(index, option) {
                                if (option.nom_cla == "Pré_scolaire") {
                                    inscrit_comm.append('<input style="display:none" id="monInput'+index+'" class="inscrit_id'+index+'" name="inscrit_id'+index+'" data-id'+index+'="' + option.nom_cla + '" value="' + option.id + '">');
                                    let prescolaire = `<div class="modal-header">
                                                            NOMBRE DE PLATS FOURNIS AU NIVEAU PRE-SCOLAIRE
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-xl-6 {{ $errors->has('effec_fil_0') ? 'has-error' : '' }}">
                                                                    <label class="form-label">Filles</label>
                                                                    <input class="form-control effect_fil_0" name="effect_fil_0" id="effect_fil_0" type="number" min="0" placeholder="Nombre à renseigner, ex: 10"  required="true">
                                                                    {!! $errors->first('effec_fil_0', '<p class="help-block">:message</p>') !!}
                                                                </div>
                                                                <div class="col-xl-6 {{ $errors->has('effect_fil_0') ? 'has-error' : '' }}">
                                                                    <label class="form-label">Garçons</label>
                                                                    <input class="form-control effect_gar_0" name="effect_gar_0" type="number" min="0" placeholder="Nombre à renseigner, ex: 10"  required="true">
                                                                    <input class="form-control id" name="id" type="hidden" id="id">
                                                                    
                                                                    {!! $errors->first('effec_gar_1', '<p class="help-block">:message</p>') !!}
                                                                </div>
                                                            </div>
                                                        </div><input style="display:none" id="eval" class="prescolaire"  value="prescolaire">`;
                                    let exampleContainer = document.getElementById("scolaireForm");
                                    exampleContainer.innerHTML = prescolaire;
                                } else{
                                    inscrit_comm.append('<input style="display:none" id="monInput'+index+'" class="inscrit_id'+index+'" name="inscrit_id'+index+'" data-id'+index+'="' + option.nom_cla + '" value="' + option.id + '">');
                                    let primaire = `<div class="modal-header">
                                                        NOMBRE DE PLATS FOURNIS AU NIVEAU PRIMAIRE
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-xl-6 {{ $errors->has('effect_fil_1') ? 'has-error' : '' }}">
                                                                <label class="form-label">Filles</label>
                                                                <input class="form-control effect_fil_1" name="effect_fil_1" type="number" min="0"  placeholder="Nombre à renseigner, ex: 10"  required="true">
                                                                {!! $errors->first('effec_fil_2', '<p class="help-block">:message</p>') !!}
                                                            </div>
                                                            <div class="col-xl-6 {{ $errors->has('effect_fil_1') ? 'has-error' : '' }}">
                                                                <label class="form-label">Garçons</label>
                                                                <input class="form-control effect_gar_1" name="effect_gar_1" type="number" min="0"  placeholder="Nombre à renseigner, ex: 10"  required="true">
                                                                <input class="form-control id" name="id" type="hidden" id="id">
                                                                {!! $errors->first('effec_gar_2', '<p class="help-block">:message</p>') !!}
                                                            </div>
                                                        </div>
                                                    </div><input style="display:none" id="eval" class="primaire"  value="primaire">`;
                                    let exampleContainer = document.getElementById("scolaireForm");
                                    exampleContainer.innerHTML = primaire;
                                }
                            });
                        }else if(data.length == 2) {
                            $.each(data, function(index, option) {
                                inscrit_comm.append('<input style="display:none" id="monInput'+index+'" class="inscrit_id'+index+'" name="inscrit_id'+index+'" data-id'+index+'="' + option.nom_cla + '" value="' + option.id + '">');
                                let prepri =    `<div class="col-xl-6 pre">
                                                    <div class="card">
                                                        <div class="modal-header">
                                                            NOMBRE DE PLATS FOURNIS AU NIVEAU PRE-SCOLAIRE
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="{{ $errors->has('effec_fil_0') ? 'has-error' : '' }}">
                                                                    <label class="form-label">Plats des filles</label>
                                                                    <input class="form-control effect_fil_0" name="effect_fil_0" id="effect_fil_0" type="number" min="0" placeholder="Nombre à renseigner, ex: 10"  required="true">
                                                                    {!! $errors->first('effec_fil_0', '<p class="help-block">:message</p>') !!}
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="{{ $errors->has('effect_fil_0') ? 'has-error' : '' }}">
                                                                    <label class="form-label">Plats des garçons</label>
                                                                    <input class="form-control effect_gar_0" name="effect_gar_0" id="effect_gar_0" type="number" min="0" placeholder="Nombre à renseigner, ex: 10"  required="true">
                                                                    <input class="form-control id" name="id" type="hidden" id="id">
                                                                    {!! $errors->first('effec_gar_1', '<p class="help-block">:message</p>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 pri">
                                                    <div class="card">
                                                        <div class="modal-header">
                                                            NOMBRE DE PLATS FOURNIS AU NIVEAU PRIMAIRE
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="{{ $errors->has('effect_fil_1') ? 'has-error' : '' }}">
                                                                    <label class="form-label">Plats des filles</label>
                                                                    <input class="form-control effect_fil_1" name="effect_fil_1" id="effect_fil_1" type="number" min="0"  placeholder="Nombre à renseigner, ex: 10"  required="true">
                                                                    {!! $errors->first('effec_fil_2', '<p class="help-block">:message</p>') !!}
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="{{ $errors->has('effect_fil_1') ? 'has-error' : '' }}">
                                                                    <label class="form-label">Plats des garçons</label>
                                                                    <input class="form-control effect_gar_1" name="effect_gar_1" id="effect_gar_1" type="number" min="0"  placeholder="Nombre à renseigner, ex: 10"  required="true">
                                                                    <input class="form-control id" name="id" type="hidden" id="id">
                                                                    {!! $errors->first('effec_gar_2', '<p class="help-block">:message</p>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input style="display:none" id="eval" class="prepri"  value="prepri">`;
                                let exampleContainer = document.getElementById("scolaireForm");
                                exampleContainer.innerHTML = prepri;
                            });
                        }else if(data.length > 2) {
                                inscrit_comm.append('<div class="mt-4"><span class="text-red mt-4"> Vous avez activé trop d\'écoles associées à cette cantine aucours d\'une année scolaire!</span></div>');
                        }else{
                            inscrit_comm.append('<div class="mt-4"><span class="text-red mt-4"> Aucune école n\'est associée à cette cantine!</span></div>');
                        }
                    },
                    error: function() {
                        //classe_comm.html('<option value="">Erreur de chargement</option>');
                    }
                });
                } else {
                    //classe_comm.html('<option value="">Sélectionnez une option</option>');
                    //classe_comm.prop('disabled', true);
                }
            });
        });
    </script>
    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.repas.js') }}"></script>
    <script>
        $('#region').change(function(){
            var myvalue = $("#region option:selected").attr("id");
            $.ajax({url: "/prefectures/"+myvalue, success: function(result){
            $("#prefect").html(result);
            document.getElementById('prefecturecss').style.display = "block";
            }});
        });   
        
        $('#region_edit').change(function(){
            var myvalue = $("#region_edit option:selected").attr("id");
            $.ajax({url: "/prefectures/"+myvalue, success: function(result){
            $("#prefect_edit").html(result);
            document.getElementById('prefecturecss_edit').style.display = "block";
            }});
        }); 
    </script>

    <script>
        $('#prefect').change(function(){
            var myvalu = $("#prefect option:selected").attr("id");
            $.ajax({url: "/communes/"+myvalu, success: function(result){
            $("#commun").html(result);
            document.getElementById('communecss').style.display = "block";
            }});
        });
        $('#prefect_edit').change(function(){
            var myvalu = $("#prefect_edit option:selected").attr("id");
            $.ajax({url: "/communes/"+myvalu, success: function(result){
            $("#commun_edit").html(result);
            document.getElementById('communecss_edit').style.display = "block";
            }});
        });            
    </script>

    <script>
        $('#commun').change(function(){
            var myvalue = $("#commun option:selected").attr("id");
            $.ajax({url: "/cantons/"+myvalue, success: function(result){
            $("#canto").html(result);
            document.getElementById('cantoncss').style.display = "block";
            }});
        }); 
        $('#commun_edit').change(function(){
            var myvalue = $("#commun_edit option:selected").attr("id");
            $.ajax({url: "/cantons/"+myvalue, success: function(result){
            $("#canto_edit").html(result);
            document.getElementById('cantoncss_edit').style.display = "block";
            }});
        });           
    </script>
    <script>
        $('#canto').change(function(){
            var myvalue = $("#canto option:selected").attr("id");
            $.ajax({url: "/villages/"+myvalue, success: function(result){
            $("#localit").html(result);
            document.getElementById('localitecss').style.display = "block";
            }});
        });
        $('#canto_edit').change(function(){
            var myvalue = $("#canto_edit option:selected").attr("id");
            $.ajax({url: "/villages/"+myvalue, success: function(result){
            $("#localit_edit").html(result);
            document.getElementById('localitecss_edit').style.display = "block";
            }});
        });
    </script>

    <script>
        $('#localit').change(function(){
            var myvalue = $("#localit option:selected").attr("id");
            $.ajax({url: "/ecoles/"+myvalue, success: function(result){
            $("#ecole").html(result);
            document.getElementById('ecolecss').style.display = "block";
            }});
        });
        $('#localit_edit').change(function(){
            var myvalue = $("#localit_edit option:selected").attr("id");
            $.ajax({url: "/ecoles/"+myvalue, success: function(result){
            $("#ecole_edit").html(result);
            document.getElementById('ecolecss_edit').style.display = "block";
            }});
        });
    </script>

    <script>
        $('#ecole').change(function(){
            var myvalue = $("#ecole option:selected").attr("id");
            $.ajax({url: "/classes/"+myvalue, success: function(result){
            $("#classe").html(result);
            document.getElementById('classecss').style.display = "block";
            }});
        });
        $('#ecole_edit').change(function(){
            var myvalue = $("#ecole_edit option:selected").attr("id");
            $.ajax({url: "/classes/"+myvalue, success: function(result){
            $("#classe_edit").html(result);
            document.getElementById('classecss_edit').style.display = "block";
            }
        });
        });
    </script>

    <script>
        let checkboxx = document.getElementById('checkbox');
        let monTextareaa = document.getElementById('monTextarea');

        checkboxx.addEventListener('change', function() {
            // Vérifiez si le checkbox est coché
            if (checkbox.checked) {
                // Affichez le textarea
                monTextareaa.style.display = 'block';
                monTextareaa.value = 0;
                document.getElementById('effect_fil_0').value = 0;
                document.getElementById('effect_fil_1').value = 0;
                document.getElementById('effect_gar_0').value = 0;
                document.getElementById('effect_gar_1').value = 0;
            } else {
                // Masquez le textarea
                monTextareaa.style.display = 'none';
            }
        });        
    </script>


</body>

@endsection