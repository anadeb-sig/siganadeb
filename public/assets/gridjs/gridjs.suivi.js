function action_suivi(id, site_iid){
  return gridjs.html(
      `<div class='dropdown'>
          <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
              <i class="fa-solid fa-ellipsis-vertical"></i>
          </button>
          <div class='dropdown-menu dropdown-menu-end' style=''>
              <a href='suivis/show/${id}' class='dropdown-item'>
                Voir détail
              </a>
              <a href='javascript:void(0)' id='edit_suivi' data-id='${id}' class='dropdown-item'>
                Editer
              </a>
              <a href='javascript:void(0)' id='delete_suivi' data-id='${id}' class='dropdown-item'>
                Supprimer
              </a>
          </div>
      </div>`
  )
}

function rendtableau_suivi(nom_reg, nom_comm, nom_site, date_demarre_debut,date_demarre_fin) {
  fetch(`suivis/fetch?nom_reg=${nom_reg}&nom_comm=${nom_comm}&nom_site=${nom_site}&date_demarre_debut=${date_demarre_debut}&date_demarre_fin=${date_demarre_fin}`)
    .then(response => response.json())
    .then(data => {
        let tableDataa = data.data.map(item => [
        item.nom_reg,
        item.nom_comm,
        item.nom_site,
        item.date_suivi,
        item.conf_plan,
        item.niv_eval,
        action_suivi(item.id, item.site_iid)               
    ]);

    let grid = new gridjs.Grid({columns:
      ["Région", "Commune", "Site", "Date de suivi", "Respect du plan","Indice d'évolution", "Actions"],
        data: tableDataa,
        sort:!0,
        search:!0,
        pagination:{
          limit:10,
          enabled:true,
          summary:true
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
  });

  const tableElement = document.getElementById('table_suivi');

    if (tableElement) {
        tableElement.textContent = '';
        grid.render(tableElement);
        grid.forceRender();          
        
        const header = document.querySelector('#table_suivi .gridjs-head');
        if (header) {
            let button = header.querySelector('button');
            if (!button) {
                button = document.createElement('button');
                button.id = 'totalButton';
                button.classList.add('btn', 'btn-outline-primary');
                header.appendChild(button);
            }
            button.textContent = `Total : ${data.total.toLocaleString('fr-FR')}`;
        } else {
            console.error("L'élément d'en-tête du tableau est introuvable.");
        }
      } else {
          console.error("L'élément avec l'ID 'table_etatpaiements' est introuvable.");
      }
    });
  }