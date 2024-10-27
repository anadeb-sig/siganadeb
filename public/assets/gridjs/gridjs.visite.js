import { Grid, html } from "/assets/gridjs/gridjs.module.js";

new gridjs.Grid({columns:
    ["Région", "Cantine", "Titre", "Objet", "Contact","Date visite", "Actions"],
    pagination:{
        limit:10,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "visites/fetch",
        then: (data) => data.map((item) =>
            [
                item.nom_reg,
                item.nom_ecl,
                item.titre,
                item.objet,
                item.contact,
                item.date_visite,
                html(
                `<div class='dropdown'>
                      <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <div class='dropdown-menu dropdown-menu-end' style=''>
                          <a  href='javascript:void(0)' id='show_visite' data-url='visites/show/`+item.id+`' class='dropdown-item' title=''>
                            Voir détail
                          </a>
                          <a href='javascript:void(0)' id='edit_visite' data-id='`+item.id+`' class='dropdown-item' title=''>
                            Editer
                          </a>
                          <a href='javascript:void(0)' id='delete_visite' data-id='`+item.id+`' class='dropdown-item' title=''>
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
}).render(document.getElementById("table_visite"));