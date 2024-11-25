<input class="form-control id" name="id" type="hidden">
<input class="form-control type_realisation_id" name="type_realisation_id" type="hidden" required>
<input class="form-control ouvrage_id" name="ouvrage_id" type="hidden" required>

<div class="row">
    <div class="col-xl-12 {{ $errors->has('design') ? 'has-error' : '' }}">
        <label for="design" class="control-label">{{ __('Designation') }}</label>
        <textarea class="form-control design" name="design" type="text" placeholder="{{ __('Entrez la designation') }}" required></textarea>
        {!! $errors->first('design', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('unite') ? 'has-error' : '' }}">
        <label for="unite" class="control-label">{{ __('Unité de mesure') }}</label>
        <input class="form-control unite" name="unite" type="text" maxlength="50" required>
        {!! $errors->first('unite', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('prix_unit') ? 'has-error' : '' }}">
        <label for="prix_unit" class="control-label">{{ __('Prix unitaire') }}</label>
        <input class="form-control prix_unit" name="prix_unit" type="number" required>
        {!! $errors->first('prix_unit', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-4">
    <div class="col-xl-12 {{ $errors->has('qte') ? 'has-error' : '' }}">
        <label for="qte" class="control-label">{{ __('Quantité') }}</label>
        <input class="form-control qte" name="qte" type="number" required>
        {!! $errors->first('qte', '<p class="help-block">:message</p>') !!}
    </div>
</div>