@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
            <h1 class="h3 mb-0 text-gray-800">
                <a href="{{ route('regions.index') }}" style="color: #08764f;">
                    <i class='fas fa-arrow-circle-left'></i>
                </a>&nbsp;&nbsp;
                Liste des cantines bénéficiaires
            </h1>
                <button class="btn btn-outline-primary" id="export-btn">Export en excel</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-12">
                    <div id="liste_ecoles"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="module" src="{{ asset('./assets/gridjs/gridjs.parregion.js') }}"></script>
    <script>
            function hideRow(table,rowIndex){
                var elt = document.getElementById('liste_ecoles');
                for (var i = 0, row; row = elt.length; i++) {
                    if(rowIndex==i){
                        row.className ="hiddenclass";
                    }
                }        
            }

            function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('liste_ecoles');
            hideRow(  elt,2);
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1", ignoreHiddenRows: true });
            
                return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('liste_ecoles.' + (type || 'xlsx')));
            }
        </script>
@endsection