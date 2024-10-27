<div class="modal fade add_site_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
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
                <form method="POST" action="{{ route('sites.store') }}" accept-charset="UTF-8" id="form" name="create_ouvrage_form" class="form-horizontal">
                {{ csrf_field() }}
                    @include ('sites.form', [
                                            'ouvrage' => null,
                                        ])

                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger" type="button" data-bs-dismiss="modal">
                            Fermer
                        </button> 
                        &nbsp;&nbsp;                   
                        <button id="add_site_btn" type="submit" class="btn btn-outline-primary">Valider</button>
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>


