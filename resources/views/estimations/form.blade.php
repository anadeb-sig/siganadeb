
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
        <label for="libelle" class="control-label">Libellé</label>
        <select class="form-control region-comm" id="type_realisation_id" name="type_realisation_id" required="true">
            <option value="" style="display: none;" disabled selected>Selectionner la région</option>
            @foreach ($libelles as $libelle)
                <option value="{{ $libelle->id }}">
                    {{ $libelle->lib }}
                </option>
            @endforeach
        </select>
    </div>
</div>



<div class="row mt-4">
    <div class="col-xl-12 {{ $errors->has('design') ? 'has-error' : '' }}">
        <label for="design" class="control-label">{{ __('Designation') }}</label>
        <textarea class="form-control" name="design" id="design" placeholder="{{ __('Entrez la designation') }}" required> </textarea>
        {!! $errors->first('design', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('nom_charge') ? 'has-error' : '' }}">
        <label for="unite" class="control-label">{{ __('Unité') }}</label>
        <input class="form-control" name="unite" type="text" placeholder="{{ __('Entrez l\'unité de mesure') }}" required>
        {!! $errors->first('unite', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('Quantité') ? 'has-error' : '' }}">
        <label for="qte" class="control-label">{{ __('Quantité') }}</label>
        <input class="form-control" name="qte" type="number"  min="0" placeholder="{{ __('Entrez la quantité') }}" required>
        {!! $errors->first('qte', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('prix_unit') ? 'has-error' : '' }}">
        <label for="prix_unit" class="control-label">{{ __('Prix unitaire') }}</label>
        <input class="form-control" name="prix_unit" type="number"  min="0" placeholder="{{ __('Entrez le prix unitaire') }}" required>
        {!! $errors->first('prix_unit', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('montant') ? 'has-error' : '' }}">
        <label for="montant" class="control-label">{{ __('Montant unitaire') }}</label>
        <input class="form-control" name="montant" type="number" min="0" placeholder="{{ __('Entrez le montant') }}" required>
        {!! $errors->first('montant', '<p class="help-block">:message</p>') !!}
    </div>
</div>