function action_demande(id,iid, statu) {
  let actions = `
    <div class='dropdown'>
        <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <div class='dropdown-menu dropdown-menu-end'>
            <a href='demandes/detail/${id}/${iid}' class='dropdown-item'>
              Voir detail
            </a>
  `;

  // Ajouter des actions supplémentaires en fonction du statut
  if (statu === "En attente") {
    actions += `
      <a href='demandes/approvals/${id}/Approuvé' class='dropdown-item'>
        Approuver
      </a>
    `;
  }

  // Fermer les balises HTML
  actions += `
        </div>
    </div>
  `;

  return gridjs.html(actions);
}

function statu_statu(statu) {
  let action = '';

  switch(statu) {
    case "En attente":
      action += `<span class="badge bg-danger">En attente d'approbation</span>`;
      break;
    case "Approuvé":
      action += `<span class="badge bg-light text-body">Approuvé</span>`;
      break;
    default:
      action += `<span class="badge bg-dark">Inconnu</span>`;
  }

  return gridjs.html(action);
}

function rendtableau_demande(nom_reg, nom_comm, nom_site,statu,user,titre,date_demarre_debut,date_demarre_fin) {
  fetch(`demandes/fetch?nom_reg=${nom_reg}&nom_comm=${nom_comm}&nom_site=${nom_site}&statu=${statu}&user=${user}&titre=${titre}&date_demarre_debut=${date_demarre_debut}&date_demarre_fin=${date_demarre_fin}`)
    .then(response => response.json())
    .then(data => {
      let tableDataa = data.data.map(item => [
        item.nom_reg,
        item.nom_comm,
        item.nom_site,
        statu_statu(item.statu),
        action_demande(item.id,item.iid, item.statu)                  
      ]);

      const grid = new gridjs.Grid({
        columns: ["Région", "Commune", "Site", "Status", "Actions"],
        data: tableDataa,
        sort: true,
        search: true,
        pagination: {
          limit: 10,
          enabled: true,
          summary: true
        },
        style: {
          th: {
            // Personnalisation des en-têtes de colonnes
          },
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

      const tableElement = document.getElementById('table_demande');

      if (tableElement) {
        tableElement.textContent = '';
        grid.render(tableElement);

        // Mettre à jour le bouton total
        const header = document.querySelector('#table_demande .gridjs-head');
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
        console.error("L'élément avec l'ID 'table_demande' est introuvable.");
      }
    })
    .catch(error => {
      console.error('Erreur lors de la récupération des données:', error);
    });
}
