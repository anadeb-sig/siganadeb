function rendtableau_realisation(nom_reg, nom_comm, nom_site, nom_ouvrage) {
  
  fetch(`realisations/fetch?nom_reg=${encodeURIComponent(nom_reg)}&nom_comm=${encodeURIComponent(nom_comm)}&nom_site=${encodeURIComponent(nom_site)}&nom_ouvrage=${encodeURIComponent(nom_ouvrage)}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`Erreur HTTP ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // Préparer les données pour Grid.js
      const tableDataa = data.data.map(item => [
        item.nom_ouvrage,
        item.qte,
        item.prix_unit,
        gridjs.html(
          `<div class='dropdown'>
              <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  <i class="fa-solid fa-ellipsis-vertical"></i>
              </button>
              <div class='dropdown-menu dropdown-menu-end' style=''>
                  <a href='javascript:void(0)' id='edit_realisation' data-estimation_id='` + item.estimation_id + `' data-ouvrage_id='` + item.ouvrage_id + `'  data-id='` + item.id + `' class='dropdown-item'>
                    Editer
                  </a>
                  <a href='javascript:void(0)' id='delete_realisation' data-id='` + item.id + `' class='dropdown-item'>
                    Supprimer
                  </a>
              </div>
          </div>`
        )
      ]);

      // Créer une nouvelle instance Grid.js
      const grid = new gridjs.Grid({
        columns: [
          { name: "Ouvrage"},
          { name: "Quantité"},
          { name: "Prix unitaire (FCFA)"},
          { name: "Actions"}
        ],
        data: tableDataa,
        sort: true,
        search: {
          placeholder: '🔍 Rechercher...',
        },
        pagination: {
          limit: 10,
          enabled: true,
          summary: true
        },
        style: {
          table: {
            border: '1px solid #ccc',
          },
          th: {
            backgroundColor: '#f5f5f5',
            color: '#333',
          },
        },
        language: {
          pagination: {
            previous: 'Précédent',
            next: 'Suivant',
            showing: 'Affichage de',
            results: 'enregistrements',
          },
        },
      });

      // Sélectionner l'élément du tableau
      const tableElement = document.getElementById('table_realisation');
      if (tableElement) {
        tableElement.textContent = ''; // Réinitialiser l'élément
        grid.render(tableElement);
        grid.forceRender();  

        // Ajouter ou mettre à jour le bouton de total
        const header = document.querySelector('#table_realisation .gridjs-head');
        if (header) {
          let button = header.querySelector('#totalButton');
          if (!button) {
            button = document.createElement('button');
            button.id = 'totalButton';
            button.classList.add('btn', 'btn-outline-primary', 'ms-3');
            header.appendChild(button);
          }
          // Afficher le total en format CFA
          button.textContent = `Total : ${data.total.toLocaleString('fr-FR')}`;
        } else {
          console.error("L'élément d'en-tête du tableau est introuvable.");
        }
      } else {
        console.error("L'élément avec l'ID 'table_realisation' est introuvable.");
      }
    })
    .catch(error => {
      console.error('Erreur lors de la récupération des données :', error);
    });
}
