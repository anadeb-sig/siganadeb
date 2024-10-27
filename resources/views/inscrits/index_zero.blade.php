@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Ecoles avec effectif null<br><a href="{{ route('inscrits.index') }}"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="40" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5"/>
</svg></a></h1>
                @can('inscrit-create')
                    <a href="javascript:void(0)" class="btn btn-outline-primary" id="add_modal_inscrit">
                        Nouvel enregistrement
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body"> 
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_inscritzero"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
@include('inscrits.create')
@include('inscrits.edit')
@include('inscrits.delete_zero')
@include('inscrits.crud')

    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.inscrit_zero.js') }}"></script>
@endsection