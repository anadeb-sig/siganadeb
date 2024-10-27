
<div class="row {{ $errors->has('nom_fin') ? 'has-error' : '' }}">
    <div class="col-xl-12">
        <label class="control-label">Nom du financement</label>
        <input class="form-control nom_fin majuscules" name="nom_fin" type="text" maxlength="150" placeholder="Entrez le nom du financeur ici..." required>
        {!! $errors->first('nom_fin', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row {{ $errors->has('commentaire') ? 'has-error' : '' }}">
    <div class="col-xl-12">
        <label class="control-label">Commentaire</label>
        <textarea class="form-control commentaire majuscules" name="commentaire" cols="50" rows="10" id="commentaire" placeholder="Entrez le commentaire ici..." required></textarea>
        {!! $errors->first('commentaire', '<p class="help-block">:message</p>') !!}
        <input class="form-control id" name="id" type="hidden" id="id">
    </div>
</div>


