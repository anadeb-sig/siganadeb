<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_ecole', function(){
            let url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail de la cantine n°'+data.id);
                $('.show_ecole_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_nom_ecl').text(data.nom_ecl);
                    $('#show_nom_vill').text(data.nom_vill);
                    $('#show_nom_fin').text(data.nom_fin);
                    $('#show_nbr_ensg').text(data.nbr_ensg);
                    $('#show_nbr_mamF').text(data.nbr_mamF);
                    $('#show_nbr_mamH').text(data.nbr_mamH);
                    $('#show_nbr_pri').text(data.nbr_pri);
                    $('#show_nbr_pre').text(data.nbr_pre);
                    $('#show_nbr_ensg').text(data.nbr_ensg);
                    $('#show_nom_vill').text(data.nom_vill);
                    $('#show_nom_cant').text(data.nom_cant);
                    $('#show_nom_comm').text(data.nom_comm);
                    $('#show_nom_pref').text(data.nom_pref);
                    $('#show_nom_reg').text(data.nom_reg);
                    $('#show_form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_ecole', function(){
            $.get('/ecoles/create', function(res){
                $('.modal-title').text('Nouvelle cantine');
                $('.add_ecole_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_ecole_btn').on('submit', function(e){
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
                    $('.add_ecole_modal').modal('hide');
                    index_ecole();
                }
            })
        });

        $('body').on('click', '#edit_ecole', function(){
            let id = $(this).data('id');
            $.get('/ecoles/'+id+'/edit', function(res){
                $('.modal-title').text('Modification de la cantine n°'+id);
                $('.edit_ecole_modal').modal('show');
                $('.id').val(id);
                $('.nom_ecl').val(res.nom_ecl);
                $('.nbr_ensg').val(res.nbr_ensg);
                $('.nbr_mamH').val(res.nbr_mamH);
                $('.nbr_mamF').val(res.nbr_mamF);
                $('.nbr_pri').val(res.nbr_pri);
                $('.nbr_pre').val(res.nbr_pre);
                $('.financement_id').val(res.financement_id);
                $('.village_id').val(res.village_id);
                $('#form')[0].reset();
            })
        });

        $('#edit_ecole_btn').on('submit', function(e){
            e.preventDefault();
            let form = $(this).serialize();
            let url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_ecole_modal').modal('hide');
                $('#form')[0].reset();
                index_ecole();
            })
        });

        $('body').on('click', '#delete_ecole', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            $('.modal-title').text('Suppression de la cantine n°'+id);
            $('.delete_ecole_modal').modal('show');
            $('#btn_delete_ecole').val(id);
        });

        $('#btn_delete_ecole').click(function(){
            let id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: 'ecoles/'+id
            });
            $('#form')[0].reset();
            $('.delete_ecole_modal').modal('hide');
            location.reload(true);            
        });
    });


    function index_ecole(){
        $.get("{{URL::to('ecoles')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>