@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste type d'ouvrages</h1>
                @can('typeouvrage-create')
                    <a href="javascript:void(0)" class="btn btn-outline-primary btnform" id="add_typeouvrage">
                        <i class="fas fa-plus"></i> &nbsp;enregistrement
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-lg-12">
                    <div id="table_typeouvrage"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('typeouvrages.create')
    @include('typeouvrages.show')
    @include('typeouvrages.edit')
    @include('typeouvrages.delete')
    @include('typeouvrages.crud')

    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.typeouvrage.js') }}"></script>
@endsection