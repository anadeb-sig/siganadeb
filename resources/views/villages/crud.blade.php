<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_village', function(){
            var url = $(this).data('url');
            var nom_reg = $(this).data('nom_reg');
            var nom_pref = $(this).data('nom_pref');
            var nom_comm = $(this).data('nom_comm');
            var nom_cant = $(this).data('nom_cant');
            $.get(url, function(data){
                $('.modal-title').text('Détail de la village n°'+data.id);
                $('.show_village_modal').modal('show');
                    $('#show_nom_reg').text(nom_reg);
                    $('#show_nom_pref').text(nom_pref);                    
                    $('#show_nom_comm').text(nom_comm);
                    $('#show_nom_cant').text(nom_cant);
                    $('#show_nom_vill').text(data.nom_vill);
                    $('#show_form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_village', function(){
            $.get('/villages/create', function(res){
                $('.modal-title').text('Nouveau village');
                $('.add_village_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_village_btn').on('submit', function(e){
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
                    $('.add_village_modal').modal('hide');
                    index_village();
                }
            })
        });

        $('body').on('click', '#edit_village', function(){
            var id = $(this).data('id');
            $.get('/villages/'+id+'/edit', function(res){
                $('.modal-title').text('Modification du village n°'+id);
                $('.edit_village_modal').modal('show');
                $('.id').val(id);
                $('.prefecture_id').val(res.prefecture_id);
                $('.region_id').val(res.region_id);
                $('.commune_id').val(res.commune_id);
                $('.canton_id').val(res.canton_id);
                $('.nom_vill').val(res.nom_vill);
                $('#form')[0].reset();
            })
        });

        $('#edit_village_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_village_modal').modal('hide');
                $('#form')[0].reset();
                index_village();
            })
        });

        $('body').on('click', '#delete_village', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression du village n°'+id);
                $('.delete_village_modal').modal('show');
                $('#btn_delete_village').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_village').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/villages/'+id
            });
            $('#form')[0].reset();
            $('.delete_village_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_village(){
        $.get("{{URL::to('villages')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>