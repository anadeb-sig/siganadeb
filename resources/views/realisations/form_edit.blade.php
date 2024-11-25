<input class="form-control id" name="id" type="hidden">
<input class="form-control estimation_id" name="estimation_id" type="hidden" required>
<input class="form-control ouvrage_id" name="ouvrage_id" type="hidden" required>

<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('qte') ? 'has-error' : '' }}">
        <label for="qte" class="control-label">{{ __('Quantité') }}</label>
        <input class="form-control qte" name="qte" type="number" required>
        {!! $errors->first('qte', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('prix_unit') ? 'has-error' : '' }}">
        <label for="prix_unit" class="control-label">{{ __('Prix unitaire') }}</label>
        <input class="form-control prix_unit" name="prix_unit" type="number" required>
        {!! $errors->first('prix_unit', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-4">    
    <div class="col-xl-12 {{ $errors->has('date_real') ? 'has-error' : '' }}">
        <label for="date_real" class="control-label">{{ __('Date de realisation') }}</label>
        <input class="form-control date_real" name="date_real" type="text" maxlength="50" required>
        {!! $errors->first('date_real', '<p class="help-block">:message</p>') !!}
    </div>
</div>