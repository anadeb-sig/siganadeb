<input class="form-control id" name="id" type="hidden" id="id">
<div class="row mb-3">
    <div class="col-xl-6">
        <label for="titre" class="control-label">Titre de la visite</label>
        <input class="form-control titre majuscules" name="titre" type="text" id="titre" maxlength="150" placeholder="Entrer le titre ici...">
        {!! $errors->first('titre', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6">
        <label for="objet" class="control-label">Objet</label>
        <input class="form-control objet majuscules" name="objet" type="text" id="objet" maxlength="150" placeholder="Entrer objet ici...">
        {!! $errors->first('objet', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="row {{ $errors->has('contact') ? 'has-error' : '' }}">
    <div class="col-xl-6 mb-3">
        <label for="contact" class="control-label">Contact du directeur de l'école</label>
        <input class="form-control contact" name="contact" type="number" id="contact" placeholder="Entrer le contact ici...">
        {!! $errors->first('contact', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 mb-3">
        <label for="date_visite" class="control-label">Date visite</label>
        <input class="form-control date_visite" name="date_visite" type="date" id="date_visite">
        {!! $errors->first('date_visite', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row">
    <div class="col-xl-6 mb-3 {{ $errors->has('constat') ? 'has-error' : '' }}">
        <label for="constat" class="control-label">Constats</label>
        <textarea class="form-control constat majuscules" name="constat" cols="50" rows="5" id="constat" placeholder="Entrer constat ici..."></textarea>
        {!! $errors->first('constat', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 mb-3 {{ $errors->has('niveau_exe') ? 'has-error' : '' }}">
        <label for="niveau_exe" class="control-label">Niveau d'exécution</label>
        <textarea class="form-control niveau_exe majuscules" name="niveau_exe" cols="50" rows="5" id="niveau_exe" placeholder="Entrer le niveau d'exécution ici..."></textarea>
        {!! $errors->first('niveau_exe', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row {{ $errors->has('recommandation') ? 'has-error' : '' }}">
    <div class="col-xl-12 mb-3">
        <label for="recommandation" class="control-label">Les recommandations</label>
        <textarea class="form-control recommandation majuscules" name="recommandation" cols="50" rows="5" id="recommandation" placeholder="Entre les recommandations ici..."></textarea>
        {!! $errors->first('recommandation', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        DONNEES DE LOCALISATION
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                <label for="region_id" class="control-label">Région</label>
                <select class="form-control" id="region_comm" required="true">
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
                <select class="form-control" id="commune_comm" required="true" disabled>
                        <option value="" disabled selected>Selectionner la commune</option>
                </select>        
                {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-xl-4">
                <label for="ecole_id" class="control-label">Cantine</label>
                <select class="form-control ecole_id" id="ecole_comm" name="ecole_id" disabled required="true">
                    <option value="" disabled selected>Selectionner l'école</option>
                </select>
            </div>
        </div>
    </div>
</div>

