<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#delete_user', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression l\'utilisateur n°'+id);
                $('.delete_user_modal').modal('show');
                $('#btn_delete_user').val(id);
        });

        $('#btn_delete_user').click(function(){
            var id = $(this).val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                url: '/users/destroy/'+id
            });
            $('.delete_user_modal').modal('hide');
            location.reload(true);
        });
    });
</script>