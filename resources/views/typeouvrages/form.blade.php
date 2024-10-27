
<div class="row">
    <div class="col-md-12  {{ $errors->has('nom_type') ? 'has-error' : '' }}">
        <label for="nom_type" class="control-label">{{ __('Type de l\'ouvrage') }}</label>
        <input class="form-control majuscules" name="nom_type" type="text" id="nom_type" value="" placeholder="{{ __('Entrer le type ouvrage') }}">
        {!! $errors->first('nom_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

