<script type="text/javascript">
        $(document).ready(function(){
            $('#submitData').on('click', function() {
                let jsonArray = $('#phpArray').val();
                let dataArray = JSON.parse(jsonArray);

                $.ajax({
                    type: 'post',
                    url: '{{ route('temps.charger') }}',
                    data: {
                        _token: '{{ csrf_token() }}',  // CSRF token for security
                        data: dataArray
                    },
                    dataType: 'json',
                    success: function(response){
                        console.log(response);
                        document.getElementById('message').style.display = 'block';
                        $('#message').text(response.message).css('color', 'green');
                        setTimeout(function() {
                                window.location.href = response.redirect_url;
                        }, 5000);  // Redirection après 2 secondes
                    },
                    error: function(xhr, status, error) {
                        $('#errors').text(response.message).css('color', 'red');
                        window.location.href = response.redirect_url;
                    }
                });
            });
        });

        function index_temp(){
            $.get("{{URL::to('repas')}}", function(data){
                $('.gridjs-table').empty().html(data);
            })
        }


        document.addEventListener('DOMContentLoaded', function() {
            // Fonction pour vérifier la présence de .blinking-row
            function checkBlinkingRows() {
                const blinkingRows = document.querySelectorAll('.blinking-row');
                const validerButton = document.getElementById('submitData');

                if (blinkingRows.length > 0) {
                    validerButton.classList.add('disabled');
                    validerButton.disabled = true;
                } else {
                    validerButton.classList.remove('disabled');
                    validerButton.disabled = false;
                }
            }

            // Appeler la fonction au chargement de la page
            checkBlinkingRows();

            // Optionnel : Si le contenu de la table change dynamiquement, vous pouvez surveiller les changements
            const observer = new MutationObserver(checkBlinkingRows);
            observer.observe(document.getElementById('dataTable'), { childList: true, subtree: true });

            // Pour désactiver le bouton manuellement à tout moment (par exemple, après une action utilisateur)
            document.getElementById('valider').addEventListener('click', function() {
                checkBlinkingRows();
            });
        });
    </script>