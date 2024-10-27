<div class="row {{ $errors->has('nom_cla') ? 'has-error' : '' }}">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                PRE-SCOLAIRE (INSCRITS)
            </div>
            <div class="card-body">
                <div class="row">
                    <input class="form-control" id="nom_cla" name="nom_cla[]" value="Pré_scolaire" type="hidden">
                </div>
                <div class="row">
                    <div class="{{ $errors->has('effec_fil') ? 'has-error' : '' }}">
                        <label class="form-label">Effectif des filles</label>
                        <input class="form-control effec_fil" name="effec_fil[]" type="number" min="0" id="effec_fil"  placeholder="Effectif à renseigner, ex: 10"  required="true">
                        {!! $errors->first('effec_fil', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="{{ $errors->has('effec_fil') ? 'has-error' : '' }}">
                        <label class="form-label">Effectif des garçons</label>
                        <input class="form-control effec_gar" name="effec_gar[]" type="number" min="0" id="effec_gar"  placeholder="Effectif à renseigner, ex: 10"  required="true">
                        {!! $errors->first('effec_gar', '<p class="help-block">:message</p>') !!}
                    </div><!-- end col -->
                </div>
            </div>
            <!-- end card body -->
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                PRIMAIRE (INSCRITS)
            </div>
            <div class="card-body">
                <div class="row">
                    <input class="form-control" name="nom_cla[]" value="Primaire" type="hidden">
                </div>
                <div class="row">
                    <div class="{{ $errors->has('effec_fil') ? 'has-error' : '' }}">
                        <label class="form-label">Effectif des filles</label>
                        <input class="form-control effec_fil" name="effec_fil[]" type="number" min="0" id="effec_fil"  placeholder="Effectif à renseigner, ex: 10"  required="true">
                        {!! $errors->first('effec_fil', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="{{ $errors->has('effec_fil') ? 'has-error' : '' }}">
                        <label class="form-label">Effectif des garçons</label>
                        <input class="form-control effec_gar" name="effec_gar[]" type="number" min="0" id="effec_gar"  placeholder="Effectif à renseigner, ex: 10"  required="true">
                        {!! $errors->first('effec_gar', '<p class="help-block">:message</p>') !!}
                    </div><!-- end col -->
                </div>
            </div>
            <!-- end card body -->
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        DONNEES DE LOCALISATION ET CANTINE
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                <label for="region_id" class="control-label">Région</label>
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
                
                <label for="commune_id" class="control-label">Commune</label>
                <select class="form-control" id="commune_comm" required="true" disabled>
                        <option value="" disabled selected>Selectionner le canton</option>
                </select>        
                {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-xl-4">
                <label for="ecole_id" class="control-label">Cantine</label>
                <select class="form-control ecole_id" id="ecole_comm" name="ecole_id" disabled required="true">
                    <option value="" disabled selected>Selectionner l'école</option>
                </select>
            </div>
        </div>
    </div>
</div>