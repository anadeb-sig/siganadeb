<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_site', function(){
            var url = $(this).data('url');
            var id = $(this).data('id');
            $.get(url, function(data){
                $('.modal-title').text('Détail du site n°'+id);
                $('.show_site_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_statu').text(data.statu);
                    $('#show_nom_site').text(data.nom_site);
                    $('#show_nom_vill').text(data.nom_vill);                    
                    $('#show_nom_cant').text(data.nom_cant);                    
                    $('#show_nom_comm').text(data.nom_comm);                   
                    $('#show_descrip_site').text(data.descrip_site);                    
                    $('#show_budget').text(data.budget);
                    $('#show_nom_pref').text(data.nom_pref);
                    $('#show_nom_reg').text(data.nom_reg);
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_site', function(){
            $.get('/sites/create', function(res){
                $('.modal-title').text('Nouvel site');
                $('.add_site_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_site_btn').on('submit', function(e){
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
                    $('.add_site_modal').modal('hide');
                    $('#form')[0].reset();
                    index_site();
                }
            })
        });

        $('body').on('click', '#edit_site', function(){
            var id = $(this).data('id');
            $.get('/sites/'+id+'/edit', function(res){
                $('.modal-title').text('Modification du site n°'+id);
                $('.edit_site_modal').modal('show');
                $('.id').val(id);
                $('.nom_site').val(res.nom_site);
                $('.budget').val(res.budget);
                $('.descrip_site').val(res.descrip_site);
            })
        });

        $('#edit_site_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_site_modal').modal('hide');
                $('#form')[0].reset();
                index_site();
            })
        });

        $('body').on('click', '#delete_site', function(e){
            e.preventDefault();
            let id = $(this).data('id');
                $('.modal-title').text('Suppression du site n°'+id);
                $('.delete_site_modal').modal('show');
                $('#btn_delete_site').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_site').click(function(){
            let id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/sites/'+id
            });
            $('#form')[0].reset();
            $('.delete_site_modal').modal('hide');
            location.reload(true);
        });
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

    $('body').on('click', '#telecharger_site', function(){
        $.get('/sites/telecharger', function(res){
            $('.modal-title').text('Téléchargement des sites selon la localisation géographique');
            $('.telecharger_modal').modal('show');
        })
    });

    function index_site(){
        $.get("{{URL::to('sites')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>