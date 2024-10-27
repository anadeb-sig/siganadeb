<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_prefecture', function(){
            var url = $(this).data('url');
            var nom_reg = $(this).data('nom_reg');
            $.get(url, function(data){
                $('.modal-title').text('Détail de la préfecture n°'+data.id);
                $('.show_prefecture_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_nom_reg').text(nom_reg);
                    $('#show_nom_pref').text(data.nom_pref);
                    $('#show_form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_prefecture', function(){
            $.get('/prefectures/create', function(res){
                $('.modal-title').text('Nouvelle prefecture');
                $('.add_prefecture_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_prefecture_btn').on('submit', function(e){
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
                    $('.add_prefecture_modal').modal('hide');
                    index_prefecture();
                }
            })
        });

        $('body').on('click', '#edit_prefecture', function(){
            var id = $(this).data('id');
            $.get('/prefectures/'+id+'/edit', function(res){
                $('.modal-title').text('Modification de la préfecture n°'+id);
                $('.edit_prefecture_modal').modal('show');
                $('.id').val(id);
                $('.nom_pref').val(res.nom_pref);
                $('.region_id').val(res.region_id);
                $('#form')[0].reset();
            })
        });

        $('#edit_prefecture_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_prefecture_modal').modal('hide');
                $('#form')[0].reset();
                index_prefecture();
            })
        });

        $('body').on('click', '#delete_prefecture', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression du prefecture n°'+id);
                $('.delete_prefecture_modal').modal('show');
                $('#btn_delete_prefecture').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_prefecture').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/prefectures/'+id
            });
            $('#form')[0].reset();
            $('.delete_prefecture_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_prefecture(){
        $.get("{{URL::to('prefectures')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>