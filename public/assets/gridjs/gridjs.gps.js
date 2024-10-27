import { html } from "/assets/gridjs/gridjs.module.js";

new gridjs.Grid({columns:
    ["Région", "Commune", 'Site', "Latitude", "Longitude", "Précision", "Action"],
    pagination:{
        limit:10,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "locations/fetch",
        then: (data) => data.map((item) =>
            [
                item.nom_reg,
                item.nom_comm,
                item.nom_site,
                item.latitude,
                item.longitude,
                item.accuracy,
                html(
                  `<div class='dropdown'>
                      <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <div class='dropdown-menu dropdown-menu-end' style=''>
                          <a href='javascript:void(0)' id='delete_location' data-id='`+item.id+`' class='dropdown-item' title='Supprimer les coordonnées gps'>
                            Supprimer
                          </a>
                      </div>
                  </div>`
                )
            ]
        ),
    },
    language: {
        'search': {
          'placeholder': '🔍 Recherche...'
        },
        'pagination': {
          'previous': 'Précédent',
          'next': 'Suivant',
          'showing': '😃 Affichage de ',
          'results': () => 'enregistrements'
        }
      }
}).render(document.getElementById("table_gps"));