<div class="modal fade add_visite_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('visites.store') }}" accept-charset="UTF-8" id="form" class="form-horizontal">
                    {{ csrf_field() }}
                    @include ('visites.form')
                    <!-- Pied du formulaire -->
                    @include('button.button')
                    <?php                     
                    $attribut = "add_visite_btn"; 
                        echo buttons($attribut);
                    ?>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->