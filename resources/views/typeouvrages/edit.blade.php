<!-- Full screen modal content -->
<div class="modal fade edit_typeouvrage_modal" tabindex="-1" aria-labelledby="edit_typeouvrage_modalLabel" aria-hidden="true">
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

                <form method="POST" action="/typeouvrages/typeouvrage/" id="form" accept-charset="UTF-8" class="form-horizontal">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PUT">
                    @include ('typeouvrages.form_edit')
                    <!-- Pied du formulaire -->
                    <?php                     
                        $attribut = "edit_typeouvrage_btn"; 
                        echo buttons($attribut);
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>