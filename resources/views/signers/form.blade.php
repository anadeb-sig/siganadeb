
<div class="form-group {{ $errors->has('contrat_id') ? 'has-error' : '' }}">
    <label for="contrat_id" class="col-xl-2 control-label">{{ trans('signers.contrat_id') }}</label>
    <div class="col-xl-10">
        <select class="form-control" id="contrat_id" name="contrat_id" required="true">
        	    <option value="" style="display: none;" {{ old('contrat_id', optional($signer)->contrat_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('signers.contrat_id__placeholder') }}</option>
        	@foreach ($Contrats as $key => $Contrat)
			    <option value="{{ $key }}" {{ old('contrat_id', optional($signer)->contrat_id) == $key ? 'selected' : '' }}>
			    	{{ $Contrat }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('contrat_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('ouvrage_id') ? 'has-error' : '' }}">
    <label for="ouvrage_id" class="col-xl-2 control-label">{{ trans('signers.ouvrage_id') }}</label>
    <div class="col-xl-10">
        <select class="form-control" id="ouvrage_id" name="ouvrage_id" required="true">
        	    <option value="" style="display: none;" {{ old('ouvrage_id', optional($signer)->ouvrage_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('signers.ouvrage_id__placeholder') }}</option>
        	@foreach ($Ouvrages as $key => $Ouvrage)
			    <option value="{{ $key }}" {{ old('ouvrage_id', optional($signer)->ouvrage_id) == $key ? 'selected' : '' }}>
			    	{{ $Ouvrage }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('ouvrage_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('entreprise_id') ? 'has-error' : '' }}">
    <label for="entreprise_id" class="col-xl-2 control-label">{{ trans('signers.entreprise_id') }}</label>
    <div class="col-xl-10">
        <select class="form-control" id="entreprise_id" name="entreprise_id" required="true">
        	    <option value="" style="display: none;" {{ old('entreprise_id', optional($signer)->entreprise_id ?: '') == '' ? 'selected' : '' }} disabled selected>{{ trans('signers.entreprise_id__placeholder') }}</option>
        	@foreach ($Entreprises as $key => $Entreprise)
			    <option value="{{ $key }}" {{ old('entreprise_id', optional($signer)->entreprise_id) == $key ? 'selected' : '' }}>
			    	{{ $Entreprise }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('entreprise_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('date_sign') ? 'has-error' : '' }}">
    <label for="date_sign" class="col-xl-2 control-label">{{ trans('signers.date_sign') }}</label>
    <div class="col-xl-10">
        <input class="form-control" name="date_sign" type="text" id="date_sign" value="{{ old('date_sign', optional($signer)->date_sign) }}" placeholder="{{ trans('signers.date_sign__placeholder') }}">
        {!! $errors->first('date_sign', '<p class="help-block">:message</p>') !!}
    </div>
</div>

