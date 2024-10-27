import { html } from "/assets/gridjs/gridjs.module.js";

new gridjs.Grid({columns:
    ["Région", "Commune", "Cantine", "Ecole","Effectif filles","Effectif garçons", "Actions"],
    pagination:{
        limit:10,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "/inscrits/fetch/zero",
        then: (data) => data.map((item) =>
            [
                item.nom_reg,
                item.nom_comm,
                item.nom_ecl,
                item.nom_cla,
                item.effec_fil,
                item.effec_gar,
                html(
                `<div class='dropdown'>
                    <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <div class='dropdown-menu dropdown-menu-end' style=''>
                        <a href='javascript:void(0)' id='edit_inscrit' data-id='`+item.id+`' class='dropdown-item' title='Edit l\'enseignement'>
                          Editer
                        </a>
                        <a href='javascript:void(0)' id='delete_inscrit_zero' data-id='`+item.id+`' class='dropdown-item' title='Supprimer l\'enseignement'>
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
}).render(document.getElementById("table_inscritzero"));