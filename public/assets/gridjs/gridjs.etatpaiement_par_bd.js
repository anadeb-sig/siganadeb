function rendtableau_paiement_beneficiaire(nom_reg,nom_pref, nom_comm,nom_cant,nom_vill, financement,nom,prenom, telephone,cardNum) {
    fetch(`/fsb_syntheses/fetch_beneficiaire?nom_reg=${nom_reg}&nom_pref=${nom_pref}&nom_comm=${nom_comm}&nom_cant=${nom_cant}&nom_vill=${nom_vill}&financement=${financement}&nom=${nom}&prenom=${prenom}&telephone=${telephone}&cardNum=${cardNum}`)
    .then(response => response.json())
    .then(data => {
        const somme_montantEnv = data.totals.somme_montantEnv || 0;
        const somme_montantRecu = data.totals.somme_montantRecu || 0;
        const somme_frais = data.totals.somme_frais || 0;
        const somme_montant1 = data.totals.somme_montant1 || 0;
        const somme_frais1 = data.totals.somme_frais1 || 0;
        const somme_montant2 = data.totals.somme_montant2 || 0;
        const somme_frais2 = data.totals.somme_frais2 || 0;
        const somme_montant3 = data.totals.somme_montant3 || 0;
        const somme_frais3 = data.totals.somme_frais3 || 0;
        const somme_montant4 = data.totals.somme_montant4 || 0;
        const somme_frais4 = data.totals.somme_frais4 || 0;
        const somme_montant5 = data.totals.somme_montant5 || 0;
        const somme_frais5 = data.totals.somme_frais5 || 0;
        const somme_montant6 = data.totals.somme_montant6 || 0;
        const somme_frais6 = data.totals.somme_frais6 || 0;
        
        const formatter = new Intl.NumberFormat('fr-FR');

        const tableDataa = data.data.map(item => [
            item.reg,
            item.pref,
            item.comm,
            item.cant,
            item.vill,
            item.financement,

            item.nom,
            item.prenom,
            item.sexe,
            item.cardNum,
            item.telephone,
            formatter.format(item.montantEnv),
            formatter.format(item.montantRecu),
            formatter.format(item.frais),
            formatter.format(item.montant1),
            formatter.format(item.frais1),
            formatter.format(item.montant2),
            formatter.format(item.frais2),
            formatter.format(item.montant3),
            formatter.format(item.frais3),
            formatter.format(item.montant4),
            formatter.format(item.frais4),
            formatter.format(item.montant5),
            formatter.format(item.frais5),
            formatter.format(item.montant6),
            formatter.format(item.frais6)
        ]);

        const myGrid = new gridjs.Grid({
            columns: [
                "Région",
                "Préfecture",
                "Commune",
                "Canton",
                "Village",
                "Financement",
                "Nom",
                "Prénom",
                "Sexe",
                "Numéro de la pièce",
                "Téléphone",
                {
                    name: `${formatter.format(somme_montantEnv)}`,
                    columns: [{ name: "Envoyé"}],
                },
                {
                    name: `${formatter.format(somme_montantRecu)}`,
                    columns: [{ name: "Total reçu"}],
                },
                {
                    name: `${formatter.format(somme_frais)}`,
                    columns: [{ name: "Total frais"}],
                },
                {
                    name: `${formatter.format(somme_montant1)}`,
                    columns: [{ name: "Tranche 1"}],
                },
                {
                    name: `${formatter.format(somme_frais1)}`,
                    columns: [{ name: "Frais 1"}],
                },
                {
                    name: `${formatter.format(somme_montant2)}`,
                    columns: [{ name: "Tranche 2"}],
                },
                {
                    name: `${formatter.format(somme_frais2)}`,
                    columns: [{ name: "Frais 2"}],
                },
                {
                    name: `${formatter.format(somme_montant3)}`,
                    columns: [{ name: "Tranche 3"}],
                },
                {
                    name: `${formatter.format(somme_frais3)}`,
                    columns: [{ name: "Frais 3"}],
                },
                {
                    name: `${formatter.format(somme_montant4)}`,
                    columns: [{ name: "Tranche 4"}],
                },
                {
                    name: `${formatter.format(somme_frais4)}`,
                    columns: [{ name: "Frais 4"}],
                },
                {
                    name: `${formatter.format(somme_montant5)}`,
                    columns: [{ name: "Tranche 5"}],
                },
                {
                    name: `${formatter.format(somme_frais5)}`,
                    columns: [{ name: "Frais 5"}],
                },
                {
                    name: `${formatter.format(somme_montant6)}`,
                    columns: [{ name: "Tranche 6"}],
                },
                {
                    name: `${formatter.format(somme_frais6)}`,
                    columns: [{ name: "Frais 6"}],
                },
            ],
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

        
        //console.log(myGrid);

        const tableElement = document.getElementById('table_etat_par_bd');
        if (tableElement) {
            tableElement.textContent = '';
            myGrid.render(tableElement);
            myGrid.forceRender();

            const header = document.querySelector('#table_etat_par_bd .gridjs-head');
            if (header) {
                let button = header.querySelector('button');
                if (!button) {
                    button = document.createElement('button');
                    button.id = 'totalButton';
                    button.classList.add('btn', 'btn-outline-primary');
                    header.appendChild(button);
                }
                button.textContent = `Total : ${formatter.format(data.total)}`;
            } else {
                console.error("L'élément d'en-tête du tableau est introuvable.");
            }
        } else {
            console.error("L'élément avec l'ID 'table_etat_par_bd' est introuvable.");
        }

        let excelButton = document.getElementById('excelButton');
        if (excelButton) {
        // Fonction de génération Excel au clic
        excelButton.addEventListener('click', () => {
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet([
            [
                "Région",
                "Préfecture",
                "Commune",
                "Canton",
                "Village",
                "Financement",
                "Nom",
                "Prénom",
                "Sexe",
                "Numéro de la pièce",
                "Téléphone","Envoyé","Total reçu","Total frais","Tranche 1","Frais 1","Tranche 2","Frais 2","Tranche 3","Frais 3","Tranche 4","Frais 4","Tranche 5","Frais 5","Tranche 6","Frais 6",
            ],  // En-têtes
            ...tableDataa
            ]);

            XLSX.utils.book_append_sheet(wb, ws, 'Etatpaiement_par_BD');

            // Obtenir la date actuelle
            let today = new Date();
            let yyyy = today.getFullYear();
            let mm = String(today.getMonth() + 1).padStart(2, '0'); // Mois de 0 à 11
            let dd = String(today.getDate()).padStart(2, '0'); // Jours

            // Créer le nom du fichier au format aaaa-mm-jj
            let filename = yyyy + '_' + mm + '_' + dd + '_Etatpaiement_par_BD.xlsx';
            XLSX.writeFile(wb, filename);
        });
        }
    });
}



