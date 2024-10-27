<div class="modal fade add_galerie_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
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
                <form method="POST" id="form" action="{{ route('suivis.galerie.store') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}
                    @include ('galeries.form')
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger" type="button" data-bs-dismiss="modal">
                            Fermer
                        </button> 
                        &nbsp;&nbsp;                   
                        <button id="add_galerie_btn" type="submit" class="btn btn-outline-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


