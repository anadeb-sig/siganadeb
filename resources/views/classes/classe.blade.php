<select id="classe" class="form-control" name="classe_id" required>
    <option value="" disabled selected>Selectionnez l'unité d'enseignement!</option>
    @foreach($classes as $classe)
        <option value="{{ $classe->id }}"> {{ $classe->nom_cla }}</option>
    @endforeach
</select>