function action_ouvrage(id){
  return gridjs.html(
    `<div class='dropdown'>
        <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <div class='dropdown-menu dropdown-menu-end' style=''>
            <a href='javascript:void(0)' id='show_ouvrage' data-url='ouvrages/show/${id}' data-id='${id}' class='dropdown-item'>
              Voir détail
            </a>
            <a href='javascript:void(0)' id='edit_ouvrage' data-id='${id}' class='dropdown-item'>
              Editer
            </a>
            <a href='javascript:void(0)' id='delete_ouvrage' data-id='${id}' class='dropdown-item'>
              Supprimer
            </a>
        </div>
    </div>`
  )
}


function rendtableau_ouvrage(nom_reg, nom_comm, nom_ouvrage, nom_projet, nom_type, nom_fin) {
fetch(`ouvrages/fetch?nom_reg=${nom_reg}&nom_comm=${nom_comm}&nom_ouvrage=${nom_ouvrage}&nom_projet=${nom_projet}&nom_type=${nom_type}&nom_fin=${nom_fin}`)
  .then(response => response.json())
  .then(data => {
      let tableDataa = data.data.map(item => [
        item.nom_site,item.nom_type, item.nom_ouvrage, item.name, item.nom_fin,
        action_ouvrage(item.id)                
      ]);
      
      let grid = new gridjs.Grid({
        columns: ["Site","Type ouvrage", "Ouvrage", "Projets", "Financement","Actions"],
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