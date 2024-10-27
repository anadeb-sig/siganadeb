import { html } from "/assets/gridjs/gridjs.module.js";

function statut(status){
  if(status == 0){
    return "Inactive"
  }else{
    return "Active"
  }
}

function statut_icons(id, status){
  if (status == 0){
    return "<a href='/users/update/status/"+id+"/1' class='btn btn-success m-1'>"+
          "<i class='fa fa-check'></i>"+
      "</a>"
  }else{
    return "<a href='/users/update/status/"+id+"/0' class='btn btn-danger m-1'>"+
          "<i class='fa fa-ban'></i>"+
      "</a>"
  }
}

new gridjs.Grid({columns:
    ["Nom et prénom(s)", "Email", "Téléphone", "Rôle", "Statut", "Actions"],
    pagination:{
        limit:10,
        enabled:true,
        summary:true
    },
    sort:!0,
    search:!0,
    server: {
        url: "users/fetch",
        then: (data) => data.map((item) =>
            [
                item.last_name+' '+item.first_name,  
                item.email,               
                item.mobile_number,              
                item.name,               
                html(statut(item.status)),
                html(
                  `<div class='dropdown'>
                      <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                      </button>
                      <div class='dropdown-menu dropdown-menu-end' style=''>
                          <a href='users/edit/`+item.id+`' id='edit_classe' data-id='`+item.id+`' class='dropdown-item' title='Edit l\'enseignement'>
                            Editer
                          </a>
                          <a href='javascript:void(0)' id='delete_user' data-id='`+item.id+`'' class='dropdown-item' title='Supprimer l\'enseignement'>
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
}).render(document.getElementById("table_user"));