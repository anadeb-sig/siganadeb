<!-- Full screen modal content -->
<div class="modal fade edit_repas_modal" tabindex="-1" aria-labelledby="edit_repas_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="saveform_errList_edit"></ul>
                <input name="_method" type="hidden" value="PUT">
                @include ('repas.form_edit')
                <!-- Pied du formulaire -->
                <div class="modal-footer mt-3">
                    <button class="btn btn-outline-danger" type="button" data-bs-dismiss="modal">
                        Fermer
                    </button> 
                    &nbsp;&nbsp;                   
                    <button type="submit" class="btn btn-outline-primary edit_repas_btn">Valider</button>
                </div>
            </div>
        </div>
    </div>
</div>