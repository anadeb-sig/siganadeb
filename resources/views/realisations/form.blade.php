
<div class="row">
    <div class="col-xl-6">
        <label for="region_id" class="control-label">Région</label>
        <select class="form-control region-comm" id="region_edit" required="true">
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
        <select class="form-control commune-comm" id="commune_edit" required="true" disabled>
            <option value="" disabled selected>Selectionner la commune</option>
        </select>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('ouvrage_id') ? 'has-error' : '' }}">
        <label for="ouvrage_id" class="control-label">Ouvrage</label>
        <select class="form-control" id="ouvrage_edit" name="ouvrage_id" required="true" disabled>
            <option value="" disabled selected>Selectionner l'ouvrage</option>
        </select>        
        {!! $errors->first('ouvrage_id', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6">
        <label for="libelle" class="control-label">Designation</label>
        <select class="form-control" id="estimation_id" name="estimation_id" required="true" disabled>
            <option value="" style="display: none;" disabled selected>Selectionner la designation</option>
        </select>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('Quantité') ? 'has-error' : '' }}">
        <label for="qte" class="control-label">{{ __('Quantité') }}</label>
        <input class="form-control" name="qte" type="number"  min="0" placeholder="{{ __('Entrez la quantité') }}" required>
        {!! $errors->first('qte', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('prix_unit') ? 'has-error' : '' }}">
        <label for="prix_unit" class="control-label">{{ __('Prix unitaire') }}</label>
        <input class="form-control" name="prix_unit" type="number"  min="0" placeholder="{{ __('Entrez le prix unitaire') }}" required>
        {!! $errors->first('prix_unit', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-12 {{ $errors->has('date_real') ? 'has-error' : '' }}">
        <label for="date_real" class="control-label">{{ __('Date de realisation') }}</label>
        <input class="form-control" name="date_real" type="date" min="0" placeholder="{{ __('Entrez la date') }}" required>
        {!! $errors->first('date_real', '<p class="help-block">:message</p>') !!}
    </div>
</div>