<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_commune', function(){
            var url = $(this).data('url');
            var nom_reg = $(this).data('nom_reg');
            var nom_pref = $(this).data('nom_pref');
            $.get(url, function(data){
                $('.modal-title').text('Détail de la commune n°'+data.id);
                $('.show_commune_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_nom_reg').text(nom_reg);
                    $('#show_nom_pref').text(nom_pref);                    
                    $('#show_nom_comm').text(data.nom_comm);
                    $('#show_form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_commune', function(){
            $.get('/communes/create', function(res){
                $('.modal-title').text('Nouvelle commune');
                $('.add_commune_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_commune_btn').on('submit', function(e){
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
                    $('.add_commune_modal').modal('hide');
                    index_commune();
                }
            })
        });

        $('body').on('click', '#edit_commune', function(){
            var id = $(this).data('id');
            $.get('/communes/'+id+'/edit', function(res){
                $('.modal-title').text('Modification de la commune n°'+id);
                $('.edit_commune_modal').modal('show');
                $('.id').val(id);
                $('.prefecture_id').val(res.prefecture_id);
                $('.region_id').val(res.region_id);
                $('.nom_comm').val(res.nom_comm);
                $('#form')[0].reset();
            })
        });

        $('#edit_commune_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_commune_modal').modal('hide');
                $('#form')[0].reset();
                index_commune();
            })
        });

        $('body').on('click', '#delete_commune', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression de la commune n°'+id);
                $('.delete_commune_modal').modal('show');
                $('#btn_delete_commune').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_commune').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/communes/'+id
            });
            $('#form')[0].reset();
            $('.delete_commune_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_commune(){
        $.get("{{URL::to('communes')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>