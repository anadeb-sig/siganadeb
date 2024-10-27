<input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
<input class="form-control id" name="id" type="hidden" id="id">
<div class="card">
    <div class="modal-header">
        LOCALISATION DE L'OUVRAGE VISITE
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                <label for="region_id" class="control-label">Région</label>
                <select class="form-control" id="region_contrat_edit" name="prefecture_id" required="true">
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
                <select class="form-control" id="commune_contrat_edit" name="commune_id" required="true" disabled>
                        <option value="" disabled selected>Selectionner la commune</option>
                </select>        
                {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-xl-4 {{ $errors->has('site_id') ? 'has-error' : '' }}">
                <label for="site_id" class="control-label">{{ __('Site visité') }}</label>
                <select class="form-control" id="site_contrat_edit" name="site_id" disabled required="true">
                    <option value="" disabled selected>{{ __('Sélectionnez le site visité') }}</option>
                </select>
                {!! $errors->first('site_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('date_suivi') ? 'has-error' : '' }}">
        <label for="date_suivi" class="control-label">{{ __('Date de suivi') }}</label>
        <input class="form-control date_suivi" name="date_suivi" type="date">
        {!! $errors->first('date_suivi', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('conf_plan') ? 'has-error' : '' }}">
        <label for="conf_plan" class="control-label">{{ __('Confirmation du plan en cours d\'exécution') }}</label>
        <div class="form-check">
            <input class="form-check-input conf_plan" id="conf_plan1" type="radio" value="Oui" name="conf_plan" checked>
            <label class="form-check-label" for="conf_plan1">Oui</label>
        </div>
        <div class="form-check">
            <input class="form-check-input conf_plan" id="conf_plan2" type="radio" value="Non" name="conf_plan">
            <label class="form-check-label" for="conf_plan2">Non</label>
        </div>        
        {!! $errors->first('conf_plan', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('niveau_exe') ? 'has-error' : '' }}">
        <label for="niveau_exe" class="control-label">{{ __('Niveau d\'exécution de l\'ouvrage') }}</label>
        <textarea class="form-control niveau_exe majuscules" rows="5" name="niveau_exe" type="text" maxlength="250" placeholder="{{ __('Niveau d\'exécution') }}"></textarea>
        {!! $errors->first('niveau_exe', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('recomm') ? 'has-error' : '' }}">
        <label for="recomm" class="control-label">{{ __('Les recommandations') }}</label>
        <textarea class="form-control recomm majuscules" rows="5" name="recomm" type="text" maxlength="250" placeholder="{{ __('Entrer les récommandations') }}"></textarea>
        {!! $errors->first('descrip', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-12 {{ $errors->has('niv_eval') ? 'has-error' : '' }}">
        <label for="niv_eval" class="control-label">{{ __('Indice d\'évolution du site') }}</label>
        <input class="form-control" name="niv_eval" type="number" id="niv_eval" placeholder="Exemple: 10">
        {!! $errors->first('niv_eval', '<p class="help-block">:message</p>') !!}
    </div>
</div>

