<input class="form-control id" name="id" type="hidden" id="id">

<div class="row">
    <div class="col-xl-6 {{ $errors->has('code') ? 'has-error' : '' }}">
        <label for="code" class="control-label">{{ __('Code du contrat') }}</label>
        <input class="form-control code @error('code') is-invalid @enderror" value="{{ old('code') }}" 
       pattern="\d{4}\/\d{4}\/[A-Z]{3}\/ANADEB\/[A-Z]{1,2}\/[A-Z]{2,3}" name="code" type="text" id="code_edit" placeholder="{{ __('Code du contrat') }}" required="true">
    </div>
    <div class="col-xl-6 {{ $errors->has('date_sign') ? 'has-error' : '' }}">
        <label for="date_sign" class="control-label">{{ __('Date signature du contrat') }}</label>
        <input class="form-control date_sign" name="date_sign" type="date" id="date_sign" value="" placeholder="">
        {!! $errors->first('date_sign', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-6 {{ $errors->has('date_debut') ? 'has-error' : '' }}">
        <label for="date_debut" class="control-label">{{ __('Ordre de service') }}</label>
        <input class="form-control date_debut" name="date_debut" type="date" id="date_debut" value="" placeholder="{{ trans('contrats.date_debut__placeholder') }}">
        {!! $errors->first('date_debut', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('entier') ? 'has-error' : '' }}">
        <label for="entier" class="control-label">{{ __('Durée du contrat (En jour)') }}</label>
        <input class="form-control entier_edit" name="entier" type="number" id="entier" value="" placeholder="{{ __('Durée du contrat') }}">
        {!! $errors->first('entier', '<p class="help-block">:message</p>') !!}
    </div>
</div>

