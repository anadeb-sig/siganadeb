@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste de cantines</h1>
                @can('ecole-create')
                    <a href="javascript:void(0)" class="btn btn-outline-primary btnform" id="add_ecole">
                        <i class="fas fa-plus"></i> &nbsp;enregistrement
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-lg-12">
                    <div id="table_ecole"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('ecoles.create')
    @include('ecoles.show')
    @include('ecoles.edit')
    @include('ecoles.delete')
    @include('ecoles.crud')

    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.ecole.js') }}"></script>
    <script>
        // $('#region').change(function(){
        //     var myvalue = $("#region option:selected").attr("id");
        //     $.ajax({url: "/prefectures/"+myvalue, success: function(result){
        //     $("#prefect").html(result);
        //     document.getElementById('prefecturecss').style.display = "block";
        //     }});
        // }); 
        
        // $('#region').change(function(){
        //     var myvalue = $("#region option:selected").attr("id");
        //     $.ajax({url: "/communes/"+myvalue, success: function(result){
        //     $("#commun_comm").html(result);
        //     document.getElementById('communecss').style.display = "block";
        //     }});
        // });
        
        // $('#region_edit').change(function(){
        //     var myvalue = $("#region_edit option:selected").attr("id");
        //     $.ajax({url: "/prefectures/"+myvalue, success: function(result){
        //     $("#prefect_edit").html(result);
        //     document.getElementById('prefecturecss_edit').style.display = "block";
        //     }});
        // }); 
    </script>

    <script>
        // $('#prefect').change(function(){
        //     var myvalu = $("#prefect option:selected").attr("id");
        //     $.ajax({url: "/communes/"+myvalu, success: function(result){
        //     $("#commun").html(result);
        //     document.getElementById('communecss').style.display = "block";
        //     }});
        // });
        // $('#prefect_edit').change(function(){
        //     var myvalu = $("#prefect_edit option:selected").attr("id");
        //     $.ajax({url: "/communes/"+myvalu, success: function(result){
        //     $("#commun_edit").html(result);
        //     document.getElementById('communecss_edit').style.display = "block";
        //     }});
        // });            
    </script>

    <script>
        // $('#commun').change(function(){
        //     var myvalue = $("#commun option:selected").attr("id");
        //     $.ajax({url: "/cantons/"+myvalue, success: function(result){
        //     $("#canto").html(result);
        //     document.getElementById('cantoncss').style.display = "block";
        //     }});
        // }); 
        // $('#commun_edit').change(function(){
        //     var myvalue = $("#commun_edit option:selected").attr("id");
        //     $.ajax({url: "/cantons/"+myvalue, success: function(result){
        //     $("#canto_edit").html(result);
        //     document.getElementById('cantoncss_edit').style.display = "block";
        //     }});
        // });           
    </script>
    <script>
        // $('#canto').change(function(){
        //     var myvalue = $("#canto option:selected").attr("id");
        //     $.ajax({url: "/villages/"+myvalue, success: function(result){
        //     $("#localit").html(result);
        //     document.getElementById('localitecss').style.display = "block";
        //     }});
        // });
        // $('#canto_edit').change(function(){
        //     var myvalue = $("#canto_edit option:selected").attr("id");
        //     $.ajax({url:"/villages/"+myvalue, success: function(result){
        //     $("#localit_edit").html(result);
        //     document.getElementById('localitecss_edit').style.display = "block";
        //     }});
        // });
    </script>
@endsection