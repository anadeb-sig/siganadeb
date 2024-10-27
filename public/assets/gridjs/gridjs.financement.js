import { Grid, html } from "/assets/gridjs/gridjs.module.js";

new gridjs.Grid({columns:
    ["Financement", "Commentaire", "Actions"],
    pagination:{
        limit:10,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "financements/fetch",
        then: (data) => data.map((item) =>
            [
                item.nom_fin,
                item.commentaire,
                html(
                `<div class='dropdown'>
                      <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <div class='dropdown-menu dropdown-menu-end' style=''>
                          <a  href='javascript:void(0)' id='show_financement' data-url='financements/show/`+item.id+`' class='dropdown-item' title=''>
                            Voir détail
                          </a>
                          <a href='javascript:void(0)' id='edit_financement' data-id='`+item.id+`' class='dropdown-item' title=''>
                            Editer
                          </a>
                          <a href='javascript:void(0)' id='delete_financement' data-id='`+item.id+`' class='dropdown-item' title=''>
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
}).render(document.getElementById("table_financement"));