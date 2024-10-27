<script type="text/javascript">

    $(document).ready(function(){
        $('body').on('click', '#delete_permission', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression de la permission n°'+id);
                $('.delete_permission_modal').modal('show');
                $('#btn_delete_permission').val(id);
        });

        $('#btn_delete_permission').click(function(){
            var id = $(this).val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                url: '/permissions/destroy/'+id
            });
            $('.delete_permission_modal').modal('hide');
            location.reload(true);
        });
    });
</script>