@extends('layouts.app')

@section('title', 'Permissions')

@section('content')

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between mb-2 mt-2">
            <h1 class="h3 mb-0 text-gray-800">Liste de permissions</h1>
            @can('permission-create')
                <a href="{{ route('permissions.create') }}" class="btn btn-outline-primary btnform">
                    <i class="fas fa-plus"></i> &nbsp;enregistrement
                </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_permission"></div>
                </div>
            </div>
        </div>
    </div>
    
    @include('permissions.delete')
    @include('permissions.crud')
    
    <script type="module" src="{{ asset('assets/gridjs/gridjs.permission.js') }}"></script>
@endsection
