<div class="modal fade telecharger_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="row">
                    <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                        <select class="form-control" id="region_comm" required="true">
                            <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">
                                    {{ $region->nom_reg }}
                                </option>
                            @endforeach
                        </select>        
                        {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="col-xl-4 {{ $errors->has('commune_id') ? 'has-error' : '' }}">
                        <select class="form-control" id="commune_comm" name="commune_id" required="true" disabled>
                                <option value="" disabled selected>Selectionner la commune</option>
                        </select>        
                        {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="col-xl-4 {{ $errors->has('ouvrage_id') ? 'has-error' : '' }}">
                        <select class="form-control" id="ouvrage_comm" name="ouvrage_id" required="true" disabled>
                                <option value="" disabled selected>Selectionner l'ouvrage</option>
                        </select>        
                        {!! $errors->first('ouvrage_id', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-xl-10">
                    </div>
                    <div class="col-xl-2" style="text-align: right;">
                        <button class="btn btn-outline-primary csvButton">Télécharger!</button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->