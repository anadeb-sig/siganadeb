<input class="form-control id" name="id" type="hidden" id="id">

<div class="row">
    <div class="col-xl-6 {{ $errors->has('nbr_mamF') ? 'has-error' : '' }}">
        <label for="date_debut" class="control-label">Date du démarrage de cantine</label>
        <input class="form-control date_debut" name="date_debut" type="date" required id="date_debut">
        {!! $errors->first('date_debut', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('nbr_pre') ? 'has-error' : '' }}">
        <label for="date_fin" required class="control-label">Date fin de cantine</label>
        <input class="form-control date_fin" name="date_fin" type="date" id="date_fin">
        {!! $errors->first('date_fin', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('effec_fil') ? 'has-error' : '' }}">
        <label class="form-label">Effectif des filles</label>
        <input class="form-control effec_fil" name="effec_fil" type="number" min="0" id="effec_fil"  placeholder="Effectif à renseigner, ex: 10"  required="true">
        {!! $errors->first('effec_fil', '<p class="help-block">:message</p>') !!}
    </div><!-- end col -->
    <div class="col-xl-6 {{ $errors->has('effec_gar') ? 'has-error' : '' }}">
        <label class="form-label">Effectif des garçons</label>
        <input class="form-control effec_gar" name="effec_gar" type="number" id="effec_gar" min="0"  placeholder="Effectif à renseigner, ex: 10"  required="true">
        {!! $errors->first('effec_gar', '<p class="help-block">:message</p>') !!}
    </div><!-- end col -->
</div>


<div class="card mt-4">
    <div class="card-header">
        INFORMATION COMPLEMENTAIRE SUR LA CANTINE
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
        <div class="row mb-4">
            <div class="col-xl-6 {{ $errors->has('nbr_pre') ? 'has-error' : '' }}">
                <label for="date_fin" required class="control-label">Status de l'école?</label>
                <div class="form-check form-switch" style="padding-top: 12px;">
                    <label class="form-check-label" for="flexSwitchCheckChecked"  style="padding-left: 10px;">Non demarrée pour l'année</label>
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"  style="width: 40px; height: 20px;">
                    <input class="form-control" name="status" id="status_edit" type="hidden" value="0">
                    <input class="form-control classe_id" name="classe_id" type="hidden" value="0">
                </div>
            </div>
            <div class="col-xl-6 {{ $errors->has('nbr_gr') ? 'has-error' : '' }}">
                <label for="nbr_gr" class="control-label">Nombre de group</label>
                <input class="form-control nbr_gr" name="nbr_gr" type="number" required id="nbr_gr" min="0" placeholder="Entrer le nombre du cours primaire...">
                {!! $errors->first('nbr_gr', '<p class="help-block">:message</p>') !!}
            </div>
        </div><!-- end row -->
    </div><!-- end col -->
</div><!-- end row -->

