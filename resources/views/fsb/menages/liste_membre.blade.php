@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste des membres du ménage</h1>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_liste_membre"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>

    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.liste_membre.js') }}"></script>
@endsection