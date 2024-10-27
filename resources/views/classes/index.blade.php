@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Les inscrits par école</h1>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_classe"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
@include('classes.create')
@include('classes.show')
@include('classes.edit')
@include('classes.delete')
@include('classes.crud')

    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.classe.js') }}"></script>
@endsection