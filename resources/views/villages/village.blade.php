<select id="localite" class="form-control village_id" name="village_id" required>
    <option value="" disabled selected>Selectionnez la localité</option>
    @foreach($villages as $village)
        <option value="{{ $village->id }}" id="{{ $village->id }}"> {{ $village->nom_vill }}</option>
    @endforeach
</select>