function rendtable(datedebut,datefin,nom_reg,nom_cant,nom_ecl,nom_fin){
  fetch('/repas/compta?start='+datedebut+'&end='+datefin+'&nom_reg='+nom_reg+'&nom_cant='+nom_cant+'&nom_ecl='+nom_ecl+'&nom_fin='+nom_fin)
  .then(response => response.json())
  .then(data => {
    // Créer un tableau d'objets pour organiser les données par date et école
    const tableData = {};
    const totalAmountsBySchool = {};
    var total = 0;
    // Itération sur les données pour remplir le tableau
    data.forEach(item => {
      if (!tableData[item.date_rep]) {
        tableData[item.date_rep] = {};
      }
      if (!tableData[item.date_rep][item.nom_ecl]) {
        tableData[item.date_rep][item.nom_ecl] = {
          montant: 0,
          count: 0
        };
      }
      tableData[item.date_rep][item.nom_ecl].montant += (parseInt(item.garcons) + parseInt(item.filles));
      tableData[item.date_rep][item.nom_ecl].count += 1;

      if (!totalAmountsBySchool[item.nom_ecl]) {
        totalAmountsBySchool[item.nom_ecl] = 0;
      }
      totalAmountsBySchool[item.nom_ecl] += (parseInt(item.garcons) + parseInt(item.filles));
      tableData[item.date_rep][item.nom_ecl] = gridjs.html(`${total = parseInt(item.garcons) + parseInt(item.filles)}<br/><span style="color: #3f73b4;font-style: italic;">${parseInt(total)*item.cout_unt}</span>`);
      
    });

    // Extraire les dates uniques et les écoles uniques pour créer les colonnes
    const dates = Object.keys(tableData).sort();
    const ecoles = [...new Set(data.map(item => item.nom_ecl))];

    const columns = ['Date'];
    ecoles.forEach(ecole => {
      columns.push({
        label: ecole,
        name: `${ecole} (Total: ${totalAmountsBySchool[ecole]})`
      });
    });

    // Créer la grille GridJS avec les données organisées
    const grid = new gridjs.Grid({
      columns,
      pagination:{
        limit:20,
        enabled:true,
        summary:true
      },
      sort:!0,
      search:!0,

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
      },
      data: dates.map(date => {
        const row = [date];
        ecoles.forEach(ecole => {
          row.push(tableData[date][ecole] || '');
        });
        return row;
      })
    });

    document.getElementById('table_compta').textContent = '';

    grid.render(document.getElementById('table_compta')); 
    grid.forceRender();

    /*document.getElementById('export-btn').addEventListener('click', function() {
      const exporter = Grid.download(grid);
      exporter.export('xlsx', {
        filename: 'compta.xlsx',
        all: true // Exporter toutes les pages paginées
      });
    });*/

  });

  
}