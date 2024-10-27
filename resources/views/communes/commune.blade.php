<select id="commune" class="form-control example">
    <option disabled selected>Selectionnez la Commune</option>
    @foreach($communes as $commune)
        <option id="{{ $commune->id }}"> {{ $commune->nom_comm }}</option> 
    @endforeach                            
</select>