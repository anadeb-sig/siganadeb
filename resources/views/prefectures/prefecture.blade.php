<select id="prefecture" class="form-control example" required="true" charset="iso-8859-1">
    <option disabled selected>Selectionnez la préfecture</option>
    @foreach($prefectures as $prefecture)
        <option id="{{ $prefecture->id }}"> {{ $prefecture->nom_pref }}</option> 
    @endforeach                       
</select>