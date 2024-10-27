<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_region', function(){
            var url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail de la région n°'+data.id);
                $('.show_region_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_created_at').text(data.created_at);
                    $('#show_nom_reg').text(data.nom_reg);
                    $('#show_form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_region', function(){
            $.get('/regions/create', function(res){
                $('.modal-title').text('Nouveau region');
                $('.add_region_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_region_btn').on('submit', function(e){
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
                    $('.add_region_modal').modal('hide');
                    index_region();
                }
            })
        });

        $('body').on('click', '#edit_region', function(){
            var id = $(this).data('id');
            $.get('/regions/'+id+'/edit', function(res){
                $('.modal-title').text('Modification de la région n°'+id);
                $('.edit_region_modal').modal('show');
                $('.id').val(id);
                $('.nom_reg').val(res.nom_reg);
                $('#form')[0].reset();
            })
        });

        $('#edit_region_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_region_modal').modal('hide');
                $('#form')[0].reset();
                index_region();
            })
        });

        $('body').on('click', '#delete_region', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression de la région n°'+id);
                $('.delete_region_modal').modal('show');
                $('#btn_delete_region').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_region').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/regions/'+id
            });
            $('#form')[0].reset();
            $('.delete_region_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_region(){
        $.get("{{URL::to('regions')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>