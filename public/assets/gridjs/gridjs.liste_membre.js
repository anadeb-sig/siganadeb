import {html } from "/assets/gridjs/gridjs.module.js";

let grid = new gridjs.Grid({
  columns: ["Nom", "Prénom", "Sexe", "Âge", "Lien parental","Téléphone","Actions"],
  sort: true, // Activer le tri
  search:false,
  server: {
    url: `menages/liste_membre/fetch`,
    then: data => data.data.map(membre => [
      membre.nom, membre.prenom, membre.sexe, membre.age,
      html(
        `<div>
            <a  href='javascript:void(0)' data-id-menage='`+membre.id+`' title=''>
              Enlever
            </a>
        </div>`     
      ),
    ]),
  },
  pagination: {
    enabled: true,
    summary: true,
    controls: true,
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
}).render(document.getElementById("table_liste_membre"));

