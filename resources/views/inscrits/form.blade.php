<div class="row">
    <div class="col-xl-6 {{ $errors->has('nbr_mamF') ? 'has-error' : '' }}">
        <label for="date_debut" class="control-label">Date du démarrage de cantine</label>
        <input class="form-control" name="date_debut" type="date" required id="date_debut">
        {!! $errors->first('date_debut', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('nbr_pre') ? 'has-error' : '' }}">
        <label for="date_fin" required class="control-label">Date fin de cantine</label>
        <input class="form-control" name="date_fin" type="date" id="date_fin">
        {!! $errors->first('date_fin', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-4 {{ $errors->has('nom_cla') ? 'has-error' : '' }}">
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
                        <input class="form-control effec_fil" name="effec_fil[]" type="number" min="0"  placeholder="Effectif à renseigner, ex: 10"  required="true">
                        {!! $errors->first('effec_fil', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="{{ $errors->has('effec_fil') ? 'has-error' : '' }}">
                        <label class="form-label">Effectif des garçons</label>
                        <input class="form-control effec_gar" name="effec_gar[]" type="number" min="0"  placeholder="Effectif à renseigner, ex: 10"  required="true">
                        {!! $errors->first('effec_gar', '<p class="help-block">:message</p>') !!}
                    </div><!-- end col -->
                </div>
                <div class="row mt-4">
                    <div class="{{ $errors->has('nbr_gr') ? 'has-error' : '' }}">
                        <label for="nbr_gr" class="control-label">Nombre de group préscolaire</label>
                        <input class="form-control" name="nbr_gr[]" type="number" required id="nbr_gr" min="0" placeholder="Entrer le nombre du cours primaire...">
                        {!! $errors->first('nbr_gr', '<p class="help-block">:message</p>') !!}
                    </div>
                </div><!-- end col -->
                <div class="row mt-4">
                    <div class="{{ $errors->has('nbr_pre') ? 'has-error' : '' }}">
                        <label for="date_fin" required class="control-label">Status de l'école préscolaire?</label>
                        <div class="form-check form-switch" style="padding-top: 12px;">
                            <label class="form-check-label" for="flexSwitchCheckDefault"  style="padding-left: 10px;">Non demarrée pour l'année</label>
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"  style="width: 40px; height: 20px;">
                            <input class="form-control" name="status[]" id="statuspre" type="hidden" value="0">
                        </div>
                    </div>
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
                        <input class="form-control effec_fil" name="effec_fil[]" type="number" min="0"  placeholder="Effectif à renseigner, ex: 10"  required="true">
                        {!! $errors->first('effec_fil', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="{{ $errors->has('effec_fil') ? 'has-error' : '' }}">
                        <label class="form-label">Effectif des garçons</label>
                        <input class="form-control effec_gar" name="effec_gar[]" type="number" min="0"  placeholder="Effectif à renseigner, ex: 10"  required="true">
                        {!! $errors->first('effec_gar', '<p class="help-block">:message</p>') !!}
                    </div><!-- end col -->
                </div>
                <div class="row mt-4"">
                    <div class="{{ $errors->has('nbr_gr') ? 'has-error' : '' }}">
                        <label for="nbr_gr" class="control-label">Nombre de group primaire</label>
                        <input class="form-control" name="nbr_gr[]" type="number" required id="nbr_gr" min="0" placeholder="Entrer le nombre du cours primaire...">
                        {!! $errors->first('nbr_gr', '<p class="help-block">:message</p>') !!}
                    </div>
                </div><!-- end col -->
                <div class="row mt-4">
                    <div class="{{ $errors->has('nbr_pre') ? 'has-error' : '' }}">
                        <label for="date_fin" required class="control-label">Status de l'école primaire?</label>
                        <div class="form-check form-switch" style="padding-top: 12px;">
                            <label class="form-check-label" for="flexSwitchCheckDefault"  style="padding-left: 10px;">Non demarrée pour l'année</label>
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefaultpri"  style="width: 40px; height: 20px;">
                            <input class="form-control" name="status[]" id="statuspri" type="hidden" value="0">
                        </div>
                    </div>
                </div>
            </div>
            <!-- end card body -->
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        INFORMATION COMPLEMENTAIRE SUE LA CANTINE
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-xl-6 {{ $errors->has('nbr_ensg') ? 'has-error' : '' }}">
                <label for="nbr_ensg" class="control-label">Nombre d'enseignants</label>
                <input class="form-control nbr_ensg" required name="nbr_ensg" type="number" id="nbr_ensg" min="0" placeholder="Entrer nombre d'enseignants...">
                {!! $errors->first('nbr_ensg', '<p class="help-block">:message</p>') !!}
            </div><!-- end col -->
            <div class="col-xl-6 {{ $errors->has('nbr_mam') ? 'has-error' : '' }}">
                <label for="nbr_mam" class="control-label">Effectif de maman cantine</label>
                <input class="form-control nbr_mam" name="nbr_mam" type="number" id="nbr_mam" min="0" required placeholder="Entrer nombre de maman cantine...">
                {!! $errors->first('nbr_mamF', '<p class="help-block">:message</p>') !!}
            </div><!-- end col -->
        </div><!-- end row -->
    </div><!-- end col -->
</div><!-- end row -->

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
