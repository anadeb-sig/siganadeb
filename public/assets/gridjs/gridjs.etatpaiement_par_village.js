function rendtableau_paiement_village(nom_reg, nom_pref, nom_comm, nom_cant, nom_vill, financement,projet_id) {
    fetch(`/fsb_syntheses/fetch?nom_reg=${nom_reg}&nom_pref=${nom_pref}&nom_comm=${nom_comm}&nom_cant=${nom_cant}&nom_vill=${nom_vill}&financement=${financement}&projet_id=${projet_id}`)
    .then(response => response.json())
    .then(data => {

        //console.log(data);
        const som_nbr1 = data.totals.somme_nbr1 || 0;
        const som_effectFem1 = data.totals.somme_effectFem1 || 0;
        const som_effectHom1 = data.totals.somme_effectHom1 || 0;
        const som_ressources1 = data.totals.somme_ressources1 || 0;
        const som_totalFrais1 = data.totals.somme_totalFrais1 || 0;
        const som_fraisFlooz1 = data.totals.somme_fraisFlooz1 || 0;
        const som_fraisTmoney1 = data.totals.somme_fraisTmoney1 || 0;
        const som_effectifPayeFlooz1 = data.totals.somme_effectifPayeFlooz1 || 0;
        const som_effectifPayeTmoney1 = data.totals.somme_effectifPayeTmoney1 || 0;

        const som_nbr2 = data.totals.somme_nbr2 || 0;
        const som_effectFem2 = data.totals.somme_effectFem2 || 0;
        const som_effectHom2 = data.totals.somme_effectHom2 || 0;
        const som_ressources2 = data.totals.somme_ressources2 || 0;
        const som_totalFrais2 = data.totals.somme_totalFrais2 || 0;
        const som_fraisFlooz2 = data.totals.somme_fraisFlooz2 || 0;
        const som_fraisTmoney2 = data.totals.somme_fraisTmoney2 || 0;
        const som_effectifPayeFlooz2 = data.totals.somme_effectifPayeFlooz2 || 0;
        const som_effectifPayeTmoney2 = data.totals.somme_effectifPayeTmoney2 || 0;
        
        const som_nbr3 = data.totals.somme_nbr3 || 0;
        const som_effectFem3 = data.totals.somme_effectFem3 || 0;
        const som_effectHom3 = data.totals.somme_effectHom3 || 0;
        const som_ressources3 = data.totals.somme_ressources3 || 0;
        const som_totalFrais3 = data.totals.somme_totalFrais3 || 0;
        const som_fraisFlooz3 = data.totals.somme_fraisFlooz3 || 0;
        const som_fraisTmoney3 = data.totals.somme_fraisTmoney3 || 0;
        const som_effectifPayeFlooz3 = data.totals.somme_effectifPayeFlooz3 || 0;
        const som_effectifPayeTmoney3 = data.totals.somme_effectifPayeTmoney3 || 0;
        
        const som_nbr4 = data.totals.somme_nbr4 || 0;
        const som_effectFem4 = data.totals.somme_effectFem4 || 0;
        const som_effectHom4 = data.totals.somme_effectHom4 || 0;
        const som_ressources4 = data.totals.somme_ressources4 || 0;
        const som_totalFrais4 = data.totals.somme_totalFrais4 || 0;
        const som_fraisFlooz4 = data.totals.somme_fraisFlooz4 || 0;
        const som_fraisTmoney4 = data.totals.somme_fraisTmoney4 || 0;
        const som_effectifPayeFlooz4 = data.totals.somme_effectifPayeFlooz4 || 0;
        const som_effectifPayeTmoney4 = data.totals.somme_effectifPayeTmoney4 || 0;
        
        const som_nbr5 = data.totals.somme_nbr5 || 0;
        const som_effectFem5 = data.totals.somme_effectFem5 || 0;
        const som_effectHom5 = data.totals.somme_effectHom5 || 0;
        const som_ressources5 = data.totals.somme_ressources5 || 0;
        const som_totalFrais5 = data.totals.somme_totalFrais5 || 0;
        const som_fraisFlooz5 = data.totals.somme_fraisFlooz5 || 0;
        const som_fraisTmoney5 = data.totals.somme_fraisTmoney5 || 0;
        const som_effectifPayeFlooz5 = data.totals.somme_effectifPayeFlooz5 || 0;
        const som_effectifPayeTmoney5 = data.totals.somme_effectifPayeTmoney5 || 0;
        
        const som_nbr6 = data.totals.somme_nbr6 || 0;
        const som_effectFem6 = data.totals.somme_effectFem6 || 0;
        const som_effectHom6 = data.totals.somme_effectHom6 || 0;
        const som_ressources6 = data.totals.somme_ressources6 || 0;
        const som_totalFrais6 = data.totals.somme_totalFrais6 || 0;
        const som_fraisFlooz6 = data.totals.somme_fraisFlooz6 || 0;
        const som_fraisTmoney6 = data.totals.somme_fraisTmoney6 || 0;
        const som_effectifPayeFlooz6 = data.totals.somme_effectifPayeFlooz6 || 0;
        const som_effectifPayeTmoney6 = data.totals.somme_effectifPayeTmoney6 || 0;

        const tableDataa = data.data.map(item => [
            item.reg,
            item.pref,
            item.comm,
            item.cant,
            item.vill,
            item.financement,

            item.nbr1,
            item.effectFem1,
            item.effectHom1,
            item.date1,
            item.datePrevu1,
            item.ressources1,
            item.totalFrais1,
            item.fraisFlooz1,
            item.fraisTmoney1,
            item.effectifPayeFlooz1,
            item.effectifPayeTmoney1,
            item.comment1,

            item.nbr2,
            item.effectFem2,
            item.effectHom2,
            item.date2,
            item.datePrevu2,
            item.ressources2,
            item.totalFrais2,
            item.fraisFlooz2,
            item.fraisTmoney2,
            item.effectifPayeFlooz2,
            item.effectifPayeTmoney2,
            item.comment2,

            item.nbr3,
            item.effectFem3,
            item.effectHom3,
            item.date3,
            item.datePrevu3,
            item.ressources3,
            item.totalFrais3,
            item.fraisFlooz3,
            item.fraisTmoney3,
            item.effectifPayeFlooz3,
            item.effectifPayeTmoney3,
            item.comment3,

            item.nbr4,
            item.effectFem4,
            item.effectHom4,
            item.date4,
            item.datePrevu4,
            item.ressources4,
            item.totalFrais4,
            item.fraisFlooz4,
            item.fraisTmoney4,
            item.effectifPayeFlooz4,
            item.effectifPayeTmoney4,
            item.comment4,

            item.nbr5,
            item.effectFem5,
            item.effectHom5,
            item.date5,
            item.datePrevu5,
            item.ressources5,
            item.totalFrais5,
            item.fraisFlooz5,
            item.fraisTmoney5,
            item.effectifPayeFlooz5,
            item.effectifPayeTmoney5,
            item.comment5,

            item.nbr6,
            item.effectFem6,
            item.effectHom6,
            item.date6,
            item.datePrevu6,
            item.ressources6,
            item.totalFrais6,
            item.fraisFlooz6,
            item.fraisTmoney6,
            item.effectifPayeFlooz6,
            item.effectifPayeTmoney6,
            item.comment6,
        ]);

        const myGrid = new gridjs.Grid({
            columns: [
                "Région",
                "Préfecture",
                "Commune",
                "Canton",
                "Village",
                "Financement",
                {
                    columns: [{ name: `Total: ${som_nbr1.toLocaleString('fr-FR')}`}],
                    name: "Nombre de bénéficiaires payés Tranche 1",
                },
                {
                    columns: [{ name: `Total: ${som_effectFem1.toLocaleString('fr-FR')}`}],
                    name: "Effectif Femmes payées Tranche 1",
                },
                {
                    columns: [{ name: `Total: ${som_effectHom1.toLocaleString('fr-FR')}`}],
                    name: "Effectif Hommes payés Tranche 1",
                },
                "Date de paiement Tranche 1",
                "Date prévue paiement Tranche 1",
                {
                    columns: [{ name: `Total: ${som_ressources1.toLocaleString('fr-FR')}`}],
                    name: "Ressources transférées aux bénéficiaires Tranche 1",
                },
                {
                    columns: [{ name: `Total: ${som_totalFrais1.toLocaleString('fr-FR')}`}],
                    name: "Total frais de retrait Tranche 1",
                },
                {
                    columns: [{ name: `Total: ${som_fraisFlooz1.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Flooz Tranche 1",
                },
                {
                    columns: [{ name: `Total: ${som_fraisTmoney1.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Tmoney Tranche 1",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeFlooz1.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Flooz Tranche 1",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeTmoney1.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Tmoney Tranche 1",
                },
                "Commentaires 1",
                {
                    columns: [{ name: `Total: ${som_nbr2.toLocaleString('fr-FR')}`}],
                    name: "Nombre de bénéficiaires payés Tranche 2",
                },
                {
                    columns: [{ name: `Total: ${som_effectFem2.toLocaleString('fr-FR')}`}],
                    name: "Effectif Femmes payées Tranche 2",
                },
                {
                    columns: [{ name: `Total: ${som_effectHom2.toLocaleString('fr-FR')}`}],
                    name: "Effectif Hommes payés Tranche 2",
                },
                "Date de paiement Tranche 2",
                "Date prévue paiement Tranche 2",
                {
                    columns: [{ name: `Total: ${som_ressources2.toLocaleString('fr-FR')}`}],
                    name: "Ressources transférées aux bénéficiaires Tranche 2",
                },
                {
                    columns: [{ name: `Total: ${som_totalFrais2.toLocaleString('fr-FR')}`}],
                    name: "Total frais de retrait Tranche 2",
                },
                {
                    columns: [{ name: `Total: ${som_fraisFlooz2.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Flooz Tranche 2",
                },
                {
                    columns: [{ name: `Total: ${som_fraisTmoney2.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Tmoney Tranche 2",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeFlooz2.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Flooz Tranche 2",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeTmoney2.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Tmoney Tranche 2",
                },
                "Commentaires 2",
                {
                    columns: [{ name: `Total: ${som_nbr3.toLocaleString('fr-FR')}`}],
                    name: "Nombre de bénéficiaires payés Tranche 3",
                },
                {
                    columns: [{ name: `Total: ${som_effectFem3.toLocaleString('fr-FR')}`}],
                    name: "Effectif Femmes payées Tranche 3",
                },
                {
                    columns: [{ name: `Total: ${som_effectHom3.toLocaleString('fr-FR')}`}],
                    name: "Effectif Hommes payés Tranche 3",
                },
                "Date de paiement Tranche 3",
                "Date prévue paiement Tranche 3",
                {
                    columns: [{ name: `Total: ${som_ressources3.toLocaleString('fr-FR')}`}],
                    name: "Ressources transférées aux bénéficiaires Tranche 3",
                },
                {
                    columns: [{ name: `Total: ${som_totalFrais3.toLocaleString('fr-FR')}`}],
                    name: "Total frais de retrait Tranche 3",
                },
                {
                    columns: [{ name: `Total: ${som_fraisFlooz3.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Flooz Tranche 3",
                },
                {
                    columns: [{ name: `Total: ${som_fraisTmoney3.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Tmoney Tranche 3",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeFlooz3.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Flooz Tranche 3",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeTmoney3.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Tmoney Tranche 3",
                },
                "Commentaires 3",
                {
                    columns: [{ name: `Total: ${som_nbr4.toLocaleString('fr-FR')}`}],
                    name: "Nombre de bénéficiaires payés Tranche 4",
                },
                {
                    columns: [{ name: `Total: ${som_effectFem4.toLocaleString('fr-FR')}`}],
                    name: "Effectif Femmes payées Tranche 4",
                },
                {
                    columns: [{ name: `Total: ${som_effectHom4.toLocaleString('fr-FR')}`}],
                    name: "Effectif Hommes payés Tranche 4",
                },
                "Date de paiement Tranche 4",
                "Date prévue paiement Tranche 4",
                {
                    columns: [{ name: `Total: ${som_ressources4.toLocaleString('fr-FR')}`}],
                    name: "Ressources transférées aux bénéficiaires Tranche 4",
                },
                {
                    columns: [{ name: `Total: ${som_totalFrais4.toLocaleString('fr-FR')}`}],
                    name: "Total frais de retrait Tranche 4",
                },
                {
                    columns: [{ name: `Total: ${som_fraisFlooz4.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Flooz Tranche 4",
                },
                {
                    columns: [{ name: `Total: ${som_fraisTmoney4.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Tmoney Tranche 4",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeFlooz4.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Flooz Tranche 4",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeTmoney4.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Tmoney Tranche 4",
                },
                "Commentaires 4",
                {
                    columns: [{ name: `Total: ${som_nbr5.toLocaleString('fr-FR')}`}],
                    name: "Nombre de bénéficiaires payés Tranche 5",
                },
                {
                    columns: [{ name: `Total: ${som_effectFem5.toLocaleString('fr-FR')}`}],
                    name: "Effectif Femmes payées Tranche 5",
                },
                {
                    columns: [{ name: `Total: ${som_effectHom5.toLocaleString('fr-FR')}`}],
                    name: "Effectif Hommes payés Tranche 5",
                },
                "Date de paiement Tranche 5",
                "Date prévue paiement Tranche 5",
                {
                    columns: [{ name: `Total: ${som_ressources5.toLocaleString('fr-FR')}`}],
                    name: "Ressources transférées aux bénéficiaires Tranche 5",
                },
                {
                    columns: [{ name: `Total: ${som_totalFrais5.toLocaleString('fr-FR')}`}],
                    name: "Total frais de retrait Tranche 5",
                },
                {
                    columns: [{ name: `Total: ${som_fraisFlooz5.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Flooz Tranche 5",
                },
                {
                    columns: [{ name: `Total: ${som_fraisTmoney5.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Tmoney Tranche 5",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeFlooz5.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Flooz Tranche 5",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeTmoney5.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Tmoney Tranche 5",
                },
                "Commentaires 5",
                {
                    columns: [{ name: `Total: ${som_nbr6.toLocaleString('fr-FR')}`}],
                    name: "Nombre de bénéficiaires payés Tranche 6",
                },
                {
                    columns: [{ name: `Total: ${som_effectFem6.toLocaleString('fr-FR')}`}],
                    name: "Effectif Femmes payées Tranche 6",
                },
                {
                    columns: [{ name: `Total: ${som_effectHom6.toLocaleString('fr-FR')}`}],
                    name: "Effectif Hommes payés Tranche 6",
                },
                "Date de paiement Tranche 6",
                "Date prévue paiement Tranche 6",
                {
                    columns: [{ name: `Total: ${som_ressources6.toLocaleString('fr-FR')}`}],
                    name: "Ressources transférées aux bénéficiaires Tranche 6",
                },
                {
                    columns: [{ name: `Total: ${som_totalFrais6.toLocaleString('fr-FR')}`}],
                    name: "Total frais de retrait Tranche 6",
                },
                {
                    columns: [{ name: `Total: ${som_fraisFlooz6.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Flooz Tranche 6",
                },
                {
                    columns: [{ name: `Total: ${som_fraisTmoney6.toLocaleString('fr-FR')}`}],
                    name: "Frais de retrait Tmoney Tranche 6",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeFlooz6.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Flooz Tranche 6",
                },
                {
                    columns: [{ name: `Total: ${som_effectifPayeTmoney6.toLocaleString('fr-FR')}`}],
                    name: "Effectif payé par Tmoney Tranche 6",
                },
                "Commentaires 6",
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

        const tableElement = document.getElementById('table_etatpaiement_par_village');
        if (tableElement) {
            tableElement.textContent = '';
            myGrid.render(tableElement);
            myGrid.forceRender();

            const header = document.querySelector('#table_etatpaiement_par_village .gridjs-head');
            if (header) {
                let button = header.querySelector('button');
                if (!button) {
                    button = document.createElement('button');
                    button.id = 'totalButton';
                    button.classList.add('btn', 'btn-outline-primary');
                    header.appendChild(button);
                }
                button.textContent = `Total : ${som_nbr1.toLocaleString('fr-FR')}`;
            } else {
                console.error("L'élément d'en-tête du tableau est introuvable.");
            }
        } else {
            console.error("L'élément avec l'ID 'table_etatpaiements' est introuvable.");
        }
    });
}



