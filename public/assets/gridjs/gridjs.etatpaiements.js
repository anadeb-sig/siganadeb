function statuspaie(SommeSucces, id, telephone){
    if (SommeSucces == 0){
        return gridjs.html(
            `<div>
                <span> Non payé </span>
            </div>`
        )
    }else{ 
        return gridjs.html(
            `<div>
                <a href='javascript:void(0)'  data-id='${id}' id='openModalBtn' data-url='/beneficiaires/paiement/`+telephone+`' title='Voir les membres'>
                    Payé 
                </a>
            </div>`
        )
    }
}


function rendtableau_paiement(nom_reg, nom_comm, nom_vill, nom,prenom, telephone, sexe, type_card, card_number,financement,SommeTM,montant,type_transfert) {
    fetch(`/beneficiaires/fetch_etat_paiements?nom_reg=${nom_reg}&nom_comm=${nom_comm}&nom_vill=${nom_vill}&nom=${nom}&prenom=${prenom}&telephone=${telephone}&sexe=${sexe}&type_card=${type_card}&card_number=${card_number}&financement=${financement}&SommeTM=${SommeTM}&montant=${montant}&type_transfert=${type_transfert}`)
    .then(response => response.json())
    .then(data => {
        // Calcul des sommes basées sur toutes les données
        const som_SommeSucces = data.totals.totalSommeSucces || 0;
        const som_SommePending = data.totals.totalSommePending || 0;
        const som_SommeCancel = data.totals.totalSommeCancel || 0;
        const som_SommeTM = data.totals.totalSommeTM || 0;
        const som_SommeMIE = data.totals.totalSommeMIE || 0;

        const tableDataa = data.data.map(item => [
            item.card_number,
            item.nom,
            item.prenom,
            item.sexe,
            item.telephone,
            statuspaie(item.SommeSucces, item.id, item.telephone),
            item.nbr,
            item.nombre_succes,
            item.SommeSucces.toLocaleString('fr-FR'),
            item.SommeTM.toLocaleString('fr-FR'),
            item.SommeMIE.toLocaleString('fr-FR'),
            item.SommePending.toLocaleString('fr-FR'),
            item.nombre_pending,
            item.nombre_Cancel,
            item.SommeCancel.toLocaleString('fr-FR'),
            item.nombre_Fail
        ]);

        const myGrid = new gridjs.Grid({
            columns: [
                "Numéro de carte",
                "Nom",
                "Prénoms",
                "Sexe",
                "Téléphone",
                "Status",
                "Nbr",
                "Nbr succès",
                {
                    id: "som_success",
                    name: Number(som_SommeSucces).toLocaleString(),
                    columns: [{
                        name: 'Som des succès'
                    }],
                },
                {
                    id: "som_successTM",
                    name: Number(som_SommeTM).toLocaleString(),
                    columns: [{
                        name: 'TM'
                    }],
                },
                {
                    id: "som_success",
                    name: Number(som_SommeMIE).toLocaleString(),
                    columns: [{
                        name: 'MIE'
                    }],
                },
                "Nbr pending",
                {
                    id: "som_pending",
                    name: Number(som_SommePending).toLocaleString(),
                    columns: [{
                        name: 'Som pending'
                    }],
                },
                "Nbr cancel",
                {
                    id: "som_cancel",
                    name: Number(som_SommeCancel).toLocaleString(),
                    columns: [{
                        name: 'Som cancel'
                    }],
                },
                "Nbr échoué"
            ],
            data: tableDataa,
            pagination: {
                enabled: true,
                summary: true
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

        const tableElement = document.getElementById('table_etatpaiements');
        if (tableElement) {
            tableElement.textContent = '';
            myGrid.render(tableElement);
            myGrid.forceRender();

            const header = document.querySelector('#table_etatpaiements .gridjs-head');
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
    });



    

    $(document).ready(function() {
        var membresModal = new bootstrap.Modal(document.getElementById('paiement_liste_modal'));
        let currentPage = 1; // Garder la trace de la page actuelle
        let lastPage = false; // Pour vérifier si c'est la dernière page

        $('body').on('click', '#openModalBtn', function(e) {
            e.preventDefault();
            let url = $(this).data('url');
            let id = $(this).data('id');
            $('.modal-title').text('Liste des paiements du ménage ' + id);

            // Initialiser la modal et charger la première page de résultats
            loadMembres(url, id, 1); // Charger la première page
        });

        function loadMembres(url, id, page) {
            fetch(`${url}?page=${page}`)
            .then(response => response.json())
            .then(data => {
                if (page === 1) {
                    // Vider le tableau avant d'ajouter les éléments (seulement lors du premier chargement)
                    let tableMembres = document.getElementById('table_liste_paiement');
                    tableMembres.innerHTML = '';

                    // Ajouter les en-têtes du tableau
                    let thead = document.createElement('thead');
                    thead.classList.add('table-striped');
                    let trHead = document.createElement('tr');

                    let headers = ['Téléphone', 'Type carte', 'Numéro pièce id', 'Opérateur', 'Montant', 'Date paiement', 'Status'];
                    headers.forEach(headerText => {
                        let th = document.createElement('th');
                        th.textContent = headerText;
                        trHead.appendChild(th);
                    });

                    thead.appendChild(trHead);
                    tableMembres.appendChild(thead);

                    // Créer le corps du tableau
                    let tbody = document.createElement('tbody');
                    tbody.setAttribute('id', 'tbody_liste_paiement'); // Attribuer un ID pour manipuler le corps du tableau plus tard
                    tableMembres.appendChild(tbody);
                    // Ajouter les classes Bootstrap pour le style
                    tableMembres.classList.add('table', 'table-striped', 'mb-0');
                }

                // Ajouter les nouvelles lignes au tableau
                let tbody = document.getElementById('tbody_liste_paiement');
                data.data.forEach(membre => { // Remarque : les données de pagination sont dans data.data
                    let tr = document.createElement('tr');
                    let telephone = document.createElement('td');
                    telephone.textContent = membre.telephone;

                    let type_carte = document.createElement('td');
                    type_carte.textContent = membre.type_card;

                    let card_number = document.createElement('td');
                    card_number.textContent = membre.numero_card;

                    let mobile_money = document.createElement('td');
                    mobile_money.textContent = membre.mobile_money;

                    let montant = document.createElement('td');
                    montant.textContent = membre.montant;

                    let date_paiement = document.createElement('td');
                    date_paiement.textContent = membre.date_paiement;

                    let status = document.createElement('td');
                    status.textContent = membre.status;

                    tr.appendChild(telephone);
                    tr.appendChild(type_carte);
                    tr.appendChild(card_number);
                    tr.appendChild(mobile_money);
                    tr.appendChild(montant);
                    tr.appendChild(date_paiement);
                    tr.appendChild(status);

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

                        document.getElementById('paiement_liste_modal').appendChild(loadMoreBtn);
                    }
                } else {
                    // Supprimer le bouton "Charger plus" si c'est la dernière page
                    let loadMoreBtn = document.getElementById('loadMoreBtn');
                    if (loadMoreBtn) {
                        loadMoreBtn.remove();
                    }
                }

                membresModal.show();
            })
            .catch(error => console.error('Erreur:', error));
        }
    });
}