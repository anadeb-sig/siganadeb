<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#show_repas', function(){
            let url = $(this).data('url');
            let id = $(this).data('id');
            $.get(url, function(data){
                $('.modal-title').text('Détail du repas n°'+id);
                $('.show_repas_modal').modal('show');
                $('#show_effect_gar').text(data.effect_gar);
                $('#show_effect_fil').text(data.effect_fil);
                $('#show_nom_cla').text(data.nom_cla);
                $('#show_nom_ecl').text(data.nom_ecl);
                $('#show_date_rep').text(data.date_rep);
                $('#show_nom_vill').text(data.nom_vill);
                $('#show_nom_cant').text(data.nom_cant);
                $('#show_nom_comm').text(data.nom_comm);
                $('#show_nom_pref').text(data.nom_pref);
                $('#show_nom_reg').text(data.nom_reg);
            })
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        

        $('body').on('click', '#format_charger', function(){
            $.get('/repas/format_charger', function(res){
                $('.modal-title').text('Téléchargement des cantines selon le niveau de localisation / Format de chargement de données de repas');
                $('.format_charger_modal').modal('show');
                //$('#form')[0].reset();
            })
        });



        $('body').on('click', '#add_repas', function(){
            $.get('/repas/create', function(res){
                $('.modal-title').text('Nouveau repas');
                $('.add_repas_modal').modal('show');
                //$('#form')[0].reset();
            })
        });

        $(document).on('click', '.add_repas_btn', function(e){
            e.preventDefault();
            // Récupérer la région
            let selectElement = document.getElementById("region_rep");
            // Récupérer l'option sélectionnée
            let selectedOption = selectElement.options[selectElement.selectedIndex];
            // Récupérer la valeur de l'attribut data-nom
            let dataNomValue = selectedOption.getAttribute("data-reg");

            // Récupérer la commune
            // Récupérer l'élément select
            let selectCanton = document.getElementById("commune_rep");
            // Récupérer l'option sélectionnée
            let optionCanton = selectCanton.options[selectCanton.selectedIndex];
            // Récupérer la valeur de l'attribut data-nom
            let cantonValue = optionCanton.getAttribute("data-comm");

            // Récupérer la cantine
            let selectEcole = document.getElementById("ecole_rep");
            // Récupérer l'option sélectionnée
            let optionEcole = selectEcole.options[selectEcole.selectedIndex];
            // Récupérer la valeur de l'attribut data-nom
            let ecoleValue = optionEcole.getAttribute("data-ecl");

            let inputElement1 = document.getElementById("monInput0");
            let inputElement2 = document.getElementById("monInput1");

            let eval = document.getElementById("eval").value;

            let  data = {};

            if (eval == "prepri") {
                // Récupérer la valeur de l'attribut data-nom
                let cla0 = inputElement1.getAttribute("data-id0");
                // Récupérer la valeur de l'attribut data-nom
                let cla1 = inputElement2.getAttribute("data-id1");

                //console.log($('.description').val());
                data = {
                    'inscrit_id0': $('.inscrit_id0').val(), 
                    'effect_fil_0': $('.effect_fil_0').val(), 
                    'effect_gar_0': $('.effect_gar_0').val(),

                    'inscrit_id1': $('.inscrit_id1').val(),
                    'effect_fil_1': $('.effect_fil_1').val(), 
                    'effect_gar_1': $('.effect_gar_1').val(),
                    'menu_id': $('.menu_id').val(),
                    'descrip': $('.descrip').val(),
                    'eval': $('.prepri').val(),

                    'date_rep1': $('.date_rep1').val(),
                    
                    'test0':dataNomValue+''+cantonValue+''+ecoleValue+''+cla0+''+$('.date_rep1').val(),
                    'test1':dataNomValue+''+cantonValue+''+ecoleValue+''+cla1+''+$('.date_rep1').val()
                };

            } else if (eval == "prescolaire") {
                // Récupérer la valeur de l'attribut data-nom
                let cla0 = inputElement1.getAttribute("data-id0");

                //console.log($('.description').val());
                data = {
                    'inscrit_id0': $('.inscrit_id0').val(), 
                    'effect_fil_0': $('.effect_fil_0').val(), 
                    'effect_gar_0': $('.effect_gar_0').val(),

                    'menu_id': $('.menu_id').val(),
                    'descrip': $('.descrip').val(),

                    'date_rep1': $('.date_rep1').val(),
                    'eval': $('.prescolaire').val(),
                    
                    'test0':dataNomValue+''+cantonValue+''+ecoleValue+''+cla0+''+$('.date_rep1').val(),
                };
            } else if (eval == "primaire") {
                // Récupérer la valeur de l'attribut data-nom
                let cla0 = inputElement1.getAttribute("data-id0");
                data = {
                    'inscrit_id0': $('.inscrit_id0').val(),
                    'effect_fil_1': $('.effect_fil_1').val(), 
                    'effect_gar_1': $('.effect_gar_1').val(),

                    'menu_id': $('.menu_id').val(),
                    'descrip': $('.descrip').val(),

                    'date_rep1': $('.date_rep1').val(),
                    'eval': $('.primaire').val(),
                    
                    'test0':dataNomValue+''+cantonValue+''+ecoleValue+''+cla0+''+$('.date_rep1').val(),
                };
            }

            $.ajax({
                type: 'post',
                url: '/repas/',
                data: data,
                dataType: 'json',
                success: function(response){
                    if(response.status == 400){
                        $('#saveform_errList_1').html("");
                        $('#saveform_errList_1').addClass('alert alert-danger');
                        $.each(response.errors, function (key, err_values){
                            $('#saveform_errList_1').append('<li>'+err_values+'</li>');
                        });
                    }else if(response.status == 10){
                        $('#saveform_errList_1').html("");
                        $('#saveform_errList_1').addClass('alert alert-danger');
                        $('#saveform_errList_1').append('<li>'+response.errors+'</li>');
                    }else if(response.status == 11){
                        $('#saveform_errList_1').html("");
                        $('#saveform_errList_1').addClass('alert alert-danger');
                        $('#saveform_errList_1').append('<li>'+response.errors+'</li>');
                    }else if(response.status == 12){
                        $('#saveform_errList_1').html("");
                        $('#saveform_errList_1').addClass('alert alert-danger');
                        $('#saveform_errList_1').append('<li>'+response.errors+'</li>');
                    }else if(response.status == 13){
                        $('#saveform_errList_1').html("");
                        $('#saveform_errList_1').addClass('alert alert-danger');
                        $('#saveform_errList_1').append('<li>'+response.errors+'</li>');
                    }else if(response.status == 20){
                        $('#saveform_errList_1').html("");
                        $('#saveform_errList_1').addClass('alert alert-danger');
                        $('#saveform_errList_1').append('<li>'+response.errors+'</li>');
                    }else if(response.status == 200){
                        $('#add_repas_modal').modal('hide');
                        window.location.href = "{{URL::to('repas')}}";
                    }
                }

            })
        });


        $('body').on('click', '#edit_repas', function(){
            let id = $(this).data('id');
            let inscrit_id = $(this).data('inscrit_id');
            let url = '/repas/'+id+'/edit';
            $.get(url, function(data){
                $('.modal-title').text('Modification du repas n°'+id);
                $('.edit_repas_modal').modal('show');
                $('.id').val(id);
                $('.edit_effect_gar').val(data.effect_gar);
                $('.edit_effect_fil').val(data.effect_fil);
                $('.inscrit_id').val(inscrit_id);
                $('.menu_id').val(data.menu_id);
                $('.edit_date_rep').val(data.date_rep);
                //$('.edit_descrip').val(data.descrip);
                //$('#form')[0].reset();
            })
        });

        $(document).on('click', '.edit_repas_btn', function(e){
            e.preventDefault();
            let id = $('.id').val();

            let data = {
                'inscrit_id': $('.inscrit_id').val(),
                'menu_id': $('.menu_id').val(),
                'effect_gar': $('.edit_effect_gar').val(),
                'effect_fil': $('.edit_effect_fil').val(),
                'date_rep': $('.edit_date_rep').val()
            };

            $.ajax({
                    type: 'put',
                    url: '/repas/'+id,
                    data: data,
                    dataType: 'json',
                    success: function(response){
                        if(response.status == 400){
                            $('#saveform_errList_edit').html("");
                            $('#saveform_errList_edit').addClass('alert alert-danger');
                            $.each(response.errors, function (key, err_values){
                                $('#saveform_errList_edit').append('<li>'+err_values+'</li>');
                            });
                        }else if(response.status == 10){
                            $('#saveform_errList_edit').html("");
                            $('#saveform_errList_edit').addClass('alert alert-danger');
                            $('#saveform_errList_edit').append('<li>'+response.errors+'</li>');
                        }else if(response.status == 11){
                            $('#saveform_errList_edit').html("");
                            $('#saveform_errList_edit').addClass('alert alert-danger');
                            $('#saveform_errList_edit').append('<li>'+response.errors+'</li>');
                        }else if(response.status == 12){
                            $('#saveform_errList_edit').html("");
                            $('#saveform_errList_edit').addClass('alert alert-danger');
                            $('#saveform_errList_edit').append('<li>'+response.errors+'</li>');
                        }else if(response.status == 13){
                            $('#saveform_errList_edit').html("");
                            $('#saveform_errList_edit').addClass('alert alert-danger');
                            $('#saveform_errList_edit').append('<li>'+response.errors+'</li>');
                        }else if(response.status == 20){
                            $('#saveform_errList_edit').html("");
                            $('#saveform_errList_edit').addClass('alert alert-danger');
                            $('#saveform_errList_edit').append('<li>'+response.errors+'</li>');
                        }else if(response.status == 200){
                            $('.edit_repas_modal').modal('hide');
                            window.location.href = "{{URL::to('repas')}}";
                        }                    
                    }
                })
            });

        $('body').on('click', '#delete_repas', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            console.log(id);
                $('.modal-title').text('Suppression du repas n°'+id);
                $('.delete_repas_modal').modal('show');
                $('#btn_delete_repas').val(id);
        });

        $('#btn_delete_repas').click(function(){
            let id = $(this).val();
            $.ajax({
                type: 'DELETE',
                url: '/repas/'+id
            });
            //$('#form')[0].reset();
            $('.delete_repas_modal').modal('hide');
            location.reload(true);
        });
    });


    function index_repas(){
        $.get("{{URL::to('repas')}}", function(data){
            $('.gridjs-table').empty().html(data);
        })
    }
</script>