<!-- staticBackdrop Modal -->
<div class="modal fade delete_inscrit_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="delete_inscritLabel" aria-hidden="true">
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
            <?php                     
                $attribut = "btn_delete_inscrit"; 
                echo buttons($attribut);
            ?>
        </div>
    </div>
</div><!-- end modal -->