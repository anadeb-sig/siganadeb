<input class="form-control id" name="id" type="hidden" id="id">
<div class="row mb-4">
    <div class="col-xl-6">
        <div class="{{ $errors->has('nom_ecl') ? 'has-error' : '' }}">
            <label for="nom_ecl" class="control-label">Nom de la cantine</label>
            <input class="form-control nom_ecl majuscules" name="nom_ecl" type="text" required id="nom_ecl" maxlength="150" placeholder="Enter nom ecl here...">
            {!! $errors->first('nom_ecl', '<p class="help-block">:message</p>') !!}
        </div>
    </div><!-- end col -->
    <div class="col-xl-6 {{ $errors->has('financement_id') ? 'has-error' : '' }}">
        <label for="financement_id" class="control-label">Financement</label>
        <select class="form-control financement_id" id="financement_id" name="financement_id" required>
        	<option value="" style="display: none;" disabled selected>Select financement</option>
        	@foreach ($Financements as $key => $Financement)
			    <option value="{{ $key }}">
			    	{{ $Financement }}
			    </option>
			@endforeach
        </select>
        {!! $errors->first('financement_id', '<p class="help-block">:message</p>') !!}
    </div><!-- end col -->
</div>

<div class="card mt-4">
    <div class="card-header">
        DONNEES DE LOCALISATION
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
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
            <div class="col-xl-4 {{ $errors->has('commune_id') ? 'has-error' : '' }}">
                <label for="commune_id" class="control-label">Commune</label>
                <select class="form-control commune_id" id="commune_edit" name="commune_id" required="true" disabled>
                    <option value="" disabled selected>Selectionner la commune</option>
                </select>        
                {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-xl-4">
                <label class="control-label">Village</label>
                <select class="form-control" id="village_edit" name="village_id" disabled required="true">
                    <option value="" disabled selected>Selectionner le village</option>
                </select>
                {!! $errors->first('nom_vill', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>

