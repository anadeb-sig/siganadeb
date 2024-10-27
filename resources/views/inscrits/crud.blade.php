<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_inscrit', function(){
            let url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail des inscrits');
                $('.show_inscrit_modal').modal('show');
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
                    $('#show_nbr_gr').text(data.nbr_gr);
                    $('#show_nbr_mam').text(data.nbr_mam);
                    $('#show_nbr_ensg').text(data.nbr_ensg);
                    $('#form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_modal_inscrit', function(){
            $.get('/inscrits/create', function(res){
                $('.modal-title').text('Nouvels inscrits');
                $('.add_inscrit_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_inscrit').on('submit', function(e){
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
                    $('.add_inscrit_modal').modal('hide');
                    index_inscrit();
                }
            })
        });

        $('body').on('click', '#edit_inscrit', function(){
            let id = $(this).data('id');
            
            $.get('/inscrits/'+id+'/edit', function(res){
                $('.modal-title').text('Modification des inscrits');
                $('.edit_inscrit_modal').modal('show');
                $('.id').val(id);
                $('.effec_gar').val(res.effec_gar);
                $('.effec_fil').val(res.effec_fil);
                $('.date_debut').val(res.date_debut);
                $('.date_fin').val(res.date_fin);
                $('.nbr_mam').val(res.nbr_mam);
                $('.nbr_ensg').val(res.nbr_ensg);
                $('.nbr_gr').val(res.nbr_gr);
                $('.classe_id').val(res.classe_id);
                $('.status').val(res.status);
                $('#form')[0].reset();
            })
        });

        $('#edit_inscrit').on('submit', function(e){
            e.preventDefault();
            let form = $(this).serialize();
            let url = $(this).attr('action');
            $.post(url,form, function(data){
                console.log(data);
                $('.edit_inscrit_modal').modal('hide');
                //$('#form')[0].reset();
                //index_inscrit();
            })
        });

        $('body').on('click', '#delete_inscrit', function(e){
            e.preventDefault();
            let id = $(this).data('id');
                $('.modal-title').text('Suppression des inscrits n°'+id);
                $('.delete_inscrit_modal').modal('show');
                $('#btn_delete_inscrit').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_inscrit').click(function(){
            let id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: "inscrits/destroy/"+id
            });
            $('#form')[0].reset();
            $('.delete_inscrit_modal').modal('hide');
            location.reload(true);
        });

        // Partie des effectifs nulls
        $('body').on('click', '#delete_inscrit_zero', function(e){
            e.preventDefault();
            let id = $(this).data('id');
                $('.modal-title').text('Suppression des inscrits n°'+id);
                $('.delete_inscrit_modal_zero').modal('show');
                $('#btn_delete_inscrit_zero').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_inscrit_zero').click(function(){
            let id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: "destroy/"+id
            });
            //console.log(url);
            $('#form')[0].reset();
            $('.delete_inscrit_modal_zero').modal('hide');
            location.reload(true);
        });
    });


    function index_inscrit(){
        $.get("{{URL::to('inscrits')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>