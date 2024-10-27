import { html } from "/assets/gridjs/gridjs.module.js";

new gridjs.Grid({columns:
    ["Rôle", "Titre", "Actions"],
    pagination:{
        limit:20,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "/roles/fetch",
        then: (data) => data.map((item) =>
            [
                item.name,  
                item.guard_name,            
                html(
                `<div class='dropdown'>
                      <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <div class='dropdown-menu dropdown-menu-end' style=''>
                          <a href='roles/edit/`+item.id+`' id='edit_classe' class='dropdown-item' title='Edit l\'enseignement'>
                            Editer
                          </a>
                          <a href='javascript:void(0)'  id='delete_role' data-id='`+item.id+`' class='dropdown-item' title='Supprimer l\'enseignement'>
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
}).render(document.getElementById("table_role"));