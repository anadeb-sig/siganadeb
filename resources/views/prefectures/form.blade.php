<input class="form-control id" name="id" type="hidden" id="id">
<div class="form-group {{ $errors->has('region_id') ? 'has-error' : '' }}">
    <div class="col-md-12">
        <label for="region_id" class="control-label">Region</label>
        <select class="form-control region_id" id="region_id" name="region_id" required="true">
        	    <option value="" style="display: none;" disabled selected>Select region</option>
        	@foreach ($regions as $Region)
			    <option value="{{ $Region->id }}">
			    	{{ $Region->nom_reg }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('nom_pref') ? 'has-error' : '' }}">
    <div class="col-md-12">
        <label for="nom_pref" class="control-label">Nom Pref</label>
        <input class="form-control nom_pref majuscules" name="nom_pref" type="text" id="nom_pref" maxlength="150" placeholder="Entrer le nom de la préfecture...">
        {!! $errors->first('nom_pref', '<p class="help-block">:message</p>') !!}
    </div>
</div>

