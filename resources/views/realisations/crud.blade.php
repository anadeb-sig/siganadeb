<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_realisation', function(){
            $.get('/realisations/create', function(res){
                $('.modal-title').text('Nouvelle realisation');
                $('.add_realisation_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_realisation_btn').on('submit', function(e){
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
                    $('.add_realisation_modal').modal('hide');
                    index_realisation();
                }
            })
        });

        $('body').on('click', '#edit_realisation', function(){
            var id = $(this).data('id');
            var estimation_id = $(this).data('estimation_id');
            var ouvrage_id = $(this).data('ouvrage_id');
            $.get('/realisations/'+id+'/edit', function(res){
                $('.modal-title').text('Modification de l\' realisation n°'+id);
                $('.edit_realisation_modal').modal('show');
                $('.id').val(id);
                $('.date_real').val(res.date_real);
                $('.qte').val(res.qte);
                $('.prix_unit').val(res.prix_unit);
                $('.estimation_id').val(estimation_id);
                $('.ouvrage_id').val(ouvrage_id);
                $('#form')[0].reset();
            })
        });

        $('#edit_realisation_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_realisation_modal').modal('hide');
                $('#form')[0].reset();
                index_realisation();
            })
        });

        $('body').on('click', '#delete_realisation', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression de l\'realisation n°'+id);
                $('.delete_realisation_modal').modal('show');
                $('#btn_delete_realisation').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_realisation').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/realisations/'+id
            });
            $('#form')[0].reset();
            $('.delete_realisation_modal').modal('hide');
            location.reload(true);
        });
    });


    
    $('body').on('click', '#telecharger_realisation', function(){
        $('.modal-title').text('Téléchargement le format csv');
        $('.telecharger_modal').modal('show');
    });

    function index_realisation(){
        $.get("{{URL::to('realisations')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>