<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_contrat', function(){
            var url = $(this).data('url');
            var date_debut = $(this).data('date_debut');
            var date_fin = $(this).data('date_fin');
            $.get(url, function(data){
                $('.modal-title').text('Détail du contrat  '+data.code);
                $('.show_contrat_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_date_debut').text(data.date_debut);
                    $('#show_date_fin').text(data.date_fin);
                    $('#show_email').text(data.email);
                    $('#show_tel').text(data.tel);
                    $('#show_nom_entrep').text(data.nom_entrep);
                    $('#show_nom_charge').text(data.nom_charge);
                    $('#show_prenom_charge').text(data.prenom_charge);
                    $('#show_code').text(data.code);
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_contrat', function(){
            $.get('/contrats/create', function(res){
                $('.modal-title').text('Nouveau contrat');
                $('.add_contrat_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_contrat_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                type: 'post',
                url: url,
                data: form,
                dataType: 'json',
                success: function(){
                    $('#form')[0].reset();
                    $('.add_contrat_modal').modal('hide');
                    index_contrat();
                    $('#form')[0].reset();
                }
            })
        });

        $('body').on('click', '#edit_contrat', function(){
            var id = $(this).data('id');
            $.get('/contrats/'+id+'/edit', function(res){
                $('.modal-title').text('Modification du contrat  '+res.code);
                $('.edit_contrat_modal').modal('show');
                $('.id').val(id);
                $('.code').val(res.code);
                $('.date_debut').val(res.date_debut);
                $('.iid').val(res.iid);
                $('.date_sign').val(res.date_sign);
                $('.entier_edit').val(((new Date(res.date_fin) - new Date(res.date_debut))/(1000 * 60 * 60 * 24)));
                $('.ouvrage_id').val(res.ouvrage_id);
                $('.entreprise_id').val(res.entreprise_id);
                $('#form')[0].reset();
            })
        });

        $('#edit_contrat_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_contrat_modal').modal('hide');
                $('#form')[0].reset();
                index_contrat();
            })
        });

        $('body').on('click', '#delete_contrat', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression du contrat n°'+id);
                $('.delete_contrat_modal').modal('show');
                $('#btn_delete_contrat').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_contrat').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/contrats/'+id
            });
            $('#form')[0].reset();
            $('.delete_contrat_modal').modal('hide');
            location.reload(true);
        });
    });


    $('body').on('click', '#demande_jours', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            let date_fin = $(this).data('date_fin');
            $('#contrat_id').val(id);
            $('#date_fin').val(date_fin);
            $('.modal-title').text('Demande de jours de plus sur le contrat');
            $('.add_demande_jour_modal').modal('show');
            $('#form')[0].reset();
        });

    // Ajout de demande de suspension du site
    $('#add_demadeJour_btn').on('submit', function(e){
        e.preventDefault();
        var form = $(this).serialize();
        var url = $(this).attr('action');
        $.ajax({
            type: 'post',
            url: url,
            data: form,
            dataType: 'json',
            success: function(){
                $('.add_demande_jour_modal').modal('hide');
                $('#form')[0].reset();
                index_contrat();
            }
        })
    });


    function index_contrat(){
        $.get("{{URL::to('contrats')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>