import { html } from "/assets/gridjs/gridjs.module.js";

const search = window.location.search;
const params = new URLSearchParams(search);
const id = params.get('id');

  function statut(status){
    if(status == 0){
      return "<span><i class='mdi mdi-square-rounded font-size-10 text-danger me-2'></i> Non démarré</span>"
    }else{
      return "<span><i class='mdi mdi-square-rounded font-size-10 text-success me-2'></i> Démarré</span>"
    }
  }

  fetch("ecole_par_canton/"+id)
    .then(response => response.json())
    .then(data => {
      // Calculer la somme de la deuxième colonne
      const som_pre = data.reduce((acc, row) => acc + parseInt(row.nbr_gr_pre), 0);
      const som_pri = data.reduce((acc, row) => acc + parseInt(row.nbr_gr_pri), 0);

      const tableData = [
        [,,som_pre,som_pri,],
        []
      ];

      const tableDataa = [
        ...data.map(item => 
          [
            item.nom_reg,
            item.nom_pref,
            item.nom_comm,
            item.nom_cant,
            item.nom_ecl,
            item.nom_fin,
            item.nbr_gr_pre,
            item.nbr_gr_pri,
            html(statut(item.status))
          ]),
        ];

        const myGrid = new gridjs.Grid().updateConfig({
          columns: 
          [
            {
              id: "region",
              name: "Région"
            },
            {
              id: "prefecture",
              name: "Préfecture"
            },
            {
              id: "commune",
              name: "Commune"
            },
            {
              id: "canton",
              name: "Canton"
            },
            {
              id: "cantine",
              name: "Cantine"
            },
            {
              id: "financement",
              name: "Financement"
            },
            {
              id: "nb_pri",
              name: tableData[0][2],
              columns: [{
                name: "Nb de groupes pré-scolaire"
              }],
            },
    
            {
              id: "nb_pri",
              name: tableData[0][3],
              columns: [{
                name: "Nb de groupes primaire"
              }],
            },
            'Statut'
          ],
          data: tableDataa,
          pagination:{
            limit:20,
            enabled:true,
            summary:true
          },
          search: {
            enabled: true
          },
          sort:true,
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
      myGrid.render(document.getElementById('liste_ecoles')); 
    myGrid.forceRender();      
  });