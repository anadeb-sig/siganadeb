<div class="modal fade add_realisation_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
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
                <form method="POST" action="{{ route('realisations.store') }}" accept-charset="UTF-8" id="form" name="create_realisation_form" class="form-horizontal">
                {{ csrf_field() }}
                    @include ('realisations.form')
                    <!-- Pied du formulaire -->
                    @include('button.button')
                    <?php                     
                        $attribut = "add_realisation_btn"; 
                        echo buttons($attribut);
                    ?>
                    
                </form>
            </div>
        </div>
    </div>
</div>


