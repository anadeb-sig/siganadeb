<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet Legend Example</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 500px;
        }
        .legend {
            background: white;
            padding: 10px;
            line-height: 1.5;
        }
        .legend h4 {
            margin: 0;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // Initialisation de la carte
    const map = L.map('map').setView([8.6195, 0.8232], 7);

    // Ajout du fond de carte OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Ajout de quelques marqueurs de démonstration
    const locations = [
        {lat: 8.6195, lon: 0.8232, village: 'Village A', commune: 'Commune A', region: 'Région A'},
        {lat: 8.5, lon: 1.0, village: 'Village B', commune: 'Commune B', region: 'Région B'},
        {lat: 9.0, lon: 0.7, village: 'Village C', commune: 'Commune C', region: 'Région C'}
    ];

    locations.forEach(location => {
        L.marker([location.lat, location.lon]).addTo(map)
            .bindPopup(`<strong>${location.village}</strong><br>${location.commune}, ${location.region}`);
    });

    // Création du contrôle personnalisé pour la légende
    const legend = L.Control.extend({
        options: { position: 'bottomright' },

        onAdd: function(map) {
            const div = L.DomUtil.create('div', 'legend');
            div.innerHTML += '<h4>Informations Lieux</h4>';
            locations.forEach(location => {
                div.innerHTML += `
                    <div>
                        <strong>Village:</strong> ${location.village}<br>
                        <strong>Commune:</strong> ${location.commune}<br>
                        <strong>Région:</strong> ${location.region}<br><br>
                    </div>`;
            });
            return div;
        }
    });

    // Ajout de la légende à la carte
    map.addControl(new legend());

</script>




<script>

    

	        let csvButton = document.getElementById('csvButton'); 
          if (csvButton) {
              // Fonction d'exportation CSV au clic
                csvButton.addEventListener('click', () => {
		        fetch(`sites/fetch?region_id=${region_id}&prefecture_id=${prefecture_id}&commune_id=${commune_id}&canton_id=${canton_id}`)
                    .then(response => response.json())
                    .then(data => {
                        let tableDataa = data.data.map(item => [
                            item.nom_reg,
                            item.nom_pref,
                            item.nom_comm,
                            item.nom_cant,
                            item.nom_vill
                        ]);
                        const csvContent = [
                      // En-têtes
                      ["Region", "Prefecture", "Commune", "Canton", "Village", "Site", "Description", "Budget"],
                      ...data.data.map(item => [
                        item.nom_reg, 
                        item.nom_pref, 
                        item.nom_comm, 
                        item.nom_cant, 
                        item.nom_vill,,,
                    ])
                  ].map(e => e.join(";")).join("\n");

                  // Créer un blob pour générer le fichier CSV
                  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                  const url = URL.createObjectURL(blob);
                  
                  // Création et déclenchement du téléchargement du fichier CSV
                  const a = document.createElement('a');
                  a.href = url;
                  a.download = 'format.csv';
                  a.style.display = 'none';
                  document.body.appendChild(a);
                  a.click();
                  document.body.removeChild(a);

                });
                  // Contenu CSV avec les en-têtes et une seule ligne de données
              });
          }


</script>

</body>
</html>
