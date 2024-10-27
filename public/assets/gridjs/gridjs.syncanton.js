function rendtableau_cant(datedebut,datefin,nom_reg,nom_cant,nom_fin){
  fetch('/repas/canton?start='+datedebut+'&end='+datefin+'&nom_reg='+nom_reg+'&nom_cant='+nom_cant+'&nom_fin='+nom_fin)
  .then(response => response.json())
  .then(data => {
    // Calculer la somme des colonnes
    const som_nbrjr_pri = data.reduce((acc, row) => acc + parseInt(row.nbrjr_pri), 0);
    const som_gar_pri = data.reduce((acc, row) => acc + parseInt(row.prim_gar), 0);
    const som_fil_pri = data.reduce((acc, row) => acc + parseInt(row.prim_fil), 0);
    const som_t_pri = data.reduce((acc, row) => acc + (parseInt(row.prim_gar) + parseInt(row.prim_fil)), 0);
    const som_m_t_pri = data.reduce((acc, row) => acc + (parseInt(row.prim_gar) + parseInt(row.prim_fil))*parseInt(row.cout_unt), 0);
    const som_gar_pre = data.reduce((acc, row) => acc + parseInt(row.pres_gar), 0);
    const som_fil_pre = data.reduce((acc, row) => acc + parseInt(row.pres_fil), 0);
    const som_t_pre = data.reduce((acc, row) => acc + (parseInt(row.pres_gar) + parseInt(row.pres_fil)), 0);
    const som_m_t_pre = data.reduce((acc, row) => acc + (parseInt(row.pres_gar) + parseInt(row.pres_fil))*parseInt(row.cout_unt), 0);

    const som_nbrjr_pre = data.reduce((acc, row) => acc + parseInt(row.nbrjr_pre), 0);
    const gar_Pri_Pre = data.reduce((acc, row) => acc + (parseInt(row.prim_gar) + parseInt(row.pres_gar)), 0);
    const fil_Pri_Pre = data.reduce((acc, row) => acc + (parseInt(row.prim_fil) + parseInt(row.pres_fil)), 0);
    const som_t_Pri_Pre = data.reduce((acc, row) => acc + (parseInt(row.prim_gar) + parseInt(row.pres_gar)+ parseInt(row.prim_fil) + parseInt(row.pres_fil)), 0);
    const som_m_Pri_Pre = data.reduce((acc, row) => acc + parseInt((parseInt(row.cout_unt) + parseInt(row.cout_unt))/2)*(parseInt(row.prim_gar) + parseInt(row.pres_gar) + parseInt(row.prim_fil) + parseInt(row.pres_fil)), 0);

    // Créer un nouveau tableau qui contient les données et la somme de la colonne
    const tableData = [
      [,,,,,som_gar_pri, som_fil_pri, som_t_pri, som_m_t_pri,, som_gar_pre, som_fil_pre, som_t_pre, som_m_t_pre, gar_Pri_Pre, fil_Pri_Pre, som_t_Pri_Pre, som_m_Pri_Pre],
      []
    ];
    const tableDataa = [
      ...data.map(row => 
        [
          row.nom_reg,
          row.nom_comm,
          row.nom_cant,
          parseInt(row.nbrjr_max), 
          parseInt(row.nbrjr_pri), 
          parseInt(row.prim_gar),
          parseInt(row.prim_fil),
          parseInt(row.prim_gar) + parseInt(row.prim_fil),
          //parseInt(row.cout_unt),
          (parseInt(row.prim_gar) + parseInt(row.prim_fil))*parseInt(row.cout_unt),
          
          parseInt(row.nbrjr_pre), 
          parseInt(row.pres_gar),
          parseInt(row.pres_fil),

          parseInt(row.pres_gar) + parseInt(row.pres_fil),
          //parseInt(row.cout_unt),
          (parseInt(row.pres_gar) + parseInt(row.pres_fil))*parseInt(row.cout_unt),
          parseInt(row.prim_gar) + parseInt(row.pres_gar),
          parseInt(row.prim_fil) + parseInt(row.pres_fil),
          parseInt(row.prim_gar) + parseInt(row.pres_gar)+ parseInt(row.prim_fil) + parseInt(row.pres_fil),
          //parseInt((parseInt(row.cout_unt) + parseInt(row.cout_unt))/2),
          parseInt((parseInt(row.cout_unt) + parseInt(row.cout_unt))/2)*(parseInt(row.prim_gar) + parseInt(row.pres_gar) + parseInt(row.prim_fil) + parseInt(row.pres_fil)),
        ]),
    ];

    // Créer une instance de la grille
    const myGrid = new gridjs.Grid({
      columns: 
      [
        {
          id: "region",
          name: "Région"
        },
        {
          id: "commune",
          name: "Commune"
        },
        {
          id: "Canton",
          name: "canton"
        },
        {
          id: "nbr_jr_max",
          name: tableData[0][3],
          columns: [{
            name: "Nbr jour max"
          }],
        },
        {
          id: "nbr_jr_pri",
          name: tableData[0][4],
          columns: [{
            name: "Nbr jour (Pri)"
          }],
        },
        {
          id: "gar_pri",
          name: tableData[0][5],
          columns: [{
            name: "G (Pri)"
          }],
        },

        {
          id: "fil_pri",
          name: tableData[0][6],
          columns: [{
            name: "F (Pri)"
          }],
        },

        {
          id: "t_pri",
          name: tableData[0][7],
          columns: [{
            name: "T (Pri)"
          }],
        },

        {
          id: "m_t_pri",
          name: tableData[0][8],
          columns: [{
            name: "M T (Prim)"
          }],
        },

        {
          id: "nbr_jr_pre",
          name: tableData[0][9],
          columns: [{
            name: "Nbr jour (Pre)"
          }],
        },

        {
          id: "gar_pre",
          name: tableData[0][10],
          columns: [{
            name: "G (Pre)"
          }],
        },

        {
          id: "fil_pre",
          name: tableData[0][11],
          columns: [{
            name: "F (Pre)"
          }],
        },

        {
          id: "t_pre",
          name: tableData[0][12],
          columns: [{
            name: "T (Pre)"
          }],
        },

        {
          id: "m_t_pre",
          name: tableData[0][13],
          columns: [{
            name: "M T (Pres)"
          }],
        },

        {
          id: "gar_Pri_Pre",
          name: tableData[0][14],
          columns: [{
            name:"G (Pri + Pré)"
          }],
        },

        {
          id: "fil_Pri_Pre",
          name: tableData[0][15],
          columns: [{
            name:"F (Pri + Pré)"
          }],
        },

        {
          id: "t_Pri_Pre",
          name: tableData[0][16],
          columns: [{
            name:"Total (Pri +Pré)"
          }],
        },
        {
          id: "m_Pri_Pre",
          name: tableData[0][17],
          columns: [{
            name:'Montant(Pri + Pré)'
          }],
        },

      ],
      fixedHeader: true,
      fixed: [1],
      data: tableDataa,
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
      } 

    });

    document.getElementById('table_parcanton').textContent = '';
    
    myGrid.render(document.getElementById('table_parcanton'));
    myGrid.forceRender();


    document.getElementById('export-btn').addEventListener('click', function() {
      // Obtenez toutes les données paginées du tableau Grid.js
      const d = [,,,
        myGrid.config.columns[3].name,
        myGrid.config.columns[4].name,              
        myGrid.config.columns[5].name,
        myGrid.config.columns[6].name,
        myGrid.config.columns[7].name,
        myGrid.config.columns[8].name,
        myGrid.config.columns[9].name,
        myGrid.config.columns[10].name,
        myGrid.config.columns[11].name,
        myGrid.config.columns[12].name,
        myGrid.config.columns[13].name,
        myGrid.config.columns[14].name,
        myGrid.config.columns[15].name,
        myGrid.config.columns[16].name,
        myGrid.config.columns[17].name
      ];   

    const da = [[
        myGrid.config.columns[0].name,
        myGrid.config.columns[1].name,
        myGrid.config.columns[2].name,
        myGrid.config.columns[3].columns[0].name,
        myGrid.config.columns[4].columns[0].name,
        myGrid.config.columns[5].columns[0].name,
        myGrid.config.columns[6].columns[0].name,
        myGrid.config.columns[7].columns[0].name,
        myGrid.config.columns[8].columns[0].name,
        myGrid.config.columns[9].columns[0].name,
        myGrid.config.columns[10].columns[0].name,
        myGrid.config.columns[11].columns[0].name,
        myGrid.config.columns[12].columns[0].name,
        myGrid.config.columns[13].columns[0].name,
        myGrid.config.columns[14].columns[0].name,
        myGrid.config.columns[15].columns[0].name,
        myGrid.config.columns[16].columns[0].name,
        myGrid.config.columns[17].columns[0].name
      ]];
      //Concatenation des données de l'entete et du corps  
      const allData = [d].concat(da, myGrid.config.data);
    
      // Créez un objet de feuille de calcul Excel avec les données
      const worksheet = XLSX.utils.aoa_to_sheet(allData);
    
      // Créez un classeur Excel et ajoutez la feuille de calcul
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Feuille 1');

      // Générez le fichier Excel et téléchargez-le
      XLSX.writeFile(workbook, 'canton.xlsx');
    });
  })
}