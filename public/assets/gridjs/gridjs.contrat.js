function jourscontrat(date10, date20){
  // Créez deux objets Date avec vos dates
  var date1 = new Date(date10);
  var date2 = new Date(date20);
  if(date1 < date2) {
    var differenceEnMillisecondes = (date2-date1);
    // Convertissez la différence en jours
    var differenceEnJours = differenceEnMillisecondes/(1000*60*60*24);
    return differenceEnJours;
  }else if(date1 > date2) {
    var differenceEnMillisecondes = (date1-date2);
    // Convertissez la différence en jours
    var differenceEnJours = differenceEnMillisecondes/(1000*60*60*24);
    return differenceEnJours;
  }else{
    return "0";
  }
}

function action_contrat(id_signer, date_fin, statu) {
  let extraLink = '';
  
  if (statu === "NON_SUSPENDU") {
    extraLink = `<a href='javascript:void(0);' id="demande_jours" data-id='${id_signer}' data-date_fin='${date_fin}' class='dropdown-item'>
                    Demande de jours de plus
                  </a>`;
  }

  return gridjs.html(
    `<div class='dropdown'>
        <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <div class='dropdown-menu dropdown-menu-end'>
            <a href='contrats/show/${id_signer}' id='show_contrat' class='dropdown-item'>
              Voir détail
            </a>
            <a href='javascript:void(0)' id='edit_contrat' data-id='${id_signer}' class='dropdown-item'>
              Editer
            </a>
            <a href='javascript:void(0)' id='delete_contrat' data-id='${id_signer}' class='dropdown-item'>
              Supprimer
            </a>
            ${extraLink}
        </div>
    </div>`
  );
}

function rendtableau_contrat(code, date_ordre_debut, date_ordre_fin, date_demarre_debut, date_demarre_fin) {
  fetch(`contrats/fetch?code=${code}&date_ordre_debut=${date_ordre_debut}&date_ordre_fin=${date_ordre_fin}&date_demarre_debut=${date_demarre_debut}&date_demarre_fin=${date_demarre_fin}`)
    .then(response => response.json())
    .then(data => {
      let tableDataa = data.data.map(item => [
        item.code,
        item.date_sign,
        new Date(item.date_debut).toISOString().substring(0, 10),
        new Date(item.date_fin).toISOString().substring(0, 10),
        jourscontrat(item.date_debut, item.date_fin),
        gridjs.html(`<span id="contrat_${item.id}">Jours du contrat</span>`),  // Cellule temporaire
        action_contrat(item.id, item.date_fin, item.statu)
      ]);

      let grid = new gridjs.Grid({
        columns: ["Code du contrat", "Date signature", "Ordre de service", "Date fin contrat", "Durée du contrat", "Status du contrat", "Actions"],
        data: tableDataa,
        sort: true,
        search: true,
        pagination: {
          limit: 20,
          enabled: true,
          summary: true
        },
        language: {
          search: { placeholder: '🔍 Recherche...' },
          pagination: {
            previous: 'Précédent',
            next: 'Suivant',
            showing: '😃 Affichage de ',
            results: () => 'enregistrements'
          }
        }
      });

      const tableElement = document.getElementById('table_contrat');
      if (tableElement) {
        tableElement.textContent = ''; // Efface le contenu précédent
        grid.render(tableElement); // Rend la grille dans l'élément
        
        const header = document.querySelector('#table_contrat .gridjs-head');
        if (header) {
          let button = header.querySelector('button');
          if (!button) {
            button = document.createElement('button');
            button.id = 'totalButton';
            button.classList.add('btn', 'btn-outline-primary');
            header.appendChild(button);
          }
          button.textContent = `Total : ${data.total.toLocaleString('fr-FR')}`;
        }

        // Appel à l'API de vérification pour chaque site
        data.data.forEach(item => {
          fetch(`contrats/email_entreprise/${item.id}`)
            .then(response => response.json())
            .then(dataa => {
              // Vérification de l'email
              const email = dataa.email ? dataa.email : 'Email non disponible';
              
              // Calcul de la différence en jours
              let date1 = new Date();
              let date2 = new Date(item.date_fin);
              let date3 = new Date(item.date_debut);

              // Mise à jour de la cellule correspondante dans le tableau
              const siteCell = document.getElementById(`contrat_${item.id}`);

              if (date3 > date1) {
                siteCell.innerHTML = "<a class='badge bg-yellow font-size-12'>Non demarré</a>";
              }else{
                let differenceEnJours = Math.floor((date2 - date1) / (1000 * 60 * 60 * 24));
                if (siteCell && item.statu != "SUSPENDU") {
                  if (differenceEnJours <= -45) {
                    siteCell.innerHTML = "<div class='badge bg-secondary font-size-12'>Pénalité de " + Math.abs(differenceEnJours) + " Jour(s)</div>";
                  } else if (differenceEnJours < 0) {
                    siteCell.innerHTML = "<a class='badge bg-danger font-size-12' href='mailto:" + email + "'>Pénalité de " + Math.abs(differenceEnJours) + " jour(s)</a>";
                  } else if (differenceEnJours === 0) {
                    siteCell.innerHTML = "<a class='badge bg-warning font-size-12' href='mailto:" + email + "'>Jours de contrat terminés</a>";
                  } else if (differenceEnJours > 0) {
                    siteCell.innerHTML = "<a class='badge bg-success font-size-12'>Reste " + differenceEnJours + " Jour(s)</a>";
                  }
                } else if (item.statu === "SUSPENDU") {
                  siteCell.innerHTML = "<a class='badge bg-success font-size-12'>Contrat suspendu</a>";
                } else if (date1 > date2 && item.statu === "FERME") {
                  siteCell.innerHTML = "<a class='badge bg-success font-size-12'>Contrat cloturé</a>";
                }else {
                  console.error(`L'élément avec l'ID contrat_${item.id} est introuvable.`);
                }
              }
            })
            .catch(error => {
              console.error(`Erreur lors de la vérification de l'email pour le contrat ${item.id}:`, error);
            });
        });
      } else {
        console.error("L'élément avec l'ID 'table_contrat' est introuvable.");
      }
    })
    .catch(error => {
      console.error('Erreur lors de la récupération des données:', error);
    });
}
