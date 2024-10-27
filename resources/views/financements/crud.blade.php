<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_financement', function(){
            let url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail du financement n°'+data.id);
                $('.show_financement_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_commentaire').text(data.commentaire);
                    $('#show_nom_fin').text(data.nom_fin);
                    $('#show_form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_financement', function(){
            $.get('/financements/create', function(res){
                $('.modal-title').text('Nouveau financement');
                $('.add_financement_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_financement_btn').on('submit', function(e){
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
                    $('.add_financement_modal').modal('hide');
                    index_financement();
                }
            })
        });

        $('body').on('click', '#edit_financement', function(){
            let id = $(this).data('id');
            $.get('/financements/'+id+'/edit', function(res){
                $('.modal-title').text('Modification du financement n°'+id);
                $('.edit_financement_modal').modal('show');
                $('.id').val(id);
                $('.nom_fin').val(res.nom_fin);
                $('.commentaire').val(res.commentaire);
                $('#form')[0].reset();
            })
        });

        $('#edit_financement_btn').on('submit', function(e){
            e.preventDefault();
            let form = $(this).serialize();
            let url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_financement_modal').modal('hide');
                $('#form')[0].reset();
                index_financement();
            })
        });

        $('body').on('click', '#delete_financement', function(e){
            e.preventDefault();
            let id = $(this).data('id');
                $('.modal-title').text('Suppression du financement n°'+id);
                $('.delete_financement_modal').modal('show');
                $('#btn_delete_financement').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_financement').click(function(){
            let id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/financements/'+id
            });
            $('#form')[0].reset();
            $('.delete_financement_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_financement(){
        $.get("{{URL::to('financements')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>