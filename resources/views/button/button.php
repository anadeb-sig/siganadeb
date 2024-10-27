<!-- Fonction pour les boutons -->
<?php 
    function buttons($attribut){
        return   '<div class="modal-footer mt-3">
                    <button class="btn btn-outline-danger" type="button" data-bs-dismiss="modal">
                        Fermer
                    </button> 
                    &nbsp;&nbsp;                   
                    <button id="'.$attribut.'" type="submit" class="btn btn-outline-primary">Valider</button>
                </div> ';
    }
?>