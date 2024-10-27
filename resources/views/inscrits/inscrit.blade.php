<select id="inscrit" class="form-control" name="inscrit_id" required>
    <option value="" disabled selected>Selectionnez l'unité d'enseignement!</option>
    @foreach($inscrits as $inscrit)
        <option value="{{ $inscrit->id }}"> {{ $inscrit->nom_cla }}</option>
    @endforeach
</select>