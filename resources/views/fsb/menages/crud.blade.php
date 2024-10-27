<script type="text/javascript">
    // $(document).ready(function() {
    //     $('body').on('click', '#openModalBtn', function(e) {

    //         e.preventDefault();
    //         let url = $(this).data('url');
    //         $.ajax({
    //             url: url,
    //             method: 'GET',
    //             success: function(response) {
    //                 // Vider le conteneur avant de rendre le tableau
    //                 $('#table_liste_membre').empty();
    //                 // Rendre le tableau Grid.js
    //                 new gridjs.Grid({
    //                     columns: ["Nom", "Prénom", "Sexe", "Âge", "Lien parental", "Téléphone", "Actions"],
    //                     sort: true, // Activer le tri
    //                     search: false,
    //                     data: response.map(membre => [
    //                         membre.nom, membre.prenom, membre.sexe, membre.age,membre.lien_parent,membre.phone_number1,
    //                         html(`<div>
    //                                 <a href='javascript:void(0)' data-id-menage='${membre.id}' title=''>
    //                                     Enlever
    //                                 </a>
    //                             </div>`     
    //                         ),
    //                     ]),
    //                 }).render(document.getElementById('table_liste_membre'));

    //                 // Afficher la modal
    //                 $('#membres_liste_modal').css('display', 'block');
    //             }
    //         });
    //     });
    // });


    $('#modalButton').on('click', function() {
        $('#loading-icon').css('display', 'block');
        let url = $(this).data('url');
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                // Vider le conteneur avant de rendre le tableau
                $('#table_liste_membre').empty();
                // Rendre le tableau Grid.js
                new gridjs.Grid({
                    columns: ["Nom", "Prénom", "Sexe", "Âge", "Lien parental", "Téléphone", "Actions"],
                    sort: true, // Activer le tri
                    search: false,
                    data: response.map(membre => [
                        membre.nom, membre.prenom, membre.sexe, membre.age,membre.lien_parent,membre.phone_number1,
                        html(`<div>
                                <a href='javascript:void(0)' data-id-menage='${membre.id}' title=''>
                                    Enlever
                                </a>
                            </div>`     
                        ),
                    ]),
                }).render(document.getElementById('table_liste_membre'));
                $('#loading-icon').hide(); // Masquez le loader
                // Afficher la modal
                $('#membres_liste_modal').css('display', 'block');
            },
            error: function() {
                $('#loading-icon').hide(); // Masquer le loader en cas d'erreur
                alert('Erreur lors du chargement du contenu.');
            }
        });
    });

</script>