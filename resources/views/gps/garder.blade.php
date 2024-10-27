<!-- <script>
        // Initialisation de la carte
        const map = L.map('map').setView([8.6195, 0.8232], 7); // Vue centrée sur le Togo
        //https://www.openstreetmap.org/#map=7/8.630/0.835
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            //attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        @foreach ($locations as $location)
            // Création du marqueur et ajout d'un popup
            L.marker([{{ $location->latitude }}, {{ $location->longitude }}]).addTo(map)
            .bindPopup(`
                <a href="#"
                data-url_photo="/locations/galerie/{{ $location->site->id }}"
                class="open-modal"
                site-info="{{ $location->site->nom_site }}"
                village-info="{{ $location->site->village->nom_vill }}"
                commune-info="{{ $location->site->village->canton->commune->nom_comm }}"
                region-info="{{ $location->site->village->canton->commune->prefecture->region->nom_reg }}"
                >
                    {{ $location->site->village->nom_vill }}
                </a>
            `)
            .on('popupopen', function() {
                document.querySelectorAll('.open-modal').forEach(function(element) {
                    element.addEventListener('click', function(event) {
                        event.preventDefault();

                        let url_photo = $(this).data('url_photo');
                        let site = this.getAttribute('site-info');
                        document.getElementById('site-info').textContent = site;
                        let village = this.getAttribute('village-info');
                        document.getElementById('village-info').textContent = village;
                        $('.modal-title').text('Informations complementaires sur le site');
                        let commune = this.getAttribute('commune-info');
                        document.getElementById('commune-info').textContent = commune;
                        let region = this.getAttribute('region-info');
                        document.getElementById('region-info').textContent = region;
                        $('#infoModal').modal('show');

                        // Mise à jour du slider d'images
                        let carouselIndicators = $('.carousel-indicators');
                        let carouselInner = $('.carousel-inner');
                        let cheminImage = "/images/";

                        carouselIndicators.empty();  // On vide les anciens items
                        carouselInner.empty();  // On vide les anciens items

                        fetch(url_photo)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach((item, index) => {
                                let activeClass = index === 0 ? 'active' : '';

                                // Créer les indicateurs
                                let indicator = `<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="${index}" class="${activeClass}" aria-current="true" aria-label="Slide ${index + 1}"></button>`;
                                carouselIndicators.append(indicator);

                                // Créer les items du carousel
                                let carouselItem = `<div class="carousel-item ${activeClass}">
                                                        <img src="${cheminImage}${item.photo}" class="d-block w-100" alt="Image ${index + 1}">
                                                        <div class="carousel-caption d-none d-md-block">
                                                            <h5>Image ${index + 1}</h5>
                                                            <p>${item.description || ''}</p>
                                                        </div>
                                                    </div>`;
                                carouselInner.append(carouselItem);
                            });
                        });
                    });
                });
            });
        @endforeach
    </script> -->


    <script>
        document.getElementById('btnContrat').addEventListener('click', function() {
        // Récupérer les valeurs des filtres
        let nom_reg = document.getElementById('nom_reg').value;
        let nom_comm = document.getElementById('nom_comm').value;
        let nom_projet = document.getElementById('projetsite').value;
        let nom_type = document.getElementById('nom_type').value;
        let nom_fin = document.getElementById('nom_fin').value;
        let date_demarre_debut = document.getElementById('date_demarre_debut').value;
        let date_demarre_fin = document.getElementById('date_demarre_fin').value;
        let nom_site = document.getElementById('nom_site').value;

        // Supprimer les anciens marqueurs
        map.eachLayer(function(layer) {
            if (layer instanceof L.Marker) {
                map.removeLayer(layer);
            }
        });

        // Construire l'URL avec les paramètres GET
        let url = `/locations/fetch_map?nom_reg=${encodeURIComponent(nom_reg)}&nom_comm=${encodeURIComponent(nom_comm)}&nom_projet=${encodeURIComponent(nom_projet)}&nom_type=${encodeURIComponent(nom_type)}&nom_fin=${encodeURIComponent(nom_fin)}&date_demarre_debut=${encodeURIComponent(date_demarre_debut)}&date_demarre_fin=${encodeURIComponent(date_demarre_fin)}&nom_site=${encodeURIComponent(nom_site)}`;

        // Faire la requête GET
        fetch(url)
            .then(response => response.json())
            .then(locations => {
                // Boucle à travers les locations et appliquer les filtres
                locations.forEach(function(location) {
                    if (
                        (nom_reg === '' || location.nom_reg.includes(nom_reg)) &&
                        (nom_comm === '' || location.nom_comm.includes(nom_comm)) &&
                        (nom_site === '' || location.nom_site.includes(nom_site))
                    ) {
                        L.marker([location.latitude, location.longitude]).addTo(map)
                            .bindPopup(`
                            <a href="#"
                            data-url_photo="/locations/galerie/${location.iid}" 
                            class="open-modal" 
                            site-info="${location.nom_site}" 
                            village-info="${location.nom_vill}" 
                            commune-info="${location.nom_comm }" 
                            region-info="${location.nom_reg }"
                            >
                                ${location.nom_vill }
                            </a>
                        `)
                        .on('popupopen', function() {
                            document.querySelectorAll('.open-modal').forEach(function(element) {
                                element.addEventListener('click', function(event) {
                                    event.preventDefault();

                                    let url_photo = $(this).data('url_photo');
                                    let site = this.getAttribute('site-info');
                                    document.getElementById('site-info').textContent = site;
                                    let village = this.getAttribute('village-info');
                                    document.getElementById('village-info').textContent = village;
                                    $('.modal-title').text('Informations complementaires sur le site');
                                    let commune = this.getAttribute('commune-info');
                                    document.getElementById('commune-info').textContent = commune;
                                    let region = this.getAttribute('region-info');
                                    document.getElementById('region-info').textContent = region;
                                    $('#infoModal').modal('show');

                                    // Mise à jour du slider d'images
                                    let carouselIndicators = $('.carousel-indicators');
                                    let carouselInner = $('.carousel-inner');
                                    let cheminImage = "/images/";

                                    carouselIndicators.empty();  // On vide les anciens items
                                    carouselInner.empty();  // On vide les anciens items

                                    fetch(url_photo)
                                    .then(response => response.json())
                                    .then(data => {
                                        data.forEach((item, index) => {
                                            let activeClass = index === 0 ? 'active' : '';
                                            
                                            // Créer les indicateurs
                                            let indicator = `<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="${index}" class="${activeClass}" aria-current="true" aria-label="Slide ${index + 1}"></button>`;
                                            carouselIndicators.append(indicator);

                                            // Créer les items du carousel
                                            let carouselItem = `<div class="carousel-item ${activeClass}">
                                                                    <img src="${cheminImage}${item.photo}" class="d-block w-100" alt="Image ${index + 1}">
                                                                    <div class="carousel-caption d-none d-md-block">
                                                                        <h5>Image ${index + 1}</h5>
                                                                        <p>${item.description || ''}</p>
                                                                    </div>
                                                                </div>`;
                                            carouselInner.append(carouselItem);
                                        });
                                    });
                                });
                            });
                        });
                    }
                });
            });
        });

    </script>