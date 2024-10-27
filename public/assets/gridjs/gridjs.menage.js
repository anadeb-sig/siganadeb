function detail_membre(id){
    return gridjs.html(
        `<div>
            <a href='javascript:void(0)' data-url='/menages/membres/${id}' data-id='${id}' id='openModalBtn' title='Voir les membres'>
                Voir les membres
            </a>
        </div>`
    )
}

function rendtableau_menage(nom_reg, nom_comm, nom_vill, id, nature_projet, rang, hhead, sexe, phone_member1) {
    fetch(`menages/fetch?nom_reg=${nom_reg}&nom_comm=${nom_comm}&nom_vill=${nom_vill}&id=${id}&nature_projet=${nature_projet}&rang=${rang}&hhead=${hhead}&sexe=${sexe}&phone_member1=${phone_member1}`)
    .then(response => response.json())
    .then(data => {
        let tableDataa = data.data.map(menage => [
            menage.nom_reg, menage.nom_comm, menage.id, menage.rang, menage.hhead, menage.sexe_cm, menage.age_cm,
            detail_membre(menage.id)                
        ]);

        let grid = new gridjs.Grid({
            columns: ["Région", "Commune", "Identifiant", "Rang", "Chef de ménage", "Sexe", "Âge","Action"],
            data: tableDataa,
            sort: true,
            search: true,

            pagination: {
                enabled: true,
                summary: true
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
        });

        const tableElement = document.getElementById('table_menage');

        if (tableElement) {
            tableElement.textContent = '';
            grid.render(tableElement);
            grid.forceRender();          
            
            const header = document.querySelector('#table_menage .gridjs-head');
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
            console.error("L'élément avec l'ID 'table_etatpaiements' est introuvable.");
        }
        
        $(document).ready(function() {
            var membresModal = new bootstrap.Modal(document.getElementById('membres_liste_modal'));
            let currentPage = 1; // Garder la trace de la page actuelle
            let lastPage = false; // Pour vérifier si c'est la dernière page
        
            $('body').on('click', '#openModalBtn', function(e) {
                e.preventDefault();
                let url = $(this).data('url');
                let id = $(this).data('id');
                $('.modal-title').text('Liste des membres du ménage ' + id);
                
                // Afficher le loader avant d'ouvrir la modale
                $('#loader').show();
                
                // Initialiser la modal et charger la première page de résultats
                loadMembres(url, id, 1); // Charger la première page
            });
        
            function loadMembres(url, id, page) {
                fetch(`${url}?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    if (page === 1) {
                        // Vider le tableau avant d'ajouter les éléments (seulement lors du premier chargement)
                        let tableMembres = document.getElementById('table_liste_membre');
                        tableMembres.innerHTML = '';
        
                        // Ajouter les en-têtes du tableau
                        let thead = document.createElement('thead');
                        thead.classList.add('table-striped');
                        let trHead = document.createElement('tr');
        
                        let headers = ['Nom', 'Prénom', 'Sexe', 'Âge', 'Lien de parenté', 'Numéro de téléphone'];
                        headers.forEach(headerText => {
                            let th = document.createElement('th');
                            th.textContent = headerText;
                            trHead.appendChild(th);
                        });
        
                        thead.appendChild(trHead);
                        tableMembres.appendChild(thead);
        
                        // Créer le corps du tableau
                        let tbody = document.createElement('tbody');
                        tbody.setAttribute('id', 'tbody_liste_membre'); // Attribuer un ID pour manipuler le corps du tableau plus tard
                        tableMembres.appendChild(tbody);
                        // Ajouter les classes Bootstrap pour le style
                        tableMembres.classList.add('table', 'table-striped', 'mb-0');
                    }
        
                    // Ajouter les nouvelles lignes au tableau
                    let tbody = document.getElementById('tbody_liste_membre');
                    data.data.forEach(membre => { // Remarque : les données de pagination sont dans data.data
                        let tr = document.createElement('tr');
                        let tdNom = document.createElement('td');
                        tdNom.textContent = membre.nom;
        
                        let tdPrenom = document.createElement('td');
                        tdPrenom.textContent = membre.prenom;
        
                        let tdsexe = document.createElement('td');
                        tdsexe.textContent = membre.sexe;
        
                        let tdage = document.createElement('td');
                        tdage.textContent = membre.age;
        
                        let tdlien_parent = document.createElement('td');
                        tdlien_parent.textContent = membre.lien_parent;
        
                        let tdphone_number1 = document.createElement('td');
                        tdphone_number1.textContent = membre.phone_number1;
        
                        tr.appendChild(tdNom);
                        tr.appendChild(tdPrenom);
                        tr.appendChild(tdsexe);
                        tr.appendChild(tdage);
                        tr.appendChild(tdlien_parent);
                        tr.appendChild(tdphone_number1);
        
                        tbody.appendChild(tr);
                    });
        
                    // Gérer la pagination
                    currentPage = data.current_page;
                    lastPage = data.last_page;
        
                    if (currentPage < lastPage) {
                        // Ajouter un bouton "Charger plus" si ce n'est pas la dernière page
                        if (!document.getElementById('loadMoreBtn')) {
                            let loadMoreBtn = document.createElement('button');
                            loadMoreBtn.id = 'loadMoreBtn';
                            loadMoreBtn.classList.add('btn', 'btn-primary', 'mt-2');
                            loadMoreBtn.textContent = 'Charger plus';
        
                            loadMoreBtn.addEventListener('click', function() {
                                loadMembres(url, id, currentPage + 1); // Charger la page suivante
                            });
        
                            document.getElementById('membres_liste_modal').appendChild(loadMoreBtn);
                        }
                    } else {
                        // Supprimer le bouton "Charger plus" si c'est la dernière page
                        let loadMoreBtn = document.getElementById('loadMoreBtn');
                        if (loadMoreBtn) {
                            loadMoreBtn.remove();
                        }
                    }
        
                    // Masquer le loader une fois le contenu chargé et afficher la modale
                    $('#loader').hide();
                    membresModal.show();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    $('#loader').hide(); // Masquer le loader en cas d'erreur
                });
            }
        });
    })    
}