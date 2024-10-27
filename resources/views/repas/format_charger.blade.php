<div class="modal fade format_charger_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
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
                        <form action="{{ route('repas.format_telecharger') }}" method="post">
                        {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                                    <select class="form-control" name="region_id" required="true">
                                        <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                                        @foreach ($regions as $region)
                                            <option data-reg="{{ $region->nom_reg }}" value="{{ $region->id }}">
                                                {{ $region->nom_reg }}
                                            </option>
                                        @endforeach
                                    </select>        
                                    {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
                                </div>
                                <div class="col-xl-6"></div>
                                <div class="col-xl-2">
                                    <button type="submit" class="btn btn-outline-primary">Télécharger!</button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
                <!-- Préfecture -->
                <div class="card mt-4">
                    <div class="modal-header">
                        Niveau préfecture
                    </div>
                    <div class="card-body">
                        <form action="{{ route('repas.format_telecharger') }}" method="post">
                        {{ csrf_field() }}
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
                                    <button type="submit" class="btn btn-outline-primary">Télécharger!</button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
                <!-- Commune -->
                <div class="card mt-4">
                    <div class="modal-header">
                        Niveau commune
                    </div>
                    <div class="card-body">
                        <form action="{{ route('repas.format_telecharger') }}" method="post">
                        {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                                    <select class="form-control" id="region_edit" required="true">
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
                                    <select class="form-control" id="commune_edit" name="commune_id" required="true" disabled>
                                            <option value="" disabled selected>Selectionner la commune</option>
                                    </select>        
                                    {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
                                </div>
                                <div class="col-xl-2"></div>
                                <div class="col-xl-2">
                                    <button type="submit" class="btn btn-outline-primary">Télécharger!</button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
                <!-- Canton -->
                <div class="card mt-4">
                    <div class="modal-header">
                        Niveau canton
                    </div>
                    <div class="card-body">
                        <form action="{{ route('repas.format_telecharger') }}" method="post">
                        {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xl-3 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                                    <select class="form-control" id="region_comm" required="true">
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
                                    <select class="form-control" id="commune_comm" disabled  required="true">
                                            <option value="" disabled selected>Selectionner le canton</option>
                                    </select>        
                                    {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
                                </div>
                                <div class="col-xl-3">
                                    <select class="form-control" id="canton_comm" name="canton_id" disabled required="true">
                                        <option value="" disabled selected>Selectionner le canton</option>
                                    </select>
                                </div>
                                <div class="col-xl-2">
                                    <button type="submit" class="btn btn-outline-primary">Télécharger!</button>
                                </div>
                            </div>
                        </form>
                        
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