
<div class="card">
    <div class="card-header">
        <div class="d-sm-flex align-items-center justify-content-between mb-0 mt-0">
            <h1 class="h3 mb-0 text-gray-800">Site et ouvrages</h1>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-6">
                <label for="region_id" class="control-label">Région</label>
                <select class="form-control region-comm" id="region_comm" required="true">
                    <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}">
                            {{ $region->nom_reg }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-6">
                <label for="commune_id" class="control-label">Commune</label>
                <select class="form-control commune-comm" id="commune_comm" required="true" disabled>
                    <option value="" disabled selected>Selectionner la commune</option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-xl-12">
                <label for="site_id" class="control-label">Site à construire</label>
                <select class="form-control site-comm" id="site_comm" disabled required="true">
                    <option value="" disabled selected>Sélectionnez le site à construire</option>
                </select>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-sm-12 mb-sm-0" id="ouvrages_site">
            </div>
        </div>
        <div  id="container">
        </div>
    </div>
    <hr class="mt-0">
    <div class="row">
        <div class="col-xl-12">
            <a class="btn btn-outline-success btnform" onclick="ajouterChamp()">
                <i class="fas fa-plus"></i> 
                &nbsp;site
            </a>
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

