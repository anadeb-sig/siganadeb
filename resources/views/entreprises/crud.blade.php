<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_entreprise', function(){
            var url = $(this).data('url');
            $.get(url, function(data){
                $('.modal-title').text('Détail de l\'entreprise n°'+data.id);
                $('.show_entreprise_modal').modal('show');
                    $('#show_id').text(data.id);
                    $('#show_nom_entrep').text(data.nom_entrep);
                    $('#show_num_id_f').text(data.num_id_f);                    
                    $('#show_addr').text(data.addr);                    
                    $('#show_tel').text(data.tel);                    
                    $('#show_email').text(data.email);
                    $('#show_nom_charge').text(data.nom_charge);
                    $('#show_prenom_charge').text(data.prenom_charge);
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_entreprise', function(){
            $.get('/entreprises/create', function(res){
                $('.modal-title').text('Nouvelle entreprise');
                $('.add_entreprise_modal').modal('show');
                $('#form')[0].reset();
            })
        });

        $('#add_entreprise_btn').on('submit', function(e){
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
                    $('.add_entreprise_modal').modal('hide');
                    index_entreprise();
                }
            })
        });

        $('body').on('click', '#edit_entreprise', function(){
            var id = $(this).data('id');
            $.get('/entreprises/'+id+'/edit', function(res){
                $('.modal-title').text('Modification de l\' entreprise n°'+id);
                $('.edit_entreprise_modal').modal('show');
                $('.id').val(id);
                $('.nom_entrep').val(res.nom_entrep);
                $('.nom_charge').val(res.nom_charge);
                $('.prenom_charge').val(res.prenom_charge);
                $('.addr').val(res.addr);
                $('.num_id_f').val(res.num_id_f);
                $('.tel').val(res.tel);
                $('.email').val(res.email);
                //$('#form')[0].reset();
            })
        });

        $('#edit_entreprise_btn').on('submit', function(e){
            e.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.post(url,form, function(data){
                $('.edit_entreprise_modal').modal('hide');
                $('#form')[0].reset();
                index_entreprise();
            })
        });

        $('body').on('click', '#delete_entreprise', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression de l\'entreprise n°'+id);
                $('.delete_entreprise_modal').modal('show');
                $('#btn_delete_entreprise').val(id);
                $('#form')[0].reset();
        });

        $('#btn_delete_entreprise').click(function(){
            var id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/entreprises/'+id
            });
            $('#form')[0].reset();
            $('.delete_entreprise_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_entreprise(){
        $.get("{{URL::to('entreprises')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>