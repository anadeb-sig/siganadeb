<!--Pour filtre la liste-->
<script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/query/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.8/xlsx.full.min.js"></script>
<script type="text/javascript" src="{{ asset('js/prism-core.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/feather.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/litepicker.js') }}"></script>

<!--Faire autocomplet-->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">  

<script src="{{ asset('assets/libs/gridjs/dist_gridjs.production.min.js')}}"></script>

<script>
    //Communes par région
    $(document).ready(function() {
        $('#region_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/' + value;
            let commune_comm = $('#commune_comm');    
            commune_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    commune_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    commune_comm.append('<option value="' + option.id + '">' + option.nom_comm + '</option>');
                });          
                commune_comm.prop('disabled', false);
                },
                error: function() {
                    commune_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                commune_comm.html('<option value="">Sélectionnez une option</option>');
                commune_comm.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#region_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/' + value;
            let commune_edit = $('#commune_edit');    
            commune_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    commune_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    commune_edit.append('<option value="' + option.id + '">' + option.nom_comm + '</option>');
                });          
                commune_edit.prop('disabled', false);
                },
                error: function() {
                    commune_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                commune_edit.html('<option value="">Sélectionnez une option</option>');
                commune_edit.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#region_edit_site').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/' + value;
            let commune_edit = $('#commune_edit_site');    
            commune_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    commune_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    commune_edit.append('<option value="' + option.id + '">' + option.nom_comm + '</option>');
                });          
                commune_edit.prop('disabled', false);
                },
                error: function() {
                    commune_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                commune_edit.html('<option value="">Sélectionnez une option</option>');
                commune_edit.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#region_comm_site').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/' + value;
            let commune_edit = $('#commune_comm_site');    
            commune_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    commune_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    commune_edit.append('<option value="' + option.id + '">' + option.nom_comm + '</option>');
                });          
                commune_edit.prop('disabled', false);
                },
                error: function() {
                    commune_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                commune_edit.html('<option value="">Sélectionnez une option</option>');
                commune_edit.prop('disabled', true);
            }
        });
    });

    //Villages par rcommune
    $(document).ready(function() {
        $('#commune_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/villages/get-option/' + value;
            let village_comm = $('#village_comm');    
            village_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    village_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    village_comm.append('<option value="' + option.id + '" data-vill="' + option.nom_vill + '">' + option.nom_vill+ '</option>');
                });          
                village_comm.prop('disabled', false);
                },
                error: function() {
                    village_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                village_comm.html('<option value="">Sélectionnez une option</option>');
                village_comm.prop('disabled', true);
            }
        });
    });

    //Sites par rcommune
    $(document).ready(function() {
        $('#commune_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/sites/site_commune/' + value;
            let site_comm = $('#site_comm');    
            site_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    site_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    site_comm.append('<option value="' + option.id + '" data-vill="' + option.nom_site + '">' + option.nom_site+ '</option>');
                });          
                site_comm.prop('disabled', false);
                },
                error: function() {
                    site_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                site_comm.html('<option value="">Sélectionnez une option</option>');
                site_comm.prop('disabled', true);
            }
        });
    });

    //Ouvrages par site
    $(document).ready(function() {
        $('#site_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/sites/ouvrage_site/' + value;
            let ouvrages_site = $('#ouvrages_site');    
            ouvrages_site.prop('disabled', true).html('<span value="">Chargement en cours...</span>');    

            if (url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Réinitialise le contenu
                        ouvrages_site.html('');
                        
                        // Ajouter la case "Sélectionner tout"
                        ouvrages_site.append('<span class="mt-4">Liste d\'ouvrage(s) à construire<hr><label><input type="checkbox" id="checkAllOuvrages"/> Tout sélectionner</label><hr>');
                        // Ajouter les ouvrages récupérés
                        $.each(data, function(index, option) {
                            ouvrages_site.append(
                                '<div class="form-check form-check-inline">' +
                                    '<input class="form-check-input ouvrage-input" type="checkbox" name="ouvrage_id[]" id="inlineCheckbox_' + index + '" value="' + option.id + '">' +
                                    '<label class="form-check-label" for="inlineCheckbox_' + index + '">' + option.nom_ouvrage + '</label>' +
                                '</div><br>'
                            );
                        });

                        // Réactiver le champ
                        ouvrages_site.prop('disabled', false);

                        // Gérer la sélection de "Tout sélectionner"
                        $('#checkAllOuvrages').change(function() {
                            if ($(this).is(':checked')) {
                                $('.ouvrage-input').prop('checked', true);
                            } else {
                                $('.ouvrage-input').prop('checked', false);
                            }
                        });
                    },
                    error: function() {
                        ouvrages_site.html('<span value="">Erreur de chargement</span>');
                    }
                });
            } else {
                ouvrages_site.html('<span value="">Sélectionnez une option</span>');
                ouvrages_site.prop('disabled', true);
            }
        });
    });


    $(document).ready(function() {
        $('#commune_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/villages/get-option/' + value;
            let village_edit = $('#village_edit');    
            village_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    village_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    village_edit.append('<option value="' + option.id + '">' + option.nom_vill+ '</option>');
                });          
                village_edit.prop('disabled', false);
                },
                error: function() {
                    village_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                village_edit.html('<option value="">Sélectionnez une option</option>');
                village_edit.prop('disabled', true);
            }
        });
    });

    //Sites par rcommune
    $(document).ready(function() {
        $('#commune_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/sites/site_commune/' + value;
            let site_edit = $('#site_edit');    
            site_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    site_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    site_edit.append('<option value="' + option.id + '" data-vill="' + option.nom_site + '">' + option.nom_site+ '</option>');
                });          
                site_edit.prop('disabled', false);
                },
                error: function() {
                    site_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                site_edit.html('<option value="">Sélectionnez une option</option>');
                site_edit.prop('disabled', true);
            }
        });
    });

    //Ecoles par commune
    $(document).ready(function() {
        $('#commune_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/ecoles/get-options/' + value;
            let ecole_comm = $('#ecole_comm');    
            ecole_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    ecole_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    ecole_comm.append('<option value="' + option.id + '">' + option.nom_ecl+ '</option>');
                });          
                ecole_comm.prop('disabled', false);
                },
                error: function() {
                    ecole_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                ecole_comm.html('<option value="">Sélectionnez une option</option>');
                ecole_comm.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#commune_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/ecoles/get-options/' + value;
            let ecole_edit = $('#ecole_edit');    
            ecole_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    ecole_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    ecole_edit.append('<option value="' + option.id + '">' + option.nom_ecl+ '</option>');
                });          
                ecole_edit.prop('disabled', false);
                },
                error: function() {
                    ecole_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                ecole_edit.html('<option value="">Sélectionnez une option</option>');
                ecole_edit.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#ecole_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/classes/ecole/' + value;
            let classe_comm = $('#classe_c');
            classe_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    classe_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    classe_comm.append('<option value="' + option.id + '" data-cla="' + option.nom_cla + '">' + option.nom_cla+ '</option>');
                });          
                classe_comm.prop('disabled', false);
                },
                error: function() {
                    classe_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                classe_comm.html('<option value="">Sélectionnez une option</option>');
                classe_comm.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#ecole_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/classes/ecole/' + value;
            let classe_comm = $('#classe_com');
            classe_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    classe_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    classe_comm.append('<option value="' + option.id + '" data-cla="' + option.nom_cla + '">' + option.nom_cla+ '</option>');
                });          
                classe_comm.prop('disabled', false);
                },
                error: function() {
                    classe_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                classe_comm.html('<option value="">Sélectionnez une option</option>');
                classe_comm.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#ecole_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/classes/get-options/' + value;
            let classe_comm = $('#classe_comm'); 
            classe_comm.empty();   
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.length > 0) {
                        $.each(data, function(index, option) {
                            classe_comm.append('<input style="display:none;" name="classe_id[]"  data-cla="' + option.nom_cla + '" data-id="' + option.nom_cla + '" value="' + option.id + '">');
                        });
                    }else{
                        classe_comm.append('<span style="color: red;font-size: 1.2em;"> Aucune école n\'est associée à cette cantine!</span>');
                    }
                },
                error: function() {
                    //classe_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                //classe_comm.html('<option value="">Sélectionnez une option</option>');
                //classe_comm.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#ecole_comm_2').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/classes/get-options/' + value;
            let classe_comm = $('#classe_comm_2'); 
            classe_comm.empty();   
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.length > 0) {
                        $.each(data, function(index, option) {
                            classe_comm.append('<input id="monInput'+index+'" style="display:none;" class="classe_id'+index+'" name="classe_id'+index+'" data-id'+index+'="' + option.nom_cla + '" value="' + option.id + '">');
                        });
                    }else{
                        classe_comm.append('<span style="color: red;font-size: 1.2em;"> Aucune école n\'est associée à cette cantine!</span>');
                    }
                },
                error: function() {
                    //classe_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                //classe_comm.html('<option value="">Sélectionnez une option</option>');
                //classe_comm.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#ecole_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/classes/get-options/' + value;
            let classe_commm = $('#classe_commm');    
            classe_commm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    classe_commm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    classe_commm.append('<option value="' + option.id + '">' + option.nom_cla+ '</option>');
                });          
                classe_commm.prop('disabled', false);
                },
                error: function() {
                    classe_commm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                classe_commm.html('<option value="">Sélectionnez une option</option>');
                classe_commm.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#commune_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/ouvrages/get-sign/' + value;
            let ouvrage_comm = $('#ouvrage_comm');    
            ouvrage_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    ouvrage_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    ouvrage_comm.append('<option value="' + option.id + '" data-ouv="' + option.nom_ouvrage + '">' + option.nom_ouvrage+ '</option>');
                });          
                ouvrage_comm.prop('disabled', false);
                },
                error: function() {
                    ouvrage_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                ouvrage_comm.html('<option value="">Sélectionnez une option</option>');
                ouvrage_comm.prop('disabled', true);
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#region_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/get-options/' + value;
            let prefecture_comm = $('#prefecture_comm');    
            prefecture_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    prefecture_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    prefecture_comm.append('<option value="' + option.id + '">' + option.nom_pref + '</option>');
                });          
                prefecture_comm.prop('disabled', false);
                },
                error: function() {
                    prefecture_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                prefecture_comm.html('<option value="">Sélectionnez une option</option>');
                prefecture_comm.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#ouvrage_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/realisations/par_ouvrage/' + value;
            let estimation_id = $('#estimation_id');    
            estimation_id.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    estimation_id.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    estimation_id.append('<option value="' + option.id + '" data-ouv="' + option.design + '">' + option.design+ '</option>');
                });          
                estimation_id.prop('disabled', false);
                },
                error: function() {
                    estimation_id.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                estimation_id.html('<option value="">Sélectionnez une option</option>');
                estimation_id.prop('disabled', true);
            }
        });
    });
</script>


    
<script>
    $(document).ready(function() {
        $('#region_contrat').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/' + value;
            let commune_contrat = $('#commune_contrat');    
            commune_contrat.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    commune_contrat.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    commune_contrat.append('<option value="' + option.id + '">' + option.nom_comm + '</option>');
                });          
                commune_contrat.prop('disabled', false);
                },
                error: function() {
                    commune_contrat.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                commune_contrat.html('<option value="">Sélectionnez une option</option>');
                commune_contrat.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#commune_contrat').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/sites/ouvcontrat_sign/' + value;
            let ouvrage_comm = $('#site_contrat');    
            ouvrage_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    ouvrage_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    ouvrage_comm.append('<option value="' + option.id + '" data-ouv="' + option.nom_site + '">' + option.nom_site+ '</option>');
                });          
                ouvrage_comm.prop('disabled', false);
                },
                error: function() {
                    ouvrage_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                ouvrage_comm.html('<option value="">Sélectionnez une option</option>');
                ouvrage_comm.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#region_contrat_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/' + value;
            let commune_contrat = $('#commune_contrat_edit');    
            commune_contrat.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    commune_contrat.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    commune_contrat.append('<option value="' + option.id + '">' + option.nom_comm + '</option>');
                });          
                commune_contrat.prop('disabled', false);
                },
                error: function() {
                    commune_contrat.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                commune_contrat.html('<option value="">Sélectionnez une option</option>');
                commune_contrat.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#commune_contrat_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/sites/ouvcontrat_sign/' + value;
            let ouvrage_comm = $('#site_contrat_edit');    
            ouvrage_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    ouvrage_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    ouvrage_comm.append('<option value="' + option.id + '" data-ouv="' + option.nom_site + '">' + option.nom_site+ '</option>');
                });          
                ouvrage_comm.prop('disabled', false);
                },
                error: function() {
                    ouvrage_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                ouvrage_comm.html('<option value="">Sélectionnez une option</option>');
                ouvrage_comm.prop('disabled', true);
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#commune_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/ouvrages/get-sign/' + value;
            let ouvrage_sign = $('#ouvrage_edit');    
            ouvrage_sign.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    ouvrage_sign.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    ouvrage_sign.append('<option value="' + option.id + '">' + option.nom_ouvrage+ '</option>');
                });          
                ouvrage_sign.prop('disabled', false);
                },
                error: function() {
                    ouvrage_sign.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                ouvrage_sign.html('<option value="">Sélectionnez une option</option>');
                ouvrage_sign.prop('disabled', true);
            }
        });
    });
</script>

<!-- Edite -->
<script>
    // $(document).ready(function() {
    //     $('#region_edit').change(function() {
    //         let value = $(this).val();
    //         let selectedOption = $(this).find('option:selected');
    //         let url = '/cantons/' + value;
    //         let commune_edit = $('#commune_edit');
    //         commune_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
    //         if (url) {
    //         $.ajax({
    //             url: url,
    //             method: 'GET',
    //             dataType: 'json',
    //             success: function(data) {
    //                 commune_edit.html('<option value="">Sélectionnez une option</option>');          
    //             $.each(data, function(index, option) {
    //                 commune_edit.append('<option value="' + option.id + '" data-cant="' + option.nom_comm + '">' + option.nom_comm + '</option>');
    //             });          
    //             commune_edit.prop('disabled', false);
    //             },
    //             error: function() {
    //                 commune_edit.html('<option value="">Erreur de chargement</option>');
    //             }
    //         });
    //         } else {
    //             commune_edit.html('<option value="">Sélectionnez une option</option>');
    //             commune_edit.prop('disabled', true);
    //         }
    //     });
    // });
</script>

<script>
    $(document).ready(function() {
        $('#canton_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/ecoles/canton/' + value;
            let ecole_edit = $('#ecole_edit');
            ecole_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    ecole_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    ecole_edit.append('<option value="' + option.id + '" data-ecl="' + option.nom_ecl + '">' + option.nom_ecl+ '</option>');
                });          
                ecole_edit.prop('disabled', false);
                },
                error: function() {
                    ecole_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                ecole_edit.html('<option value="">Sélectionnez une option</option>');
                ecole_edit.prop('disabled', true);
            }
        });
    });
</script>


<!-- Commune --- Canton -->
<script>
    $(document).ready(function() {
        $('#commune_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/cantons/' + value;
            let canton_comm = $('#canton_comm');    
            canton_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    canton_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    canton_comm.append('<option value="' + option.id + '" data-comm="' + option.nom_cant+ '">' + option.nom_cant + '</option>');
                });          
                canton_comm.prop('disabled', false);
                },
                error: function() {
                    canton_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                canton_comm.html('<option value="">Sélectionnez une option</option>');
                canton_comm.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#commune_comm_site').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/cantons/' + value;
            let canton_comm = $('#canton_comm_site');    
            canton_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    canton_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    canton_comm.append('<option value="' + option.id + '" data-comm="' + option.nom_cant+ '">' + option.nom_cant + '</option>');
                });          
                canton_comm.prop('disabled', false);
                },
                error: function() {
                    canton_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                canton_comm.html('<option value="">Sélectionnez une option</option>');
                canton_comm.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#commune_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/cantons/' + value;
            let canton_edit = $('#canton_edit');    
            canton_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    canton_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    canton_edit.append('<option value="' + option.id + '" data-comm="' + option.nom_cant+ '">' + option.nom_cant + '</option>');
                });          
                canton_edit.prop('disabled', false);
                },
                error: function() {
                    canton_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                canton_edit.html('<option value="">Sélectionnez une option</option>');
                canton_edit.prop('disabled', true);
            }
        });
    });
</script>



<script>
    $(document).ready(function() {
        $('#region_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/get-options/' + value;
            let prefecture_comm = $('#prefecture_comm');    
            prefecture_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    prefecture_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    prefecture_comm.append('<option value="' + option.id + '">' + option.nom_pref + '</option>');
                });          
                prefecture_comm.prop('disabled', false);
                },
                error: function() {
                    prefecture_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                prefecture_comm.html('<option value="">Sélectionnez une option</option>');
                prefecture_comm.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#region_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/get-options/' + value;
            let prefecture_edit = $('#prefecture_edit');    
            prefecture_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    prefecture_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    prefecture_edit.append('<option value="' + option.id + '">' + option.nom_pref + '</option>');
                });          
                prefecture_edit.prop('disabled', false);
                },
                error: function() {
                    prefecture_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                prefecture_edit.html('<option value="">Sélectionnez une option</option>');
                prefecture_edit.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#prefecture_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/cantons/get-options/' + value;
            let commune_edit = $('#commune_edit');    
            commune_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    commune_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    commune_edit.append('<option value="' + option.id + '">' + option.nom_comm + '</option>');
                });          
                commune_edit.prop('disabled', false);
                },
                error: function() {
                    commune_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                commune_edit.html('<option value="">Sélectionnez une option</option>');
                commune_edit.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#commune_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/villages/get-options/' + value;
            let canton_edit = $('#canton_edit');    
            canton_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    canton_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    canton_edit.append('<option value="' + option.id + '" data-cant="' + option.nom_cant + '">' + option.nom_cant+ '</option>');
                });          
                canton_edit.prop('disabled', false);
                },
                error: function() {
                    canton_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                canton_edit.html('<option value="">Sélectionnez une option</option>');
                canton_edit.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#canton_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/villages/get-option/' + value;
            let village_edit = $('#village_edit');    
            village_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    village_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    village_edit.append('<option value="' + option.id + '" data-cant="' + option.nom_cant + '">' + option.nom_vill+ '</option>');
                });          
                village_edit.prop('disabled', false);
                },
                error: function() {
                    village_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                village_edit.html('<option value="">Sélectionnez une option</option>');
                village_edit.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#village_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/ecoles/get-options/' + value;
            let ecole_edit = $('#ecole_edit');    
            ecole_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    ecole_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    ecole_edit.append('<option value="' + option.id + '">' + option.nom_ecl+ '</option>');
                });          
                ecole_edit.prop('disabled', false);
                },
                error: function() {
                    ecole_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                ecole_edit.html('<option value="">Sélectionnez une option</option>');
                ecole_edit.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#ecole_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/classes/get-options/' + value;
            let classe_edit = $('#classe_edit');    
            classe_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    classe_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    classe_edit.append('<option value="' + option.id + '" data-cla="' + option.nom_cla + '">' + option.nom_cla+ '</option>');
                });          
                classe_edit.prop('disabled', false);
                },
                error: function() {
                    classe_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                classe_edit.html('<option value="">Sélectionnez une option</option>');
                classe_edit.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#canton_edit').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/ouvrages/get-sign/' + value;
            let ouvrage_edit = $('#ouvrage_edit');    
            ouvrage_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    ouvrage_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    ouvrage_edit.append('<option value="' + option.id + '">' + option.nom_ouvrage+ '</option>');
                });          
                ouvrage_edit.prop('disabled', false);
                },
                error: function() {
                    ouvrage_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                ouvrage_edit.html('<option value="">Sélectionnez une option</option>');
                ouvrage_edit.prop('disabled', true);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#region_comm').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/get-options/' + value;
            let prefecture_comm = $('#prefecture_comm');    
            prefecture_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    prefecture_comm.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    prefecture_comm.append('<option value="' + option.id + '">' + option.nom_pref + '</option>');
                });          
                prefecture_comm.prop('disabled', false);
                },
                error: function() {
                    prefecture_comm.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                prefecture_comm.html('<option value="">Sélectionnez une option</option>');
                prefecture_comm.prop('disabled', true);
            }
        });
    });

    $(document).ready(function() {
        $('#region_format').change(function() {
            let value = $(this).val();
            let selectedOption = $(this).find('option:selected');
            let url = '/communes/get-options/' + value;
            let prefecture_edit = $('#prefecture_format');    
            prefecture_edit.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
            if (url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    prefecture_edit.html('<option value="">Sélectionnez une option</option>');          
                $.each(data, function(index, option) {
                    prefecture_edit.append('<option value="' + option.id + '" data-id="' + option.id + '">' + option.nom_pref + '</option>');
                });          
                prefecture_edit.prop('disabled', false);
                },
                error: function() {
                    prefecture_edit.html('<option value="">Erreur de chargement</option>');
                }
            });
            } else {
                prefecture_edit.html('<option value="">Sélectionnez une option</option>');
                prefecture_edit.prop('disabled', true);
            }
        });
    });
</script>

<script>
        //Communes par région
        $(document).ready(function() {
            $('#region_rep').change(function() {
                let value = $(this).val();
                let selectedOption = $(this).find('option:selected');
                let url = '/communes/' + value;
                let commune_comm = $('#commune_rep');    
                commune_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
                if (url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        commune_comm.html('<option value="">Sélectionnez une option</option>');          
                    $.each(data, function(index, option) {
                        commune_comm.append('<option data-comm="' + option.nom_comm + '" value="' + option.id + '">' + option.nom_comm + '</option>');
                    });          
                    commune_comm.prop('disabled', false);
                    },
                    error: function() {
                        commune_comm.html('<option value="">Erreur de chargement</option>');
                    }
                });
                } else {
                    commune_comm.html('<option value="">Sélectionnez une option</option>');
                    commune_comm.prop('disabled', true);
                }
            });
        });

        //Villages par rcommune
        $(document).ready(function() {
            $('#commune_rep').change(function() {
                let value = $(this).val();
                let selectedOption = $(this).find('option:selected');
                let url = '/villages/get-option/'+value;
                let village_comm = $('#village_rep');    
                village_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
                if (url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        village_comm.html('<option value="">Sélectionnez une option</option>');          
                    $.each(data, function(index, option) {
                        village_comm.append('<option value="' + option.id + '">' + option.nom_vill+ '</option>');
                    });          
                    village_comm.prop('disabled', false);
                    },
                    error: function() {
                        village_comm.html('<option value="">Erreur de chargement</option>');
                    }
                });
                } else {
                    village_comm.html('<option value="">Sélectionnez une option</option>');
                    village_comm.prop('disabled', true);
                }
            });
        });

        //Ecoles par commune
        $(document).ready(function() {
            $('#commune_rep').change(function() {
                let value = $(this).val();
                let selectedOption = $(this).find('option:selected');
                let url = '/ecoles/get-options/' + value;
                let ecole_comm = $('#ecole_rep');    
                ecole_comm.prop('disabled', true).html('<option value="">Chargement en cours...</option>');    
                if (url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        ecole_comm.html('<option value="">Sélectionnez une option</option>');          
                    $.each(data, function(index, option) {
                        ecole_comm.append('<option data-ecl="'+ option.nom_ecl +'" value="' + option.id + '">' + option.nom_ecl+ '</option>');
                    });          
                    ecole_comm.prop('disabled', false);
                    },
                    error: function() {
                        ecole_comm.html('<option value="">Erreur de chargement</option>');
                    }
                });
                } else {
                    ecole_comm.html('<option value="">Sélectionnez une option</option>');
                    ecole_comm.prop('disabled', true);
                }
            });
        });
        
    </script>