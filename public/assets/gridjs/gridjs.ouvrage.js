function statu_sta(statu) {
  let action = '';
  switch(statu) {
    case "CONTRAT_NON_SIGNE":
      action += `<span class="badge bg-danger">Contrat non signé</span>`;
      break;
    case "EC":
      action += `<span class="badge bg-light text-body">En cours</span>`;
      break;
      case "FERME":
        action += `<span class="badge bg-primary text-white">Exécution terminée</span>`;
        break;
    case "SUSPENDU":
      action += `<span class="badge bg-secondary">Suspendu</span>`;
      break;
    case "NON_DEMARRE":
      action += `<span class="badge bg-warning">Non démarré</span>`;
      break;
    case "RT":
      action += `<span class="badge bg-success">Réception technique</span>`;
      break;
    case "RP":
      action += `<span class="badge bg-success">Réception provisoire</span>`;
      break;
    case "RD":
      action += `<span class="badge bg-success">Réception définitive</span>`;
      break;
      default:
        action += `<span class="badge bg-dark">Inconnu</span>`
  }

  return gridjs.html(action);
}

function action_ouvrage(id, statu) {
  let actions = `
    <div class='dropdown'>
        <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <div class='dropdown-menu dropdown-menu-end' style=''>
            <a href='javascript:void(0)' id='show_ouvrage' data-url='/ouvrages/show/${id}' data-id='${id}' class='dropdown-item'>
              Voir détail
            </a>
            <a href='javascript:void(0)' id='edit_ouvrage' data-id='${id}' class='dropdown-item'>
              Editer
            </a>
            <a href='javascript:void(0)' id='delete_ouvrage' data-id='${id}' class='dropdown-item'>
              Supprimer
            </a>
          `;

  // Ajouter des actions supplémentaires en fonction du statut
  if (statu === "NON_DEMARRE") {
    actions += `
      <a href='/ouvrages/update/status/${id}/EC' class='dropdown-item'>
        Demarrage
      </a>
    `;
  } else if (statu === "EC") {
    actions += `
      <a href='javascript:void(0);' id="demande_suspension" data-url='/sites/show/${id}'  data-id='${id}' class='dropdown-item'>
        Suspendre
      </a>
      <a href='/ouvrages/update/status/${id}/RT' class='dropdown-item'>
        Réception technique
      </a>
      <a href='/ouvrages/update/status/${id}/RP' class='dropdown-item'>
        Réception provisoir
      </a>
      <a href='/ouvrages/update/status/${id}/RD' class='dropdown-item'>
        Réception définitve
      </a>
    `;
  }else if (statu === "FERME") {
    actions += `
      <a href='/ouvrages/update/status/${id}/RT' class='dropdown-item'>
        Réception technique
      </a>
      <a href='/ouvrages/update/status/${id}/RP' class='dropdown-item'>
        Réception provisoir
      </a>
      <a href='/ouvrages/update/status/${id}/RD' class='dropdown-item'>
        Réception définitve
      </a>
    `;
  } else if (statu === "RT") {
    actions += `
      <a href='/ouvrages/update/status/${id}/RP' class='dropdown-item'>
        Réception provisoir
      </a>
      <a href='/ouvrages/update/status/${id}/RD' class='dropdown-item'>
        Réception définitve
      </a>
    `;
  }else if (statu === "RP") {
    actions += `
      <a href='/ouvrages/update/status/${id}/RD' class='dropdown-item'>
        Réception définitve
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

function rendtableau_ouvrage(nom_reg, nom_comm, nom_ouvrage, nom_projet, nom_type, nom_fin, statu) {
  let url = '';
  let currentUrl = window.location.pathname; // Obtient l'URL courante
  if(/^\/ouvrages\/statut\/.*/.test(currentUrl)){
    let variable = document.getElementById('statu_ouvrage').value;
    url = `/ouvrages/fetch?nom_reg=${nom_reg}&nom_comm=${nom_comm}&nom_ouvrage=${nom_ouvrage}&nom_projet=${nom_projet}&nom_type=${nom_type}&nom_fin=${nom_fin}&statu=${variable}`
  }else{
    url = `/ouvrages/fetch?nom_reg=${nom_reg}&nom_comm=${nom_comm}&nom_ouvrage=${nom_ouvrage}&nom_projet=${nom_projet}&nom_type=${nom_type}&nom_fin=${nom_fin}&statu=${statu}`;
  }
fetch(url)
  .then(response => response.json())
  .then(data => {
      let tableDataa = data.data.map(item => [
        item.nom_site,
        item.nom_ouvrage, 
        item.nom_fin,
        statu_sta(item.statu),
        action_ouvrage(item.id, item.statu)                
      ]);
      
      let grid = new gridjs.Grid({
        columns: ["Site", "Ouvrage", "Financement","Etat de l'ouvrage","Actions"],
        data: tableDataa,
        sort:!0,
        search:!0,
        pagination:{
            limit:10,
            enabled:true,
            summary:true
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

      const tableElement = document.getElementById('table_ouvrage');

      if (tableElement) {
          tableElement.textContent = '';
          grid.render(tableElement);
          grid.forceRender();          
          
          const header = document.querySelector('#table_ouvrage .gridjs-head');
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

          let excelButton = document.getElementById('excelButton');
          if (excelButton) {
            // Fonction de génération Excel au clic
            excelButton.addEventListener('click', () => {
              const wb = XLSX.utils.book_new();
              const ws = XLSX.utils.aoa_to_sheet([
                ["Région","Préfecture", "Commune","Canton","Village", "Site", "Ouvrage", "Description","Type", "Projet","Financement"],  // En-têtes
                ...data.data.map(item => [item.nom_reg,item.nom_pref, item.nom_comm,item.nom_cant,item.nom_vill, item.nom_site,item.nom_ouvrage,item.descrip,item.nom_type, item.name, item.nom_fin])
              ]);

              XLSX.utils.book_append_sheet(wb, ws, 'Ouvrages');
              XLSX.writeFile(wb, 'ouvrages_export.xlsx');
            });
          }
      } else {
          console.error("L'élément avec l'ID 'table_ouvrage' est introuvable.");
      }
    });
  }