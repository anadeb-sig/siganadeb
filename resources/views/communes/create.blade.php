<div class="modal fade add_commune_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('communes.store') }}" accept-charset="UTF-8" id="form" class="form-horizontal">
                    {{ csrf_field() }}
                    @include ('communes.form')
                    <!-- Pied du formulaire -->
                    @include('button.button')
                    <?php                     
                        $attribut = "add_commune_btn"; 
                        echo buttons($attribut);
                    ?>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->