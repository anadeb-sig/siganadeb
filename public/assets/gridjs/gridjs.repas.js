new gridjs.Grid({columns:
    ["Région","Cantine", "Ecole", "Nb plats garçons", "Nb plats filles", "Date de fourniture", "Actions"],
    pagination:{
        limit:10,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "repas/fetch",
        then: (data) => data.map((item) =>
            [
                item.nom_reg, 
                item.nom_ecl,
                item.nom_cla,
                item.effect_gar,
                item.effect_fil,
                item.date_rep,
                gridjs.html(
                  `<div class='dropdown'>
                      <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <div class='dropdown-menu dropdown-menu-end' style=''>
                          <a href='javascript:void(0)' id='show_repas' data-nom_cla='`+item.nom_cla+`' data-nom_ecl='`+item.nom_ecl+`' data-url='repas/show/`+item.id+`' data-id='`+item.id+`' class='dropdown-item' title='Détail du repas'>
                            Voir détail
                          </a>
                          <a href='javascript:void(0)' id='edit_repas' data-id='`+item.id+`' data-inscrit_id='`+item.inscrit_id+`' class='dropdown-item' title='Edit du repas'>
                            Editer
                          </a>
                          <a href='javascript:void(0)' id='delete_repas' data-id='`+item.id+`' class='dropdown-item' title='Supprimer le repas'>
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
}).render(document.getElementById("table_repas"));