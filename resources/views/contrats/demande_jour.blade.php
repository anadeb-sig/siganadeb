<div class="modal fade add_demande_jour_modal" id="exampleModalLg" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">  
                <form method="POST" action="{{ route('demandejours.store') }}" accept-charset="UTF-8" id="form" class="form-horizontal">
                {{ csrf_field() }}
                    @include ('demande_jours.form')
                    <div class="modal-footer mt-3">
                        <button class="btn btn-outline-danger" type="button" data-bs-dismiss="modal">
                            Fermer
                        </button> 
                        &nbsp;&nbsp;                   
                        <button id="add_demadeJour_btn" type="submit" class="btn btn-outline-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


