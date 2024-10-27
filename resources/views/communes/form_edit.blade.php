<input class="form-control id" name="id" type="hidden" id="id">
<div class="row mb-3">
    <div class="col-xl-6 {{ $errors->has('region_id') ? 'has-error' : '' }}">
        <label for="region_id" class="control-label">Région</label>
        <select class="form-control" id="region_edit" name="prefecture_id">
        	<option value="" style="display: none;" disabled selected>Selectionner la région</option>
        	@foreach ($regions as $region)
			    <option value="{{ $region->id }}">
			    	{{ $region->nom_reg }}
			    </option>
			@endforeach
        </select>        
        {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('prefecture_id') ? 'has-error' : '' }}">
        <label for="prefecture_id" class="control-label">Prefecture</label>
        <select class="form-control" id="prefecture_edit" name="prefecture_id" required="true" disabled>
        	<option value="" style="display: none;" disabled selected>Selectionner la prefecture</option>
        </select>        
        {!! $errors->first('prefecture_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('nom_comm') ? 'has-error' : '' }}">
    <div class="col-xl-12">
        <label for="nom_comm" class="control-label">Commune</label>
        <input class="form-control nom_comm majuscules" name="nom_comm" type="text" id="nom_comm" maxlength="150" placeholder="Entrer nom de la commune ici...">
        {!! $errors->first('nom_comm', '<p class="help-block">:message</p>') !!}
    </div>
</div>

