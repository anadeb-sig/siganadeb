import { html } from "/assets/gridjs/gridjs.module.js";

function statutt(status){
  if(status == 0){
    return "Inactive"
  }else{
    return "Active"
  }
}

function statut_iconss(id, status){
  if (status == 0){
    return "<a href='/inscrits/update/status/"+id+"/1' class='dropdown-item'>"+
          "Activer"+
      "</a>"
  }else{
    return "<a href='/inscrits/update/status/"+id+"/0' class='dropdown-item'>"+
          "Désactiver"+
      "</a>"
  }
}

new gridjs.Grid({columns:
    ["Région", "Commune", "Cantine", "Ecole","Eff. filles","Eff. garçons", "Année sco.", "Status", "Actions"],
    pagination:{
        limit:10,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "inscrits/fetch",
        then: (data) => data.map((item) =>
            [
                item.nom_reg,
                item.nom_comm,
                item.nom_ecl,
                item.nom_cla,
                item.effec_fil,
                item.effec_gar,               
                item.year1+'-'+item.year2,               
                html(statutt(item.status)),
                html(
                  `<div class='dropdown'>
                      <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <div class='dropdown-menu dropdown-menu-end'>
                          `+statut_iconss(item.id, item.status)+`
                          <a  href='javascript:void(0)' id='show_inscrit' data-url='inscrits/show/`+item.id+`' class='dropdown-item' title='Détail de l\'enseignement'>
                            Voir détail
                          </a>
                          <a href='javascript:void(0)' id='edit_inscrit' data-id='`+item.id+`' class='dropdown-item' title='Edit l\'enseignement'>
                            Editer
                          </a>
                          <a href='javascript:void(0)' id='delete_inscrit' data-id='`+item.id+`' class='dropdown-item' title='Supprimer l\'enseignement'>
                            Supprimer
                          </a>
                      </div>
                  </div>`
                )
            ]
        ),
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
}).render(document.getElementById("table_inscrit"));