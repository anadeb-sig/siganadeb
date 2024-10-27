<input class="form-control id" name="id" type="hidden" id="id">
<input class="form-control inscrit_id" name="inscrit_id" type="hidden">
<div class="row">
    <div class="col-xl-6 {{ $errors->has('effect_fil') ? 'has-error' : '' }}">
        <label for="effect_fil" class="control-label">Nombre de plats servi aux fillles</label>
        <input class="form-control edit_effect_fil" name="effect_fil" type="number" min="0" placeholder="Entrez le nombre de plats servi aux filles...">
        {!! $errors->first('effect_fil', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('effect_gar') ? 'has-error' : '' }}">
        <label for="effect_gar" class="control-label">Nombre de plats servi aux garçons</label>
        <input class="form-control edit_effect_gar" name="effect_gar" type="number" min="0" placeholder="Entrez le nombre de plats servi aux garçons...">
        {!! $errors->first('effect_gar', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="row mt-4">
    <div class="col-xl-6 {{ $errors->has('date_rep') ? 'has-error' : '' }}">
        <label for="date_rep" class="control-label">Date de fourniture de repas</label>
        <input class="form-control edit_date_rep" name="date_rep" type="date" placeholder="Date de fourniture de repas...">
        {!! $errors->first('date_rep', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('menus_id') ? 'has-error' : '' }}">
        <label for="menus_id" class="control-label">Coût du plat</label>
        <select class="form-control menu_id" id="menus_id" required="true">
            <option value="" style="display: none;" disabled selected>Selectionner le coût du plat</option>
            @foreach ($menus as $menu)
                <option value="{{ $menu->id }}">
                    {{ $menu->cout_unt }}
                </option>
            @endforeach
        </select>        
        {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<!-- <div class="card mt-4">
    <div class="card-header">
        DONNEES DE LOCALISATION ET CANTINE
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-xl-6 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                <label for="region_id" class="control-label">Région</label>
                <select class="form-control region_id" id="region_edit" name="id" required="true">
                    <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}" data-reg="{{ $region->nom_reg }}">
                            {{ $region->nom_reg }}
                        </option>
                    @endforeach
                </select>        
                {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-xl-6 {{ $errors->has('canton_id') ? 'has-error' : '' }}">
                <label for="canton_id" class="control-label">Canton</label>
                <select class="form-control" id="canton_edit" name="canton_id" required="true" disabled>
                    <option value="" disabled selected>Selectionner le canton</option>
                </select>        
                {!! $errors->first('canton_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-xl-6">
                <label for="ecole_id" class="control-label">Cantine</label>
                <select class="form-control ecole_id" id="ecole_edit" disabled required="true">
                    <option value="" disabled selected>Selectionner la cantine</option>
                </select>
            </div>
            <div class="col-xl-6  {{ $errors->has('classe_id') ? 'has-error' : '' }}">
                <label class="form-label">Ecole</label>
                <select class="form-control edit_classe_id" id="classe_edit" name="classe_id" disabled required="true">
                    <option value="" disabled selected>Selectionner l'école!</option>
                </select>
            </div>
        </div>
    </div>
</div> -->



