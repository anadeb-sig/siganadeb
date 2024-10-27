<input class="form-control" name="contrat_id" type="hidden" id="contrat_id">
<input class="form-control" name="date_fin" type="hidden" id="date_fin">

<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('titre') ? 'has-error' : '' }}">
        <label for="titre" class="control-label">{{ __('Titre de la demande') }}</label>
        <input class="form-control" name="titre" type="text" id="titre">
    </div>
    <div class="col-xl-6 {{ $errors->has('nbJr') ? 'has-error' : '' }}">
        <label for="nbJr" class="control-label">{{ __('Nombre de jour à prolonger') }}</label>
        <input class="form-control" name="nbJr" type="number" id="nbJr">
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-12 {{ $errors->has('description') ? 'has-error' : '' }}">
        <label for="description" class="control-label">{{ __('Redaction de la demande de suspension') }}</label>
        <textarea class="form-control" rows="5" name="description" type="text" id="description" maxlength="250" placeholder="{{ __('Niveau d\'exécution') }}"></textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>