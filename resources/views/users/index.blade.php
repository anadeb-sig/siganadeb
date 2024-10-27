@extends('layouts.app')

@section('title', 'Users List')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste des utilisateurs</h1>
                <div class="row">
                    <div class="col-xl-7">
                        <div class="header-search">
                            <form action="{{ route('users.upload') }}" method="post" class="search-form search-widget" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="file" class="form-control" name="file" required="" value="">
                                <button type="submit" class="button action has-icon search-button">
                                    <span class="btn btn-outline-primary"><span class="fa fa-check "></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-xl-1">
                        <a href="{{ route('users.export') }}" class="btn btn-outline-success">
                            <i class="fas fa-file"></i>
                        </a>
                    </div>
                    @can('user-create')
                        <div class="col-xl-4" style="text-align: right;">
                            <a href="{{ route('users.create') }}" class="btn btn-outline-primary btnform">
                                <i class="fas fa-plus"></i> &nbsp;enregistrement
                            </a>
                        </div> 
                    @endcan          
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_user"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    @include('users.delete')
    @include('users.crud')
    
    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.user.js') }}"></script>

@endsection
