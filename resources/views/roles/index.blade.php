@extends('layouts.app')

@section('title', 'Permissions')

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste de rôles</h1>
                @can('role-create')
                    <a href="{{ route('roles.create') }}" class="btn btn-outline-primary btnform">
                        <i class="fas fa-plus"></i> &nbsp;enregistrement
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-lg-12">
                    <div id="table_role"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    @include('roles.delete')
    @include('roles.crud')
    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.role.js') }}"></script>

@endsection