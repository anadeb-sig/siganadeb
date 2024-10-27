<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_classe', function(){
            var url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail de l\'école n°'+data.id);
                $('.show_classe_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_nom_cla').text(data.nom_cla);
                    $('#show_effec_gar').text(data.effec_gar);
                    $('#show_effec_fil').text(data.effec_fil);
                    $('#show_nom_ecl').text(data.nom_ecl);                    
                    $('#show_nom_vill').text(data.nom_vill);
                    $('#show_nom_cant').text(data.nom_cant);
                    $('#show_nom_comm').text(data.nom_comm);
                    $('#show_nom_pref').text(data.nom_pref);
                    $('#show_nom_reg').text(data.nom_reg);
                    $('#form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_modal_classe', function(){
            $.get('/classes/create', function(res){
                $('.modal-title').text('Nouvelle école');
                $('.add_classe_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_classe').on('submit', function(e){
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
                    $('.add_classe_modal').modal('hide');
                    index_classe();
                }
            })
        });

        $('body').on('click', '#edit_classe', function(){
            var id = $(this).data('id');
            $.get('/classes/'+id+'/edit', function(res){
                $('.modal-title').text('Modification de l\'école n°'+id);
                $('.edit_classe_modal').modal('show');
                $('.id').val(id);
                $('.effec_gar').val(res.effec_gar);
                $('.effec_fil').val(res.effec_fil);
                $('.nom_cla').val(res.nom_cla);
                $('.ecole_id').val(res.ecole_id);
                $('#form')[0].reset();
            })
        });

        $('#edit_classe').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_classe_modal').modal('hide');
                $('#form')[0].reset();
                index_classe();
            })
        });

        // Partie des effectifs nulls
        $('body').on('click', '#delete_classe_zero', function(e){
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id);
                $('.modal-title').text('Suppression de l\'école n°'+id);
                $('.delete_classe_modal_zero').modal('show');
                $('#btn_delete_classe_zero').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_classe_zero').click(function(){
            var id = $(this).val();
            console.log(id);
            $.ajax({
                type: 'DELETE',
                url: "classes/destroy/"+id
            });
            console.log(url);
            $('#form')[0].reset();
            $('.delete_classe_modal_zero').modal('hide');
            location.reload(true);
        });
    });


    function index_classe(){
        $.get("{{URL::to('classes')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>