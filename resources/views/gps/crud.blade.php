<script type="text/javascript">
    $(document).ready(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '#add_modal_location', function(){
            $.get('/locations/create', function(res){
                $('.modal-title').text('Nouveau référencement');
                $('.add_location_modal').modal('show');
            })
        });

        $('#add_location_btn').on('submit', function(e){
            e.preventDefault();
            let form = $(this).serialize();
            let url = $(this).attr('action');
            $.ajax({
                type: 'post',
                url: url,
                data: form,
                dataType: 'json',
                success: function(){
                    $('.add_location_modal').modal('hide');
                    index_location();
                }
            })
        });

        $('body').on('click', '#delete_location', function(e){
            e.preventDefault();
            let id = $(this).data('id');
                $('.modal-title').text('Suppression du location n°'+id);
                $('.delete_location_modal').modal('show');
                $('#btn_delete_location').val(id);
        });

        $('#btn_delete_location').click(function(){
            let id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/locations/'+id
            });
            $('.delete_location_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_location(){
        $.get("{{URL::to('locations')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>