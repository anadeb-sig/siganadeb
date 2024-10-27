<div class="modal fade telecharger_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Région -->
                <div class="card mt-4">
                    <div class="modal-header">
                        Niveau région
                    </div>
                    <div class="card-body">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                                <select class="form-control" name="region_id" id="region_id" required="true">
                                    <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                                    @foreach ($regions as $region)
                                        <option  data-id="{{ $region->id }}" value="{{ $region->id }}">
                                            {{ $region->nom_reg }}
                                        </option>
                                    @endforeach
                                </select>        
                                {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-xl-6"></div>
                            <div class="col-xl-2">
                                <button type="submit" class="btn btn-outline-primary csvButton">Télécharger!</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- Préfecture -->
                <div class="card mt-4">
                    <div class="modal-header">
                        Niveau préfecture
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                                <select class="form-control" id="region_format"  required="true">
                                    <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                                    @foreach ($regions as $region)
                                        <option  value="{{ $region->id }}">
                                            {{ $region->nom_reg }}
                                        </option>
                                    @endforeach
                                </select>        
                                {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-xl-4 {{ $errors->has('prefecture_id') ? 'has-error' : '' }}">
                                <select class="form-control" name="prefecture_id" id="prefecture_format" required="true" disabled>
                                        <option value="" disabled selected>Selectionner la préfecture</option>
                                </select>        
                                {!! $errors->first('prefecture_id', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-xl-2"></div>
                            <div class="col-xl-2">
                                <button type="submit" class="btn btn-outline-primary csvButton">Télécharger!</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- Commune -->
                <div class="card mt-4">
                    <div class="modal-header">
                        Niveau commune
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                                <select class="form-control" id="region_edit_site" required="true">
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
                                <select class="form-control" id="commune_edit_site" name="commune_id" required="true" disabled>
                                        <option value="" disabled selected>Selectionner la commune</option>
                                </select>        
                                {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-xl-2"></div>
                            <div class="col-xl-2">
                                <button type="submit" class="btn btn-outline-primary csvButton">Télécharger!</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Canton -->
                <div class="card mt-4">
                    <div class="modal-header">
                        Niveau canton
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                                <select class="form-control" id="region_comm_site" required="true">
                                    <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                                    @foreach ($regions as $region)
                                        <option data-reg="{{ $region->nom_reg }}" value="{{ $region->id }}">
                                            {{ $region->nom_reg }}
                                        </option>
                                    @endforeach
                                </select>        
                                {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-xl-4 {{ $errors->has('commune_id') ? 'has-error' : '' }}">
                                <select class="form-control" id="commune_comm_site" disabled  required="true">
                                        <option value="" disabled selected>Selectionner la commune</option>
                                </select>        
                                {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
                            </div>
                            <div class="col-xl-3">
                                <select class="form-control" id="canton_comm_site" name="canton_id" disabled required="true">
                                    <option value="" disabled selected>Selectionner le canton</option>
                                </select>
                            </div>
                            <div class="col-xl-2">
                                <button type="submit" class="btn btn-outline-primary csvButton">Télécharger!</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer mt-3">
                    <button class="btn btn-outline-danger" type="button" data-bs-dismiss="modal">
                        Fermer
                    </button> 
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->