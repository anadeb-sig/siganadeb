<!-- gridjs js-->
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction par défaut sans paramètres
            loaddatauser_ouvrage();
            // Gestionnaire de clic sur le bouton "Afficher avec paramètres"
            document.getElementById('btnOuvrage').addEventListener('click', function() {
                // Appeler la fonction avec des paramètres
                let nom_reg = document.getElementById('nom_reg').value;
                let nom_ouvrage = document.getElementById('nom_ouv').value;
                let nom_comm = document.getElementById('nom_comm').value;
                let nom_projet = document.getElementById('projetOuvrage').value;
                let nom_type = document.getElementById('nom_type').value;
                let nom_fin = document.getElementById('nom_fin').value;
                let statu = document.getElementById('statu').value;

                console.log(nom_type);
                rendtableau_ouvrage(nom_reg, nom_comm, nom_ouvrage, nom_projet, nom_type, nom_fin,statu);
                
            });
        });

        function loaddatauser_ouvrage(){
            let nom_reg = "";
            let nom_comm = "";
            let nom_ouvrage = "";
            let nom_projet = "";
            let nom_fin = "";
            let nom_type = "";
            let statu = "";
            rendtableau_ouvrage(nom_reg, nom_comm, nom_ouvrage, nom_projet, nom_type, nom_fin,statu);
        }   
        
        $(document).ready(function(){

            $(document).on('click', '.csvButton', function(e){
                e.preventDefault();

                let selectElement = document.getElementById("region_id");
                // Récupérer l'option sélectionnée
                let selectedOption = selectElement.options[selectElement.selectedIndex];
                // Récupérer la valeur de l'attribut
                let region_id = selectedOption.value;
                // Récupérer la commune

                // Récupérer l'élément select
                let selectCommune = document.getElementById("commune_edit_site");
                // Récupérer l'option sélectionnée
                let optionCommune = selectCommune.options[selectCommune.selectedIndex];
                // Récupérer la valeur de l'attribut data-nom
                let commune_id = optionCommune.value;

                // Construire l'URL avec les paramètres GET
                let url = `ouvrages/format_csv?region_id=${region_id}&commune_id=${commune_id}`;

            

                // Créer un lien pour déclencher le téléchargement
                const a = document.createElement('a');
                a.href = url;
                a.download = 'format_csv_site.csv';  // Nom du fichier CSV
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        });
    </script>