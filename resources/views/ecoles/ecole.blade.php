<select id="ecol" class="form-control" name="village_id" required>
    <option value="" disabled selected>Selectionnez l'école</option>
    @foreach($ecoles as $ecole)
        <option id="{{ $ecole->id }}"> {{ $ecole->nom_ecl }}</option>
    @endforeach
</select>