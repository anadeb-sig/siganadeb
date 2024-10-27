<input class="form-control id" name="id" type="hidden" id="id">
<div class="row {{ $errors->has('descri') ? 'has-error' : '' }}">
    <div class="col-xl-12">
        <label for="descri" class="control-label">Nom du menu</label>
        <input class="form-control descri majuscules" name="descri" type="text" id="descri" maxlength="150" placeholder="Enter nom menu here...">
        {!! $errors->first('descri', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-4 {{ $errors->has('cout_unt') ? 'has-error' : '' }}">
    <div class="col-xl-12">
        <label for="cout_unt" class="control-label">Coût unitaire</label>
        <input class="form-control cout_unt" name="cout_unt" type="number" id="cout_unt" min="-2147483648" max="2147483647" placeholder="Enter cout unt here...">
        {!! $errors->first('cout_unt', '<p class="help-block">:message</p>') !!}
    </div>
</div>


