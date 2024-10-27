import { Grid, html } from "/assets/gridjs/gridjs.module.js";
new gridjs.Grid({columns:
    ["Type ouvrage", "Actions"],
    pagination:{
        limit:10,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "typeouvrages/fetch",
        then: (data) => data.map((item) =>
            [
                item.nom_type,
                html(
                  `<div class='dropdown'>
                      <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <div class='dropdown-menu dropdown-menu-end' style=''>
                          <a href='javascript:void(0)' id='edit_typeouvrage' data-id='`+item.id+`' class='dropdown-item'>
                            Editer
                          </a>
                          <a href='javascript:void(0)' id='delete_typeouvrage' data-id='`+item.id+`' class='dropdown-item'>
                            Supprimer
                          </a>
                      </div>
                  </div>`
                )
            ]
        ),
    },
    style: {
        // th: {
        //   'text-align': 'center'
        // },
        
        // td: {
        //   'text-align': 'center',
        // }
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
}).render(document.getElementById("table_typeouvrage"));