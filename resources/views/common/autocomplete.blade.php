<script type="text/javascript">
        $(document).ready(function() {
            $("#nom_reg").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/regions/autocomplete_reg",
                        data: {
                                term : request.term
                        },
                        dataType: "json",
                        success: function(data){
                           var resp = $.map(data,function(obj){
                                return obj.nom_reg;
                        });         
                        response(resp);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script>

    
    <script type="text/javascript">
        $(document).ready(function() {
            $("#nom_pref").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/regions/autocomplete_pref",
                        data: {
                                term : request.term
                        },
                        dataType: "json",
                        success: function(data){
                           var resp = $.map(data,function(obj){
                                return obj.nom_pref;
                        });         
                        response(resp);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#nom_comm").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/regions/autocomplete_comm",
                        data: {
                                term : request.term
                        },
                        dataType: "json",
                        success: function(data){
                           var resp = $.map(data,function(obj){
                                return obj.nom_comm;
                        });         
                        response(resp);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script>
    
    <script type="text/javascript">
        $(document).ready(function() {
            $("#nom_cant").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/regions/autocomplete_cant",
                        data: {
                                term : request.term
                        },
                        dataType: "json",
                        success: function(data){
                           var resp = $.map(data,function(obj){
                                return obj.nom_cant;
                        });         
                        response(resp);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#nom_vill").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/regions/autocomplete_vill",
                        data: {
                            term : request.term
                        },
                        dataType: "json",
                        success: function(data){
                           let resp = $.map(data,function(obj){
                                return obj.nom_vill;
                        });         
                        response(resp);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script>

    <!-- <script type="text/javascript">
        $(document).ready(function() {
            $("#phone_member1").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/regions/autocomplete_phoneMenage",
                        data: {
                            term : request.term
                        },
                        dataType: "json",
                        success: function(data){
                           let resp = $.map(data,function(obj){
                                return obj.phone_member1;
                        });         
                        response(resp);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script> -->

    <script type="text/javascript">
        $(document).ready(function() {
            $("#nom_ecl").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/regions/autocomplete_ecl",
                        data: {
                                term : request.term
                        },
                        dataType: "json",
                        success: function(data){
                           var resp = $.map(data,function(obj){
                                return obj.nom_ecl;
                        });         
                        response(resp);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script>
    
    <script type="text/javascript">
        $(document).ready(function() {
            $("#nom_fin").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/regions/autocomplete_fin",
                        data: {
                                term : request.term
                        },
                        dataType: "json",
                        success: function(data){
                           var resp = $.map(data,function(obj){
                                return obj.nom_fin;
                        });         
                        response(resp);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script>

<script type="text/javascript">
        $(document).ready(function() {
            $("#phoneMenage").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/regions/autocomplete_phoneMenage",
                        data: {
                            term : request.term
                        },
                        dataType: "json",
                        success: function(data){
                           let resp = $.map(data,function(obj){
                                return obj.phone_member1;
                        });         
                        response(resp);
                        console.log(data);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script>

