<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_typeouvrage', function(){
            var url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail du type ouvrage n°'+data.id);
                $('.show_typeouvrage_modal').modal('show');
                    $('#show_nom_type').text(data.nom_type);
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_typeouvrage', function(){
            $.get('/typeouvrages/create', function(res){
                $('.modal-title').text('Nouveau type ouvrage');
                $('.add_typeouvrage_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_typeouvrage_btn').on('submit', function(e){
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
                    $('.add_typeouvrage_modal').modal('hide');
                    index_typeouvrage();
                }
            })
        });

        $('body').on('click', '#edit_typeouvrage', function(){
            var id = $(this).data('id');
            $.get('/typeouvrages/'+id+'/edit', function(res){
                $('.modal-title').text('Modification du type ouvrage n°'+id);
                $('.edit_typeouvrage_modal').modal('show');
                $('.id').val(id);
                $('.nom_type').val(res.nom_type);
            })
        });

        $('#edit_typeouvrage_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_typeouvrage_modal').modal('hide');
                $('#form')[0].reset();
                index_typeouvrage();
            })
        });

        $('body').on('click', '#delete_typeouvrage', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression du type ouvrage n°'+id);
                $('.delete_typetypeouvrage_modal').modal('show');
                $('#btn_delete_typeouvrage').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_typeouvrage').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/typeouvrages/'+id
            });
            $('#form')[0].reset();
            $('.delete_typeouvrage_modal').modal('hide');
            location.reload(true);
        });
    });

    function index_typeouvrage(){
        $.get("{{URL::to('typeouvrages')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>