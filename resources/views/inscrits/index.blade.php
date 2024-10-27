@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
            <h1 class="h3 mb-0 text-gray-800">Les inscrits par école<br><a href="{{ route('inscrits.index_zero')}}" class="text_export">... Voir les écoles disposant des effectifs inscrits null!</a></h1>
                @can('inscrit-create')
                    <a href="javascript:void(0)" class="btn btn-outline-primary btnform" id="add_modal_inscrit">
                        <i class="fas fa-plus"></i> &nbsp;enregistrement
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div id="table_inscrit"></div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </div>
    <!-- end row -->
@include('inscrits.create')
@include('inscrits.show')
@include('inscrits.edit')
@include('inscrits.delete')
@include('inscrits.crud')

    <!-- gridjs js-->
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.inscrit.js') }}"></script>
    <script>
        document.getElementById('flexSwitchCheckDefault').addEventListener('change', function() {
            var label = document.querySelector('label[for="flexSwitchCheckDefault"]');
            if (this.checked) {
                label.textContent = 'Oui demarrée pour l\'année';
                document.getElementById('statuspre').value = 1;
            } else {
                label.textContent = 'Non demarrée pour l\'année';
                document.getElementById('statuspre').value = 0;
            }
        });

        document.getElementById('flexSwitchCheckDefaultpri').addEventListener('change', function() {
            var label = document.querySelector('label[for="flexSwitchCheckDefault"]');
            if (this.checked) {
                label.textContent = 'Oui demarrée pour l\'année';
                document.getElementById('statuspri').value = 1;
            } else {
                label.textContent = 'Non demarrée pour l\'année';
                document.getElementById('statuspri').value = 0;
            }
        });

        document.getElementById('flexSwitchCheckChecked').addEventListener('change', function() {
            var label = document.querySelector('label[for="flexSwitchCheckChecked"]');
            if (this.checked) {
                label.textContent = 'Oui demarrée pour l\'année';
                document.getElementById('status_edit').value = 1;
            } else {
                label.textContent = 'Non demarrée pour l\'année';
                document.getElementById('status_edit').value = 0;
            }
        });
    </script>
@endsection