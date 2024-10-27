<input class="form-control" name="site_id" type="hidden" id="site_id">

<div class="row mt-4">
    <div class="col-xl-4 {{ $errors->has('date_debut_susp') ? 'has-error' : '' }}">
        <label for="titre" class="control-label">{{ __('Date debut de la suspension') }}</label>
        <input class="form-control" name="date_debut_susp" type="date" id="date_debut_susp">
    </div>
    <div class="col-xl-4 {{ $errors->has('titre') ? 'has-error' : '' }}">
        <label for="titre" class="control-label">{{ __('Titre de la demande') }}</label>
        <input class="form-control" name="titre" type="text" id="titre">
    </div>
    <div class="col-xl-4 {{ $errors->has('nbJr') ? 'has-error' : '' }}">
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