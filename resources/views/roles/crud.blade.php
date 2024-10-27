<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#delete_role', function(e){
            e.preventDefault();
            var id = $(this).data('id');
                $('.modal-title').text('Suppression du role n°'+id);
                $('.delete_role_modal').modal('show');
                $('#btn_delete_role').val(id);
        });        

        $('#btn_delete_role').click(function(){
            var id = $(this).val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                url: '/roles/destroy/'+id
            });
            $('.delete_role_modal').modal('hide');
            location.reload(true);
        });
    });
</script>