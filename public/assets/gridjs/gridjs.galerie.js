

function rendtableau_galerie(nom_reg, nom_comm, nom_ouvrage, nom_projet, nom_type, nom_fin, date_demarre_debut, date_demarre_fin,nom_site) {
    
    function generatePhotoHTML(photo, categorie) {
        return `
            <div class="col-md-4">
                <div class="gallery-box card text-center">
                    <div class="gallery-container">
                        <a href="/images/${photo.photo}" class="glightbox" data-gallery="${categorie}" data-title="${photo.nom_ouvrage}" data-description="${photo.recomm}">
                            <img src="/images/${photo.photo}" class="img-fluid" style="width:50em" alt="${photo.nom_ouvrage}">
                        </a>
                    </div>
                    <div class="box-content p-3">
                        <div class="row">
                            <div class="col-xl-10">
                                <h5 style="font-size: 0.7rem; float: left; margin-top: -5px;">${photo.nom_ouvrage}</h5>
                            </div>
                            <div class="col-xl-2">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary" id="dropdownMenuButton" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#!" id="delete_galerie" data-id="${photo.id}">Supprimer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Récupérer les photos et les catégories en parallèle
    Promise.all([
        fetch(`/suivis/galerie/post?nom_reg=${nom_reg}&nom_comm=${nom_comm}&nom_ouvrage=${nom_ouvrage}&nom_projet=${nom_projet}&nom_type=${nom_type}&nom_fin=${nom_fin}&date_demarre_debut=${date_demarre_debut}&date_demarre_fin=${date_demarre_fin}&nom_site=${nom_site}`)
            .then(response => response.json()),
        fetch(`/suivis/galerie/type`)
            .then(response => response.json())
    ])
    .then(([photosData, categories]) => {
        // Initialiser l'objet pour stocker les photos par catégorie
        let photosParCategorie = { 'all': [] };

        // Ajouter les catégories existantes
        categories.forEach(category => {
            let nom_type_sans_espaces = category.nom_type.replace(/ /g, '');
            photosParCategorie[nom_type_sans_espaces] = [];
        });

        // Ajouter les photos dans "All" et dans leur catégorie respective
        photosData.data.forEach(photo => {
            let categorie = photo.nom_type.replace(/ /g, ''); // Enlever les espaces des noms de catégorie
            if (!photosParCategorie['all'].some(p => p.id === photo.id)) {
                photosParCategorie['all'].push(photo); // Ajouter à "All" sans duplication
            }
            if (photosParCategorie[categorie] && !photosParCategorie[categorie].some(p => p.id === photo.id)) {
                photosParCategorie[categorie].push(photo); // Ajouter à la bonne catégorie sans duplication
            }
        });

        // Vider l'élément 'table_galerie' avant d'ajouter du nouveau contenu
        const tableElement = document.getElementById('table_galerie');
        if (tableElement) {
            tableElement.innerHTML = '';  // Vide le contenu précédent

            // Créer les onglets dynamiques
            let tabHeaders = `<div class="card-header">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
                    </li>`;
                    Object.keys(photosParCategorie).forEach(categorie => {
                        if (photosParCategorie[categorie].length > 0 && categorie !== 'all') { // N'affiche que les catégories non vides
                            tabHeaders += `
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="${categorie}-tab" data-bs-toggle="tab" data-bs-target="#${categorie}" type="button" role="tab" aria-controls="${categorie}" aria-selected="false">${categorie}</button>
                                </li>
                            `;
                        }
                    });

            tabHeaders += `
                <li class="nav-item" role="presentation">
                    <a href="javascript:void(0)" class="btn btn-outline-primary" id="add_galerie">Nouvel enregistrement</a>
                </li>
            `;
            tabHeaders += `</ul></div>`;

            // Créer le contenu des onglets pour chaque catégorie non vide
            let tabContent = `<div class="card-body"><div class="tab-content" id="myTabContent">`;

            // Onglet "All"
            if (photosParCategorie['all'].length > 0) {
                tabContent += `<div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="row">`;
                    let photos = photosParCategorie['all'];
                    for (let i = 0; i < photos.length; i += 3) {
                        tabContent += '<div class="row">';
                        for (let j = i; j < i + 3; j++) {
                            if (photos[j]) {
                                tabContent += generatePhotoHTML(photos[j], 'all');
                            }
                        }
                        tabContent += '</div>';
                    }
                    tabContent += '</div>';
                tabContent += '</div>';
            }

            Object.keys(photosParCategorie).forEach(categorie => {
                if (categorie !== 'all') {
                    tabContent += `
                    <div class="tab-pane fade" id="${categorie}" role="tabpanel" aria-labelledby="${categorie}-tab">
                        <div class="row">`;

                    let photos = photosParCategorie[categorie];
                    if (photos.length === 0) {
                        tabContent += '<p class="text-center">Aucune photo disponible pour cette catégorie.</p>';
                    } else {
                        for (let i = 0; i < photos.length; i += 3) {
                            tabContent += '<div class="row">';
                            for (let j = i; j < i + 3; j++) {
                                if (photos[j]) {
                                    tabContent += generatePhotoHTML(photos[j], categorie);
                                }
                            }
                            tabContent += '</div>';
                        }
                    }
                }

                tabContent += '</div></div>';
            });

            if (photosParCategorie['all'].length == 0) {
                tabContent += '<p class="text-center">Aucune photo disponible.</p>';
            }

            tabContent += '</div></div>';

            // Injecter les onglets et le contenu dans le DOM
            tableElement.innerHTML = tabHeaders + tabContent;

            // Initialiser Glightbox après le rendu du tableau
            const lightbox = GLightbox({
                touchNavigation: true,
                loop: true,
                zoomable: true,
            });
        } else {
            console.error("L'élément avec l'ID 'table_galerie' est introuvable.");
        }
    })
    .catch(error => console.error('Erreur lors de la récupération des données :', error));
}





