import { Grid, html } from "/assets/gridjs/gridjs.module.js";
new gridjs.Grid({columns:
    ["Région", "Actions"],
    pagination:{
        limit:10,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "regions/fetch",
        then: (data) => data.map((item) =>
            [
              
                html(
                  "<div class='pull-right' role='group'>"+
                    "<form method='get' action='ecoles/ecole_par_region'>"+
                      "<input type='hidden' name='id' value='"+item.id+"'>"+
                      "<button type='submit' style='color: #3f73b4;border: none;background-color: white;' title='Ecoles prises en charge dans le village'>"+
                          item.nom_reg+
                      "</button>"+                        
                    "</form>"
                ),
                html(
                  `<div class='dropdown'>
                      <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <div class='dropdown-menu dropdown-menu-end' style=''>
                          <a href='javascript:void(0)' id='show_region' data-url='regions/show/`+item.id+`' data-id='`+item.id+`' class='dropdown-item'>
                            Voir détail
                          </a>
                          <a href='javascript:void(0)' id='edit_region' data-id='`+item.id+`' class='dropdown-item'>
                            Editer
                          </a>
                          <a href='javascript:void(0)' id='delete_region' data-id='`+item.id+`' class='dropdown-item'>
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
}).render(document.getElementById("table_region"));