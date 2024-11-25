<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_estimation', function(){
            var url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail de l\'estimation n°'+data.id);
                $('.show_estimation_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_nom_entrep').text(data.nom_entrep);
                    $('#show_num_id_f').text(data.num_id_f);                    
                    $('#show_addr').text(data.addr);                    
                    $('#show_tel').text(data.tel);                    
                    $('#show_email').text(data.email);
                    $('#show_nom_charge').text(data.nom_charge);
                    $('#show_prenom_charge').text(data.prenom_charge);
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_estimation', function(){
            $.get('/estimations/create', function(res){
                $('.modal-title').text('Nouvelle estimation');
                $('.add_estimation_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_estimation_btn').on('submit', function(e){
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
                    $('.add_estimation_modal').modal('hide');
                    index_estimation();
                }
            })
        });

        $('body').on('click', '#edit_estimation', function(){
            var id = $(this).data('id');
            var type_realisation_id = $(this).data('type_realisation_id');
            var ouvrage_id = $(this).data('ouvrage_id');
            $.get('/estimations/'+id+'/edit', function(res){
                $('.modal-title').text('Modification de l\' estimation n°'+id);
                $('.edit_estimation_modal').modal('show');
                $('.id').val(id);
                $('.design').val(res.design);
                $('.unite').val(res.unite);
                $('.qte').val(res.qte);
                $('.prix_unit').val(res.prix_unit);
                $('.type_realisation_id').val(type_realisation_id);
                $('.ouvrage_id').val(ouvrage_id);
                $('#form')[0].reset();
            })
        });

        $('#edit_estimation_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_estimation_modal').modal('hide');
                $('#form')[0].reset();
                index_estimation();
            })
        });

        $('body').on('click', '#delete_estimation', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression de l\'estimation n°'+id);
                $('.delete_estimation_modal').modal('show');
                $('#btn_delete_estimation').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_estimation').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/estimations/'+id
            });
            $('#form')[0].reset();
            $('.delete_estimation_modal').modal('hide');
            location.reload(true);
        });
    });


    
    $('body').on('click', '#telecharger_estimation', function(){
        $('.modal-title').text('Téléchargement le format csv');
        $('.telecharger_modal').modal('show');
    });

    function index_estimation(){
        $.get("{{URL::to('estimations')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>