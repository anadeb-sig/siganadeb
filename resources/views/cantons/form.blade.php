<input class="form-control id" name="id" type="hidden" id="id">
<div class="row mb-3">
    <div class="col-md-6 {{ $errors->has('region_id') ? 'has-error' : '' }}">
        <label for="region_id" class="control-label">Région</label>
        <select class="form-control region_id" id="region_comm" name="prefecture_id">
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
        	    <option value="">Selectionner la commune</option>
        </select>
        
        {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12 {{ $errors->has('nom_cant') ? 'has-error' : '' }}">
        <label for="nom_cant" class="control-label">Canton</label>
        <input class="form-control nom_cant majuscules" name="nom_cant" type="text" id="nom_cant" maxlength="150" placeholder="Entrer le nom du canton ici...">
        {!! $errors->first('nom_cant', '<p class="help-block">:message</p>') !!}
    </div>
</div>

