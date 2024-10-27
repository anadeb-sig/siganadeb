<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_canton', function(){
            var url = $(this).data('url');
            var nom_reg = $(this).data('nom_reg');
            var nom_pref = $(this).data('nom_pref');
            var nom_comm = $(this).data('nom_comm');
            $.get(url, function(data){
                $('.modal-title').text('Détail de la canton n°'+data.id);
                $('.show_canton_modal').modal('show');
                    $('#show_nom_reg').text(nom_reg);
                    $('#show_nom_pref').text(nom_pref);                    
                    $('#show_nom_comm').text(nom_comm);
                    $('#show_nom_cant').text(data.nom_cant);
                    $('#show_form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_canton', function(){
            $.get('/cantons/create', function(res){
                $('.modal-title').text('Nouveau canton');
                $('.add_canton_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_canton_btn').on('submit', function(e){
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
                    $('.add_canton_modal').modal('hide');
                    index_canton();
                }
            })
        });

        $('body').on('click', '#edit_canton', function(){
            var id = $(this).data('id');
            $.get('/cantons/'+id+'/edit', function(res){
                $('.modal-title').text('Modification du canton n°'+id);
                $('.edit_canton_modal').modal('show');
                $('.id').val(id);
                $('.prefecture_id').val(res.prefecture_id);
                $('.region_id').val(res.region_id);
                $('.commune_id').val(res.commune_id);
                $('.nom_cant').val(res.nom_cant);
                $('#form')[0].reset();
            })
        });

        $('#edit_canton_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_canton_modal').modal('hide');
                $('#form')[0].reset();
                index_canton();
            })
        });

        $('body').on('click', '#delete_canton', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression du canton n°'+id);
                $('.delete_canton_modal').modal('show');
                $('#btn_delete_canton').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_canton').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/cantons/'+id
            });
            $('#form')[0].reset();
            $('.delete_canton_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_canton(){
        $.get("{{URL::to('cantons')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>