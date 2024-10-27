<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_menu', function(){
            var url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail du menu n°'+data.id);
                $('.show_menu_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_nom_menu').text(data.descri);
                    $('#show_cout_unt').text(data.cout_unt);
                    $('#show_form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_menu', function(){
            $.get('/menus/create', function(res){
                $('.modal-title').text('Nouveau menu');
                $('.add_menu_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_menu_btn').on('submit', function(e){
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
                    $('.add_menu_modal').modal('hide');
                    index_menu();
                }
            })
        });

        $('body').on('click', '#edit_menu', function(){
            var id = $(this).data('id');
            $.get('/menus/'+id+'/edit', function(res){
                $('.modal-title').text('Modification du menu n°'+id);
                $('.edit_menu_modal').modal('show');
                $('.id').val(id);
                $('.nom_menu').val(res.descri);
                $('.cout_unt').val(res.cout_unt);
                $('#form')[0].reset();
            })
        });

        $('#edit_menu_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_menu_modal').modal('hide');
                $('#form')[0].reset();
                index_menu();
            })
        });

        $('body').on('click', '#delete_menu', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression du menu n°'+id);
                $('.delete_menu_modal').modal('show');
                $('#btn_delete_menu').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_menu').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/menus/'+id
            });
            $('#form')[0].reset();
            $('.delete_menu_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_menu(){
        $.get("{{URL::to('menus')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>