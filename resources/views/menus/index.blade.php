@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Paramètre du coût unitaire du plat</h1>    
                @can('menu-create')
                    <a href="javascript:void(0)" class="btn btn-outline-primary btnform" id="add_menu">
                        <i class="fas fa-plus"></i> &nbsp;enregistrement
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_menu"></div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('menus.create')
    @include('menus.show')
    @include('menus.edit')
    @include('menus.delete')
    @include('menus.crud')

    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.menu.js') }}"></script>
@endsection