<!-- staticBackdrop Modal -->
<div class="modal fade delete_village_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="delete_villageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- end modalheader -->
            <div class="modal-body">
                <p>Voulez-vous vraiment supprimer ?</p>
            </div>
            <!-- end modalbody -->

            <!-- Pied du formulaire -->
            <?php                     
            $attribut = "btn_delete_village"; 
                echo buttons($attribut);
            ?>
        </div>
    </div>
</div><!-- end modal -->