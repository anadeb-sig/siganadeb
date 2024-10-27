import { html } from "/assets/gridjs/gridjs.module.js";



const grid = new gridjs.Grid({columns:
    ["Région", "Préfecture", "Commune", "Canton", "Village", "Actions"],
    pagination:{
        limit:10,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "villages/fetch",
        then: (data) => data.map((item) =>
            [
              item.nom_reg,
              item.nom_pref,
              item.nom_comm,
              item.nom_cant,
              html(
                  "<div class='pull-right' role='group'>"+
                    "<form method='get' action='ecoles/ecole_par_village'>"+
                      "<input type='hidden' name='id' value='"+item.id+"'>"+
                      "<button type='submit' style='color: #3f73b4;border: none;background-color: white;' title='Ecoles prises en charge dans le village'>"+
                          item.nom_vill+
                      "</button>"+                        
                    "</form>"
                ),
                html(
                  `<div class='dropdown'>
                      <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <div class='dropdown-menu dropdown-menu-end' style=''>
                          <a href='javascript:void(0)' id='show_village' data-url='villages/show/`+item.id+`' data-id='`+item.id+`' data-nom_reg='`+item.nom_reg+`' data-nom_pref='`+item.nom_pref+`' data-nom_comm='`+item.nom_comm+`' data-nom_cant='`+item.nom_cant+`' class='dropdown-item'>
                            Voir détail
                          </a>
                          <a href='javascript:void(0)' id='edit_village' data-id='`+item.id+`' class='dropdown-item'>
                            Editer
                          </a>
                          <a href='javascript:void(0)' id='delete_village' data-id='`+item.id+`' class='dropdown-item'>
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
      

})

/**grid.on('rowClick', () => {
  const rowData = data;
  const id = rowData[0];
  console.log(id);
  //window.location.href = `ecole_par_village?id=${id}`; // Rediriger vers autre_page.html avec l'ID en tant que paramètre d'URL
});*/

grid.render(document.getElementById("table_village"));

