<select id="canton" class="form-control example">
    <option disabled selected>Selectionnez le canton</option>
    @foreach($cantons as $canton)
        <option id="{{ $canton->id }}"> {{ $canton->nom_cant }}</option> 
    @endforeach 
</select>