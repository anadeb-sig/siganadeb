<div class="card">
    <div class="modal-header">
        Localisation géographique du site
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                <label for="region_id" class="control-label">Région</label>
                <select class="form-control region_id" id="region_comm" name="prefecture_id" required="true">
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
                <select class="form-control commune_id" id="commune_comm" name="commune_id" required="true" disabled>
                        <option value="" disabled selected>Selectionner la commune</option>
                </select>        
                {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label class="control-label">Village</label>
                <select class="form-control village_id" id="village_comm" name="village_id" disabled required="true">
                    <option value="" disabled selected>Selectionner le village</option>
                </select>
                {!! $errors->first('nom_vill', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>



<div class="card mt-4">
    <div class="modal-header">
        Information sur le site d'ouvrages
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 {{ $errors->has('nom_site') ? 'has-error' : '' }}">
                <label for="nom_site" class="control-label">{{ __('Nom du site') }}</label>
                <input class="form-control majuscules" name="nom_site" type="text" value="" min="0" max="150" placeholder="{{ __('Entrer le nom du site') }}">
                {!! $errors->first('nom_site', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-md-6 {{ $errors->has('budget') ? 'has-error' : '' }}">
                <label for="budget" class="control-label">{{ __('Budget glabale estimé (cfa)') }}</label>
                <input class="form-control" name="budget" type="number" value="" placeholder="{{ __('Entrer un montant') }}">
                {!! $errors->first('budget', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12 {{ $errors->has('descrip') ? 'has-error' : '' }}">
                <label for="descrip" class="control-label">{{ __('Description') }}</label>
                <textarea class="form-control majuscules" name="descrip_site" type="text" rows="5" value="" maxlength="250" placeholder="{{ __('Entrer la description') }}"></textarea>
                {!! $errors->first('descrip', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <div  id="container">
        </div>
        <a class="btn btn-success mt-3" onclick="ajouterChamp()">Ajouter l'ouvrage au site</a>
    </div>
</div>