<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_visite', function(){
            var url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail du visite n°'+data.id);
                $('.show_visite_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_titre').text(data.titre);
                    $('#show_objet').text(data.objet);
                    $('#show_constat').text(data.constat);
                    $('#show_recommandation').text(data.recommandation);
                    $('#show_contact').text(data.contact);
                    $('#show_nom_ecl').text(data.nom_ecl);
                    $('#show_nom_user').text(data.last_name+' '+data.first_name);
                    $('#show_mobile_number').text(data.mobile_number);
                    $('#show_nom_reg').text(data.nom_reg);
                    $('#show_nom_cant').text(data.nom_cant);
                    $('#show_nom_vill').text(data.nom_vill);
                    $('#show_date_visite').text(data.date_visite);
                    $('#show_niveau_exe').text(data.niveau_exe);
                    $('#form')[0].reset();
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_visite', function(){
            $.get('/visites/create', function(res){
                $('.modal-title').text('Nouvelle visite');
                $('.add_visite_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_visite_btn').on('submit', function(e){
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
                    $('.add_visite_modal').modal('hide');
                    index_visite();
                }
            })
        });

        $('body').on('click', '#edit_visite', function(){
            var id = $(this).data('id');
            $.get('/visites/'+id+'/edit', function(res){
                $('.modal-title').text('Modification de l\'école n°'+id);
                $('.edit_visite_modal').modal('show');
                $('.id').val(id);
                $('.contact').val(res.contact);
                $('.objet').val(res.objet);
                $('.constat').val(res.constat);
                $('.titre').val(res.titre);
                $('.date_visite').val(res.date_visite);
                $('.niveau_exe').val(res.niveau_exe);
                $('.recommandation').val(res.recommandation);
                $('#form')[0].reset();
            })
        });

        $('#edit_visite_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_visite_modal').modal('hide');
                $('#form')[0].reset();
                index_visite();
            })
        });

        $('body').on('click', '#delete_visite', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression du visite n°'+id);
                $('.delete_visite_modal').modal('show');
                $('#btn_delete_visite').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_visite').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/visites/'+id
            });
            $('#form')[0].reset();
            $('.delete_visite_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_visite(){
        $.get("{{URL::to('visites')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>