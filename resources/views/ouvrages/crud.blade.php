<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_ouvrage', function(){
            var url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail de l\'ouvrage n°'+data.id);
                $('.show_ouvrage_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_nom_ouvrage').text(data.nom_ouvrage);
                    $('#show_nom_vill').text(data.nom_vill);                    
                    $('#show_nom_cant').text(data.nom_cant);                    
                    $('#show_nom_comm').text(data.nom_comm);                    
                    $('#show_projet').text(data.name);                    
                    $('#show_descrip').text(data.descrip);                    
                    $('#show_nom_type').text(data.nom_type);
                    $('#show_nom_fin').text(data.nom_fin);
                    $('#show_nom_pref').text(data.nom_pref);
                    $('#show_nom_reg').text(data.nom_reg);
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_ouvrage', function(){
            $.get('/ouvrages/create', function(res){
                $('.modal-title').text('Nouvel ouvrage');
                $('.add_ouvrage_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_ouvrage_btn').on('submit', function(e){
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
                    $('.add_ouvrage_modal').modal('hide');
                    index_ouvrage();
                }
            })
        });

        $('body').on('click', '#edit_ouvrage', function(){
            var id = $(this).data('id');
            $.get('/ouvrages/'+id+'/edit', function(res){
                $('.modal-title').text('Modification de l\' ouvrage n°'+id);
                $('.edit_ouvrage_modal').modal('show');
                $('.id').val(id);
                $('.nom_ouvrage').val(res.nom_ouvrage);
                $('.typeouvrage_id').val(res.typeouvrage_id);
                $('.nom_type').val(res.nom_type);
                $('.projet_id').val(res.projet_id);
                $('.descrip').val(res.descrip);
                $('.financement_id').val(res.financement_id);
                $('.statu').val(res.statu);
            })
        });

        $('#edit_ouvrage_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_ouvrage_modal').modal('hide');
                $('#form')[0].reset();
                index_ouvrage();
            })
        });

        $('body').on('click', '#delete_ouvrage', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression de l\'ouvrage n°'+id);
                $('.delete_ouvrage_modal').modal('show');
                $('#btn_delete_ouvrage').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_ouvrage').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/ouvrages/'+id
            });
            $('#form')[0].reset();
            $('.delete_ouvrage_modal').modal('hide');
            location.reload(true);
        });
    });

    
    $('body').on('click', '#telecharger_ouvrage', function(){
        $.get('/ouvrages/telecharger', function(res){
            $('.modal-title').text('Téléchargement le format csv selon la localisation géographique');
            $('.telecharger_modal').modal('show');
        })
    });

    $('body').on('click', '#demande_suspension', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            $('#site_id').val(id);
                $('.modal-title').text('Nouvelle demande de suspension');
                $('.add_demande_modal').modal('show');
                $('#form')[0].reset();
        });

    // Ajout de demande de suspension du site
    $('#add_demade_btn').on('submit', function(e){
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
                $('.add_demande_modal').modal('hide');
                $('#form')[0].reset();
                index_site();
            }
        })
    });
    
    function index_ouvrage(){
        $.get("{{URL::to('ouvrages')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>