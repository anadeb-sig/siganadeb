<input class="form-control id" name="id" type="hidden" id="id">
<div class="row mb-3">
    <div class="col-xl-6 {{ $errors->has('region_id') ? 'has-error' : '' }}">
        <label for="region_id" class="control-label">Région</label>
        <select class="form-control region_id" id="region_edit" name="prefecture_id" required="true">
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
        <select class="form-control" id="commune_edit" name="commune_id" required="true" disabled>
        	    <option value="" style="display: none;" disabled selected>Selectionner la commune</option>
        </select>        
        {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row">
    <div class="col-xl-6 {{ $errors->has('canton_id') ? 'has-error' : '' }}">
        <label for="canton_id" class="control-label">Canton</label>
        <select class="form-control" id="canton_edit" name="canton_id" required="true" disabled required="true">
        	    <option value="" disabled selected>Selectionner le canton</option>
        </select>      
        {!! $errors->first('canton_id', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6">
        <label for="nom_vill" class="control-label">Village</label>
        <input class="form-control nom_vill majuscules" name="nom_vill" type="text" id="nom_vill" maxlength="150" placeholder="Entrer le nom du village ici...">
        {!! $errors->first('nom_vill', '<p class="help-block">:message</p>') !!}
    </div>
</div>

