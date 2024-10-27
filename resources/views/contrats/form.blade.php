
<div class="card">
    <div class="modal-header">
        Localisation géographique et ouvrages à construire
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-6 {{ $errors->has('region_id') ? 'has-error' : '' }}">
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
            <div class="col-xl-6 {{ $errors->has('commune_id') ? 'has-error' : '' }}">
                
                <label for="commune_id" class="control-label">Commune</label>
                <select class="form-control" id="commune_comm" required="true" disabled>
                        <option value="" disabled selected>Selectionner la commune</option>
                </select>        
                {!! $errors->first('canton_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-xl-12 {{ $errors->has('site_id') ? 'has-error' : '' }}">
                <label for="site_id" class="control-label">{{ __('Site à construire') }}</label>
                <select class="form-control" id="site_comm" disabled required="true">
                    <option value="" disabled selected>{{ __('Sélectionnez le site à construire') }}</option>
                </select>
                {!! $errors->first('site_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 mb-3 mt-3 mb-sm-0" id="ouvrages_site">
            </div>
        </div>

    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-12 {{ $errors->has('code') ? 'has-error' : '' }}">
        <label for="code" class="control-label">{{ __('Code du contrat') }}</label>
        <input class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" 
       pattern="\d{4}\/\d{4}\/[A-Z]{3}\/ANADEB\/[A-Z]{1,2}\/[A-Z]{2,3}" name="code" type="text" id="code" placeholder="{{ __('Code du contrat') }}" required="true">
    </div>
</div>
<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('entreprise_id') ? 'has-error' : '' }}">
        <label for="entreprise_id" class="control-label">{{ __('Entreprise') }}</label>
        <select class="form-control" id="entreprise_id" name="entreprise_id" required="true">
        	    <option value="" style="display: none;" disabled selected>{{ __('Sélectionnez l\'entreprise') }}</option>
        	@foreach ($Entreprises as $key => $Entreprise)
			    <option value="{{ $key }}">
			    	{{ $Entreprise }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('entreprise_id', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('date_sign') ? 'has-error' : '' }}">
        <label for="date_sign" class="control-label">{{ __('Date signature du contrat') }}</label>
        <input class="form-control" name="date_sign" type="date" id="date_sign" value="" placeholder="{{ trans('signers.date_sign__placeholder') }}">
        {!! $errors->first('date_sign', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-6 {{ $errors->has('date_debut') ? 'has-error' : '' }}">
        <label for="date_debut" class="control-label">{{ __('Ordre de service') }}</label>
        <input class="form-control" name="date_debut" type="date" id="date_debut" value="" placeholder="{{ trans('contrats.date_debut__placeholder') }}">
        {!! $errors->first('date_debut', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('entier') ? 'has-error' : '' }}">
        <label for="entier" class="control-label">{{ __('Durée du contrat') }}</label>
        <input class="form-control" name="entier" type="number" id="entier" value="" placeholder="{{ __('Durée du contrat (En jour)') }}">
        {!! $errors->first('entier', '<p class="help-block">:message</p>') !!}
    </div>
</div>

