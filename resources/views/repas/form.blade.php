<input class="form-control id" name="id" type="hidden" id="id">

<div class="card mt-4">
    <div class="modal-header">
        DONNEES DE LOCALISATION ET CANTINE
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-4 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                <label for="region_id" class="control-label">Région</label>
                <select class="form-control" id="region_rep" required="true">
                    <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                    @foreach ($regions as $region)
                        <option data-reg="{{ $region->nom_reg }}" value="{{ $region->id }}">
                            {{ $region->nom_reg }}
                        </option>
                    @endforeach
                </select>        
                {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-xl-4 {{ $errors->has('commune_id') ? 'has-error' : '' }}">
                
                <label for="commune_id" class="control-label">Commune</label>
                <select class="form-control" id="commune_rep" required="true" disabled>
                        <option value="" disabled selected>Selectionner le canton</option>
                </select>        
                {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-xl-4">
                <label for="ecole_id" class="control-label">Cantine</label>
                <select class="form-control ecole_id" id="ecole_rep" name="ecole_id" disabled required="true">
                    <option value="" disabled selected>Selectionner l'école</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div id="inscrit_rep">
        </div>
    </div>   
</div>
<div class="card mt-4">
    <div class="card-body">
        <div class="row mt-4">    
            <div class="col-xl-5 {{ $errors->has('date_rep1') ? 'has-error' : '' }}">
                <label for="date_rep" class="control-label">Date de fourniture de repas</label>
                <input class="form-control date_rep1" name="date_rep1" type="date" required="true">
                {!! $errors->first('date_rep1', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="col-xl-4 {{ $errors->has('menus_id') ? 'has-error' : '' }}">
                <label for="menus_id" class="control-label">Coût du plat</label>
                <select class="form-control menu_id" id="menus_id" required="true">
                    <option value="" style="display: none;" disabled selected>Selectionner le coût du plat</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}">
                            {{ $menu->cout_unt }}
                        </option>
                    @endforeach
                </select>        
                {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
            </div>
            
            <div class="col-xl-3 text-center">
                <label for="" class="control-label" style="font-size: 1.2em;color:red">Jour férié</label><br>
                <input type="checkbox" class="" name="" id="checkbox" style="width: 20px; height:20px;">
            </div>
        </div>
    </div>
</div>
<!-- Affichage du form à renseigner les repas selon le besoin -->
<div class="card mt-4">
    <div class="card-body">
        <div class="row" id="scolaireForm">
        </div>
    </div>
</div>

<div class="card mt-4" id="monTextarea" style="display: none;">
    <div class="card-body">
        <div class="row">
            <div class="col-xl-12">
                <label class="form-label" for="">Description</label>
                <textarea class="form-control descrip" name="descrip" cols="150" rows="5"></textarea>
            </div>
        </div>
    </div>
</div>



