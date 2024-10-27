<div class="card">
    <div class="card-header">
        LOCALISATION DE L'OUVRAGE
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-6 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                <label for="region_id" class="control-label">Région</label>
                <select class="form-control region_id" id="region_contrat" name="prefecture_id" required="true">
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
                <select class="form-control commune_id" id="commune_contrat" name="commune_id" required="true" disabled>
                        <option value="" disabled selected>Selectionner la commune</option>
                </select>        
                {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
<div class="row mt-4">
    <div class="col-xl-12 {{ $errors->has('site_id') ? 'has-error' : '' }}">
        <label for="site_id" class="control-label">{{ __('Site') }}</label>
        <select class="form-control site_contrat" id="site_contrat" name="site_id" disabled required="true">
            <option value="" disabled selected>{{ __('Sélectionnez le site') }}</option>
        </select>
        {!! $errors->first('site_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mb-3">
    <div class="col-xl-12">
        <div id="ouvrage_galerie">
        </div>
    </div>    
</div>
<div  id="container">
</div>

<a class="btn btn-success mt-3" id="btn-ajouter-champ" onclick="ajouterChamp()">Ajouter une photo d'ouvrage</a>