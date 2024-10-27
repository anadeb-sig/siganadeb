@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <div class="row">
                        <div class="col-xl-10">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size: 1.2em;">
                                #Filtrer
                            </button>
                        </div>
                        <div class="col-xl-1">
                            <a id="download-mapPDF" class="btn btn-outline-primary" style="float: right;">Télécharger le pdf</a>
                        </div>
                        <div class="col-xl-1">
                            <a id="download-map" class="btn btn-outline-primary" style="float: right;">Télécharger l'image'</a>
                        </div>
                    </div>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form method="get" action="" accept-charset="UTF-8">
                            <div class="row mt-4">
                                <div class="col-xl-4">
                                    <label for="nom_reg" class="control-label">Région</label>
                                    <select class="form-control" id="nom_reg" name="id" required="true">
                                        <option value="" style="display: none;" disabled selected>Selectionner la région</option>
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->nom_reg }}">
                                                {{ $region->nom_reg }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-4">
                                    <label for="commune_id" class="control-label">Commune</label>
                                    <input class="form-control w-100 majuscules" id="nom_comm" name="nom_comm" type="text" placeholder="exemple: MO 2  ..." />
                                </div>
                                <div class="col-xl-4 {{ $errors->has('nom_site') ? 'has-error' : '' }}">
                                    <label for="nom_site" class="control-label">Nom du site</label>
                                    <input class="form-control w-100" id="nom_site" name="nom_site" type="text" placeholder="exemple: batiment scolaire..." />
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-xl-4 {{ $errors->has('type_ouvrag') ? 'has-error' : '' }}">
                                    <label for="type_ouvrag" class="control-label">Type d'site</label>
                                    <select class="form-control" id="nom_type" name="nom_type" required="true">
                                        <option value="" style="display: none;" disabled selected>Selectionner le type d'site</option>
                                        @foreach ($typeouvrages as $typeouvrage )
                                            <option value="{{ $typeouvrage ->nom_type }}">
                                                {{ $typeouvrage ->nom_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-4 {{ $errors->has('nom_pr') ? 'has-error' : '' }}">
                                    <label for="nom_pr" class="control-label">Projet</label>
                                    <select class="form-control" id="projetsite" name="projetsite" required="true">
                                        <option value="" style="display: none;" disabled selected>Selectionner le projet</option>
                                        @foreach ($projets as $projet)
                                            <option value="{{ $projet->name }}">
                                                {{ $projet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-4">
                                    <label for="rang">Financement</label>
                                    <select class="form-control" id="nom_fin" name="nom_fin" required>
                                        <option value="" style="display: none;" disabled selected>Select financement</option>
                                        @foreach ($financements as $financement)
                                            <option value="{{ $financement->nom_fin }}">
                                                {{ $financement->nom_fin }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-xl-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xl-6 {{ $errors->has('date_fin') ? 'has-error' : '' }}">
                                                    <label for="code" class="control-label">Date début</label>
                                                    <input class="form-control" type="date" id='date_demarre_debut'  placeholder="exemple: BM">
                                                </div>
                                                <div class="col-xl-6 {{ $errors->has('date_fin') ? 'has-error' : '' }}">
                                                    <label for="code" class="control-label">Date fin</label>
                                                    <input class="form-control w-100" id="date_demarre_fin" type="date" placeholder="exemple: batiment..." />
                                                </div>
                                                <label class="control-label mt-2 mb-0 text-center">Donner une période à la quelle l'exécution de l'site a démarré </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 modal-footer mt-4">
                                    <a href="{{ route('locations.cartographie') }}" type="button" class="btn btn-outline-danger">
                                        <i class="fas fa-sync-alt"></i> &nbsp;Rafraichir
                                    </a>
                                    &nbsp;&nbsp;
                                    <button id="btnContrat" type="button" class="btn btn-outline-primary">
                                        <i class="fa fa-search"></i> &nbsp;Rechercher
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            
            <!-- Coordonnées gps sur la carte du togo -->
            <div class="row">
                <div class="col-xl-12">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

     <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </button>
            </div>
                <div class="modal-body">
                    <!-- Ajoutez ici les informations supplémentaires -->
                    <table class="table table-striped mb-0">
                        <tr>
                            <td>site</td>
                            <td><span id="site-info"></span></td>
                        </tr>
                        <tr>
                            <td>Village</td>
                            <td><span id="village-info"></span></td>
                        </tr>
                        <tr>
                            <td>Commune</td>
                            <td><span id="commune-info"></span></td>
                        </tr>
                        <tr>
                            <td>Region</td>
                            <td><span id="region-info"></span></td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2">Galérie des photos de l'site</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                    </div>
                                    <div class="carousel-inner">
                                        <!---->
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
        document.getElementById('download-map').addEventListener('click', function() {
            leafletImage(map, function(err, canvas) {
                const img = document.createElement('a');
                img.href = canvas.toDataURL();
                img.download = 'carte_togo.png';
                img.click();
            });
        });
    </script>

    <script>
        // Initialisation de la carte
        const map = L.map('map').setView([8.6195, 0.8232], 7); // Vue centrée sur le Togo
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            //attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        @foreach ($locations as $location)
            <?php
                // Trouver le suivi correspondant au site_id de cette location
                $suivi = \App\Models\Suivi::where('site_id', $location->site_id)->first();
            ?>
            @if ($suivi)
                // Création du marqueur et ajout d'un popup avec l'ID du suivi
                L.marker([{{ $location->latitude }}, {{ $location->longitude }}]).addTo(map)
                .bindPopup(`
                    <a href="/suivis/show/{{ $suivi->id }}">
                        {{ $location->site->village->nom_vill }}
                    </a>
                `);
            @endif
        @endforeach
    </script>

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
                        // Le suivi_id doit être inclus dans la réponse JSON depuis l'API
                        if (
                            (nom_reg === '' || location.nom_reg.includes(nom_reg)) &&
                            (nom_comm === '' || location.nom_comm.includes(nom_comm)) &&
                            (nom_site === '' || location.nom_site.includes(nom_site))
                        ) {
                            // Ajouter un marqueur pour chaque location qui correspond aux filtres
                            L.marker([location.latitude, location.longitude]).addTo(map)
                                .bindPopup(`
                                    <a href="/suivis/show/${location.iiid}">
                                        ${location.nom_vill}
                                    </a>
                                `);
                        }
                    });
                })
                .catch(error => {
                    console.error('Erreur lors du fetch des locations :', error);
                });
        });
    </script>


    <script>
        document.getElementById('download-mapPDF').addEventListener('click', function() {
            leafletImage(map, function(err, canvas) {
                if (err) {
                    console.error('Erreur lors de la génération de l\'image de la carte :', err);
                    return;
                }

                // Convertir le canvas en image Data URL
                const imgData = canvas.toDataURL('image/png');

                // Créer un nouveau document PDF avec jsPDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF({
                    orientation: 'landscape', // Mettre en paysage si nécessaire
                    unit: 'px', // Unité en pixels
                    format: [canvas.width, canvas.height] // Dimensions du PDF en fonction de la taille du canvas
                });

                // Ajouter l'image au PDF
                doc.addImage(imgData, 'PNG', 0, 0, canvas.width, canvas.height);

                // Télécharger le fichier PDF
                doc.save('carte_togo.pdf');
            });
        });
    </script>


    


    <script src="https://rawgit.com/mapbox/leaflet-image/gh-pages/leaflet-image.js"></script>
    <!-- Inclure jsPDF depuis un CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

@endsection