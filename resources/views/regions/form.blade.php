<input class="form-control id" name="id" type="hidden" id="id">
<div class="form-group {{ $errors->has('nom_reg') ? 'has-error' : '' }}">
    <div class="col-xl-12">
        <label for="nom_reg" class="control-label">Région</label>
        <input class="form-control nom_reg majuscules" name="nom_reg" type="text" id="nom_reg" maxlength="150" placeholder="Entrez nom de la région ici...">
        {!! $errors->first('nom_reg', '<p class="help-block">:message</p>') !!}
    </div>
</div>

