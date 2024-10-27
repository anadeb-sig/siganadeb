function detail(id, nom_reg, nom_comm, nom_vill){
    return gridjs.html(
        `<div>
            <a href='javascript:void(0)' id='show_beneficiaire' data-url='beneficiaires/show/`+id+`'
                data-nom_reg='`+nom_reg+`' data-nom_comm='`+nom_comm+`' data-nom_vill='`+nom_vill+`' 'title='Voir le detail'>
                Voir detail du BD
            </a>
        </div>`
    )
}

function rendtableau_beneficiaire(nom_reg, nom_comm, nom_vill, id,projet_id, nom,prenom, rang, telephone, sexe, type_carte, card_number,date_naiss) {
    fetch(`beneficiaires/fetch?nom_reg=${nom_reg}&nom_comm=${nom_comm}&nom_vill=${nom_vill}&id=${id}&projet_id=${projet_id}&nom=${nom}&rang=${rang}&prenom=${prenom}&sexe=${sexe}&telephone=${telephone}&date_naiss=${date_naiss}&type_carte=${type_carte}&card_number=${card_number}`)
    .then(response => response.json())
    .then(data => {
        let tableDataa = data.data.map(item => [
            item.nom_reg,item.menage_id,item.nom,item.prenom ,item.sexe,item.telephone,
            detail(item.id, item.nom_reg, item.nom_comm, item.nom_vill)
        ]);
  
        let grid = new gridjs.Grid({
            columns: ["Région","Id du ménage","Nom", "Prénoms", "Sexe","Téléphone","Action"],
            data: tableDataa,
            pagination: {
                enabled: true,
                summary: true,
                limit:10
            },
            sort: true,
            search: true,
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
        });

        const tableElement = document.getElementById('table_beneficiaire');

        if (tableElement) {
            tableElement.textContent = '';
            grid.render(tableElement);
            grid.forceRender();          
            
            const header = document.querySelector('#table_beneficiaire .gridjs-head');
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
        } else {
            console.error("L'élément avec l'ID 'table_beneficiaire' est introuvable.");
        }
    })
}