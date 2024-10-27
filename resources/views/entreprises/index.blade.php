@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Liste des entreprises</h1>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-teal mr-2 btnform" type="button" id="exportCSV">
                        <i class="fas fa-file-export"></i> &nbsp;Format en csv
                    </button>

                    <form method="POST" action="{{ Route('entreprises.import') }}" class="file-upload-form mr-2" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input type="file" class="form-control file-input" name="file" class="ml-2" accept=".csv" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-yellow btnform" type="submit">
                                    <i class="fas fa-file-import"></i> &nbsp;Import
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="file-export">
                        <button class="btn btn-outline-cyan btnform" id="excelButton">
                            <i class="fas fa-file-export"></i> &nbsp;Export
                        </button>
                    </div>
                    @can('entreprise-create')
                        <a href="javascript:void(0)" class="btn btn-outline-primary btnform ml-2" id="add_entreprise">
                            <i class="fas fa-plus"></i> &nbsp;enregistrement
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-lg-12">
                    <div id="table_entreprise"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
    @include('entreprises.create')
    @include('entreprises.edit')
    @include('entreprises.show')
    @include('entreprises.delete')
    @include('entreprises.crud')

    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.entreprise.js') }}"></script>
@endsection