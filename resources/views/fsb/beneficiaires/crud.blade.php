<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_beneficiaire', function(){
            let url = $(this).data('url');
            let nom_reg = $(this).data('nom_reg');
            let nom_comm = $(this).data('nom_comm');
            let nom_vill = $(this).data('nom_vill');
            var monImage = document.getElementById("monImage");
            $.get(url, function(data){
                $('.modal-title').text('Détail du bénéficiaire n°'+data.id);
                $('.show_beneficiaire_modal').modal('show');
                    $('#show_nom_reg').text(nom_reg);
                    $('#show_nom_comm').text(nom_comm);
                    $('#show_nom_vill').text(nom_vill);
                    $('#show_nom').text(data.nom);
                    $('#show_rang').text(data.rang);
                    $('#show_prenom').text(data.prenom);
                    $('#show_sexe').text(data.sexe);
                    $('#show_telephone').text(data.telephone);
                    $('#show_card_number').text(data.card_number);
                    $('#show_type_carte').text(data.type_carte);
                    $('#show_url_photo').text(data.url_photo);
                    $('#show_date_naiss').text(data.date_naiss);
                    $('#show_menage_id').text(data.menage_id);
                    $('#show_type_transfert').text(data.type_transfert);
                    monImage.src = data.url_photo;
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_beneficiaire', function(){
            $.get('/beneficiaires/create', function(res){
                $('.modal-title').text('Nouveau beneficiaire');
                $('.add_beneficiaire_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_beneficiaire_btn').on('submit', function(e){
            e.preventDefault();
            let form = $(this).serialize();
            let url = $(this).attr('action');
            $.ajax({
                type: 'post',
                url: url,
                data: form,
                dataType: 'json',
                success: function(){
                    $('#form')[0].reset();
                    $('.add_beneficiaire_modal').modal('hide');
                    index_beneficiaire();
                }
            })
        });

        $('body').on('click', '#edit_beneficiaire', function(){
            let id = $(this).data('id');
            $.get('/beneficiaires/'+id+'/edit', function(res){
                $('.modal-title').text('Modification du beneficiaire n°'+id);
                $('.edit_beneficiaire_modal').modal('show');
                $('.id').val(id);
                $('.nom_fin').val(res.nom_fin);
                $('.commentaire').val(res.commentaire);
                $('#form')[0].reset();
            })
        });

        $('#edit_beneficiaire_btn').on('submit', function(e){
            e.preventDefault();
            let form = $(this).serialize();
            let url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_beneficiaire_modal').modal('hide');
                $('#form')[0].reset();
                index_beneficiaire();
            })
        });

        $('body').on('click', '#delete_beneficiaire', function(e){
            e.preventDefault();
            let id = $(this).data('id');
                $('.modal-title').text('Suppression du beneficiaire n°'+id);
                $('.delete_beneficiaire_modal').modal('show');
                $('#btn_delete_beneficiaire').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_beneficiaire').click(function(){
            let id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/beneficiaires/'+id
            });
            $('#form')[0].reset();
            $('.delete_beneficiaire_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_beneficiaire(){
        $.get("{{URL::to('beneficiaires')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>