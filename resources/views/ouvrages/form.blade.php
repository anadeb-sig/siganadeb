<div class="card">
    <div class="modal-header">
        Localisation géographique du site
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                <label for="region_id" class="control-label">Région</label>
                <select class="form-control region_id" id="region_comm" required="true">
                    <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}">
                            {{ $region->nom_reg }}
                        </option>
                    @endforeach
                </select>        
                {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-md-6 {{ $errors->has('commune_id') ? 'has-error' : '' }}">                
                <label for="commune_id" class="control-label">Commune</label>
                <select class="form-control" id="commune_comm" required="true" disabled>
                        <option value="" disabled selected>Selectionner la commune</option>
                </select>        
                {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <label class="control-label">Site d'ouvrages</label>
                <select class="form-control" id="site_comm" name="site_id" disabled required="true">
                    <option value="" disabled selected>Selectionner le site</option>
                </select>
                {!! $errors->first('nom_site', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>


<div class="row mt-4">
    <div class="col-xl-4 {{ $errors->has('nom_ouvrage') ? 'has-error' : '' }}">
        <label for="nom_ouvrage" class="control-label">{{ __('Nom de l\'ouvrage') }}</label>
        <input class="form-control majuscules" name="nom_ouvrage" type="text" id="nom_ouvrage" value="" min="0" max="150" placeholder="{{ __('Entrer le nom de l\'ouvrage') }}">
        {!! $errors->first('nom_ouvrage', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-4 {{ $errors->has('typeouvrage_id') ? 'has-error' : '' }}">
        <label for="typeouvrage_id" class="control-label">{{ __('Type de l\'ouvrage') }}</label>
        <select class="form-control" id="typeouvrage_id" name="typeouvrage_id" required="true">
        	    <option value="" style="display: none;" disabled selected>{{ __('Selectionner le type de l\'ouvrage') }}</option>
        	@foreach ($Typeouvrages as  $Typeouvrage)
			    <option value="{{ $Typeouvrage->id }}">
			    	{{ $Typeouvrage->nom_type }}
			    </option>
			@endforeach
        </select>
        {!! $errors->first('typeouvrage_id', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-4 {{ $errors->has('nom_pr') ? 'has-error' : '' }}">
        <label for="statu" class="control-label">Status</label>
        <select class="form-control" name="statu">
            <option value="" style="display: none;" disabled selected>Selectionner le status</option>
            <option value="NON_DEMARRE">NON DEMARRE</option>
            <option value="EC">EN COURS</option>
            <option value="RT">RECEPTION TECHNIQUE</option>
            <option value="RP">RECEPTION PROVISOIRE</option>
            <option value="RD">RECEPTION DEFINITIVE</option>
            <option value="SUSPENDU">SUSPENDU</option>
        </select>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-6 {{ $errors->has('projet_id') ? 'has-error' : '' }}">
        <label for="projet_id" class="control-label">{{ __('Projet / Programme') }}</label>
        <select class="form-control" id="projet_id" name="projet_id" required="true">
        	    <option value="" style="display: none;" disabled selected>{{ __('Selectionner le projet') }}</option>
        	@foreach ($projets as $projet)
			    <option value="{{ $projet->id }}">
			    	{{ $projet->name }}
			    </option>
			@endforeach
        </select>
        {!! $errors->first('projet_id', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('financement_id') ? 'has-error' : '' }}">
        <label for="financement_id" class="control-label">Financement</label>
        <select class="form-control" name="financement_id" value="" required>
        	<option value="" style="display: none;" disabled selected>Select financement</option>
        	@foreach ($Financements as $Financement)
			    <option value="{{ $Financement->id }}">
			    	{{ $Financement->nom_fin }}
			    </option>
			@endforeach
        </select>
        {!! $errors->first('financement_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="row mt-4">
    <div class="col-md-12 {{ $errors->has('descrip') ? 'has-error' : '' }}">
        <label for="descrip" class="control-label">{{ __('Description') }}</label>
        <textarea class="form-control majuscules" name="descrip" type="text" id="descrip" rows="5" value="" maxlength="250" placeholder="{{ __('Entrer la description') }}"></textarea>
        {!! $errors->first('descrip', '<p class="help-block">:message</p>') !!}
    </div>
</div>
