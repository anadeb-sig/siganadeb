<input class="form-control id" name="id" type="hidden">
<div class="row">
    <div class="col-xl-6 {{ $errors->has('nom_entrep') ? 'has-error' : '' }}">
        <label for="nom_entrep" class="control-label">{{ __('Nom de l\'entreprise') }}</label>
        <input class="form-control nom_entrep majuscules" name="nom_entrep" type="text" id="nom_entrep" placeholder="{{ __('Entrez le nom de l\'entreprise') }}" required>
        {!! $errors->first('nom_entrep', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('num_id_f') ? 'has-error' : '' }}">
        <label for="num_id_f" class="control-label">{{ __('Numéro idf de l\'entreprise') }}</label>
        <input class="form-control num_id_f" name="num_id_f" type="text" id="num_id_f" placeholder="{{ __('Entrez le numéro idf de l\'entreprise') }}" required>
        {!! $errors->first('num_id_f', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-6 {{ $errors->has('nom_charge') ? 'has-error' : '' }}">
        <label for="nom_charge" class="control-label">{{ __('Nom du responsable') }}</label>
        <input class="form-control nom_charge majuscules" name="nom_charge" type="text" id="nom_charge" maxlength="50" placeholder="{{ __('Entrez le nom du chargé') }}" required>
        {!! $errors->first('nom_charge', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('prenom_charge') ? 'has-error' : '' }}">
        <label for="prenom_charge" class="control-label">{{ __('Prénom(s) du responsable') }}</label>
        <input class="form-control prenom_charge majuscules" name="prenom_charge" type="text" id="prenom_charge" maxlength="100" placeholder="{{ __('Entrez le(s) prénom(s) du chargé') }}" required>
        {!! $errors->first('prenom_charge', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-6 {{ $errors->has('tel') ? 'has-error' : '' }}">
        <label for="tel" class="control-label">{{ __('Numéro du téléphone') }}</label>
        <input class="form-control tel" name="tel" type="text" id="tel" maxlength="50" placeholder="{{ __('Entrez numéro du téléphone') }}" required>
        {!! $errors->first('tel', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-xl-6 {{ $errors->has('email') ? 'has-error' : '' }}">
        <label for="email" class="control-label">{{ __('Addrresse email de l\'entreprise') }}</label>
        <input class="form-control email" name="email" type="email" id="email" maxlength="150" placeholder="{{ __('Entrez l\'addresse email') }}" required>
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="row mt-3">
    <div class="col-xl-12 {{ $errors->has('addr') ? 'has-error' : '' }}">
        <label for="addr" class="control-label">{{ __('Addrresse de l\'entreprise') }}</label>
        <input class="form-control addr majuscules" name="addr" type="text" id="addr" maxlength="150" placeholder="{{ __('Entrez l\'addresse') }}" required>
        {!! $errors->first('addr', '<p class="help-block">:message</p>') !!}
    </div>
</div>