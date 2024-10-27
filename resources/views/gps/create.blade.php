    <!-- Full screen modal content -->
<div class="modal fade add_location_modal" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12 {{ $errors->has('region_id') ? 'has-error' : '' }}">
                                        <label for="region_id" class="control-label">Région</label>
                                        <select class="form-control" id="region_contrat" name="id" required="true">
                                            <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region->id }}">
                                                    {{ $region->nom_reg }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-12 {{ $errors->has('commune_id') ? 'has-error' : '' }}">
                                        <label for="commune_id" class="control-label">Commune</label>
                                        <select class="form-control" id="commune_contrat" name="commune_id" required disabled>
                                            <option value="" disabled selected>Selectionner la commune</option>
                                        </select>
                                        {!! $errors->first('commune_id', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-12 {{ $errors->has('site_id') ? 'has-error' : '' }}">
                                        <label for="site_id" class="control-label">{{ __('Site d\'ouvrages') }}</label>
                                        <select class="form-control site_id" id="site_contrat" name="site_id" disabled required="true">
                                            <option value="" disabled selected>{{ __('Sélectionnez le site') }}</option>
                                        </select>
                                        {!! $errors->first('site_id', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <button class="btn btn-outline-primary" onclick="getLocation()">Obtenir ma position</button>
                                    <p id="coordinates"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            document.getElementById("coordinates").innerText = "La géolocalisation n'est pas supportée par ce navigateur.";
        }
    }

    function showPosition(position) {
        let latitude = position.coords.latitude;
        let longitude = position.coords.longitude;
        let accuracy = position.coords.accuracy;
        let ouvrage = document.getElementById('site_contrat');
        let site_id = ouvrage.value;

        // Récupérer l'option sélectionnée
        let select_ouvrage = ouvrage.options[ouvrage.selectedIndex];
        // Récupérer la valeur de l'attribut data-proj
        let ouvrage_val = select_ouvrage.getAttribute("data-ouv");

        console.log(site_id);

        document.getElementById("coordinates").innerText =
            `Latitude : ${latitude}\nLongitude : ${longitude}\nPrécision : ${accuracy} mètres\nSite : ${ouvrage_val}`;

        // Envoyer les coordonnées au serveur Laravel via AJAX
        fetch('locations/save-coordinates', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                latitude: latitude,
                longitude: longitude,
                accuracy: accuracy,
                site_id: site_id
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.error || 'Erreur inconnue'); });
            }
            return response.json();
        })
        .then(data => {
            document.getElementById("coordinates").innerText += `\nRéponse du serveur: ${JSON.stringify(data)}`;
        })
        .catch(error => {
            console.error('Il y a eu un problème avec votre opération de récupération:', error);
            document.getElementById("coordinates").innerText += `\nErreur: ${error.message}`;
        });
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                document.getElementById("coordinates").innerText = "L'utilisateur a refusé la demande de géolocalisation.";
                break;
            case error.POSITION_UNAVAILABLE:
                document.getElementById("coordinates").innerText = "Les informations de géolocalisation ne sont pas disponibles.";
                break;
            case error.TIMEOUT:
                document.getElementById("coordinates").innerText = "La demande de géolocalisation a expiré.";
                break;
            case error.UNKNOWN_ERROR:
                document.getElementById("coordinates").innerText = "Une erreur inconnue s'est produite.";
                break;
        }
    }
</script>
