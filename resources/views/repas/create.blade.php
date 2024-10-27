<div class="modal fade add_repas_modal" id="add_repas_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="saveform_errList_1" style="list-style: none;"></ul>
                @include ('repas.form')

                <!-- Pied du formulaire -->
                @include('button.button')
                 
                <div class="modal-footer mt-3">
                    <button class="btn btn-outline-danger" type="button" data-bs-dismiss="modal">
                        Fermer
                    </button> 
                    &nbsp;&nbsp;                   
                    <button type="submit" class="btn btn-outline-primary add_repas_btn">Valider</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->