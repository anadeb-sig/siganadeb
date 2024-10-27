<div class="modal fade add_entreprise_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
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
                <form method="POST" action="{{ route('entreprises.store') }}" accept-charset="UTF-8" id="create_entreprise_form" name="create_entreprise_form" class="form-horizontal">
                {{ csrf_field() }}
                    @include ('entreprises.form')
                    
                    <!-- Pied du formulaire -->
                    @include('button.button')
                    <?php                     
                        $attribut = "add_entreprise_btn"; 
                        echo buttons($attribut);
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>


