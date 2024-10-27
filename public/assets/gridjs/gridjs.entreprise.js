import { html } from "/assets/gridjs/gridjs.module.js";

// Fonction pour exporter en Excel
function exportToExcel(data) {
  // Création d'un nouveau fichier Excel
  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, "Entreprises");

  // Génération du fichier et téléchargement
  XLSX.writeFile(wb, "entreprises.xlsx");
}

// Fonction pour exporter un fichier CSV vierge
function exportEmptyCSV() {
  const headers = [
    "Nom entreprise", 
    "Nom du charge", 
    "Prenoms du charge",
    "email", 
    "Telephone", 
    "IDF",
    "Adresse"
  ];

  // Convertir les en-têtes en CSV
  const csvContent = headers.join(";") + "\n";

  // Création d'un Blob pour le fichier CSV
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });

  // Créer un lien de téléchargement
  const link = document.createElement("a");
  const url = URL.createObjectURL(blob);
  link.setAttribute("href", url);
  link.setAttribute("download", "format_vierge.csv");

  // Ajouter le lien et déclencher le téléchargement
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

const grid = new gridjs.Grid({
  columns: ["Nom de l'entreprise", "Nom & prénoms du chargé", 'email', "Téléphone", "IDF", "Actions"],
  pagination: {
    limit: 10,
    enabled: true,
    summary: true
  },
  sort: true,
  search: true,
  server: {
    url: "entreprises/fetch",
    then: (data) => data.map((item) => [
      item.nom_entrep,
      item.nom_charge + ' ' + item.prenom_charge,
      item.email,
      item.tel,
      item.num_id_f,
      html(
        `<div class='dropdown'>
            <button class='btn nav-btn' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>
            <div class='dropdown-menu dropdown-menu-end' style=''>
                <a href='javascript:void(0)' id='show_entreprise' data-url='entreprises/show/`+ item.id + `' data-id='` + item.id + `' class='dropdown-item'>
                  Voir détail
                </a>
                <a href='javascript:void(0)' id='edit_entreprise' data-id='` + item.id + `' class='dropdown-item'>
                  Editer
                </a>
                <a href='javascript:void(0)' id='delete_entreprise' data-id='` + item.id + `' class='dropdown-item'>
                  Supprimer
                </a>
            </div>
        </div>`
      )
    ])
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
}).render(document.getElementById("table_entreprise"));

// Ajout de l'écouteur pour l'exportation du fichier CSV vierge
document.getElementById('exportCSV').addEventListener('click', exportEmptyCSV);

// Ajout de l'écouteur pour l'exportation Excel
document.getElementById('excelButton').addEventListener('click', async () => {
  // Récupérer les données de l'API
  const response = await fetch('entreprises/fetch');
  const data = await response.json();

  // Extraire les informations pertinentes
  const excelData = data.map(item => ({
    "IDF": item.num_id_f,
    "Nom de l'entreprise": item.nom_entrep,
    "Nom & prénoms du chargé": item.nom_charge + ' ' + item.prenom_charge,
    "email": item.email,
    "Téléphone": item.tel,
    "Adresse de l'entreprise": item.addr
  }));

  // Appel de la fonction d'exportation
  exportToExcel(excelData);
});
