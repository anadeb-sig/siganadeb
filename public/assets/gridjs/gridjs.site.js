function action_site(id) {
  let actions = `
    <div class='dropdown'>
        <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <div class='dropdown-menu dropdown-menu-end'>
            <a href='javascript:void(0)' id='show_site' data-url='sites/show/${id}' data-id='${id}' class='dropdown-item'>
              Voir detail
            </a>
            <a href='javascript:void(0)' id='edit_site' data-id='${id}' class='dropdown-item'>
              Editer
            </a>
            <a href='javascript:void(0)' id='delete_site' data-id='${id}' class='dropdown-item'>
              Supprimer
            </a>
        </div>
    </div>
  `;

  return gridjs.html(actions);
}

function rendtableau_site(nom_reg, nom_comm, nom_cant, nom_site) {
  fetch(`sites/fetch?nom_reg=${nom_reg}&nom_comm=${nom_comm}&nom_cant=${nom_cant}&nom_site=${nom_site}`)
    .then(response => response.json())
    .then(data => {
      let tableDataa = data.map(item => [
        item.nom_reg,
        item.nom_comm,
        gridjs.html(`<span id="site_${item.id}">${item.nom_site}</span>`),  // Ajout temporaire
        action_site(item.id)
      ]);

      const grid = new gridjs.Grid({
        columns: ["Région", "Commune", "Site", "Actions"],
        data: tableDataa,
        sort: true,
        search: true,
        pagination: {
          limit: 20,
          enabled: true,
          summary: true
        },
        language: {
          search: {
            placeholder: '🔍 Recherche...'
          },
          pagination: {
            previous: 'Précédent',
            next: 'Suivant',
            showing: '😃 Affichage de ',
            results: () => 'enregistrements'
          }
        }
      });

      const tableElement = document.getElementById('table_site');

      if (tableElement) {
        tableElement.textContent = '';
        grid.render(tableElement);
        grid.forceRender();

        // Mise à jour du bouton total
        const header = document.querySelector('#table_site .gridjs-head');
        if (header) {
          let button = header.querySelector('button#totalButton');
          if (!button) {
            button = document.createElement('button');
            button.id = 'totalButton';
            button.classList.add('btn', 'btn-outline-primary');
            header.appendChild(button);
          }
          button.textContent = `Total : ${grid.config.data.length ? grid.config.data.length.toLocaleString('fr-FR') : 0}`;
          
          let excelButton = document.getElementById('excelButton');
          if (excelButton) {
            // Fonction de génération Excel au clic
            excelButton.addEventListener('click', () => {
              const wb = XLSX.utils.book_new();
              const ws = XLSX.utils.aoa_to_sheet([
                ["Région","Préfecture", "Commune","Canton","Village", "Site", "Descriptio", "Budget","Status"],  // En-têtes
                ...data.map(item => [item.nom_reg,item.nom_pref, item.nom_comm,item.nom_cant,item.nom_vill, item.nom_site,item.descrip_site,item.budget, item.statu])
              ]);

              XLSX.utils.book_append_sheet(wb, ws, 'Sites');
              XLSX.writeFile(wb, 'sites_export.xlsx');
            });
          }
        
        }

        // Vérification pour chaque site et mise à jour du tableau
        for (const item of data) {
          fetch(`sites/verification?id=${item.id}`)
            .then(response => response.json())
            .then(dataa => {
              const siteCell = document.getElementById(`site_${item.id}`);
              if (siteCell) {
                if (dataa > 0) {
                  siteCell.innerHTML = `<a href='sites/detail/${item.id}'>${item.nom_site}</a>`;
                }
              }
            })
            .catch(error => {
              console.error(`Erreur lors de la vérification du site ${item.id}:`, error);
            });
        }
      } else {
        console.error("L'élément avec l'ID 'table_site' est introuvable.");
      }
    })
    .catch(error => {
      console.error('Erreur lors de la récupération des données:', error);
    });
}


