<!-- Full screen modal content -->
<div class="modal fade edit_suivi_modal" tabindex="-1" aria-labelledby="edit_suivi_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form id="form" method="POST" action="/suivis/suivi/" id="form" accept-charset="UTF-8" class="form-horizontal"  enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PUT">
                    @include ('suivis.form_edit')
                    <hr>
                    <div class="row" style="float: right;">
                        <div class="col-ms-12 mt-2">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</button>
                            <button id="edit_suivi_btn" type="submit" class="btn btn-outline-primary">Valider</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>