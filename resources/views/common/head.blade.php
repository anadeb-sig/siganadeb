<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>ANADEB</title>
    <link href="{{ asset('css/app.css')}}" rel="stylesheet" />
    <link href="{{ asset('css/styles.css')}}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico')}}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/gridjs/theme/mermaid.min.css')}}"> 

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    
    <!-- GLightbox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    
    <script type="text/javascript" src="{{ asset('js/all.min.js') }}"></script>
    <!-- <script type="text/javascript" src="{{ asset('js/feather.min.js') }}"></script> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.synecole.js') }}"></script>
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.syncanton.js') }}"></script>
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.syncommune.js') }}"></script>
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.synprefecture.js') }}"></script>
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.synregion.js') }}"></script>
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.arf.js') }}"></script>
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.compta.js') }}"></script>
    
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.menage.js') }}"></script>

    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.beneficiaire.js') }}"></script>
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.etatpaiements.js') }}"></script>
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.etatpaiement_par_village.js') }}"></script>

    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.etatpaiement_par_bd.js') }}"></script>

    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.ouvrage.js') }}"></script>
    
    
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.contrat.js') }}"></script>
    
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.suivi.js') }}"></script>
    
    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.galerie.js') }}"></script>

    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.site.js') }}"></script>

    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.demande.js') }}"></script>

    <script type="text/javascript" src="{{ asset('./assets/gridjs/gridjs.demande_jour.js') }}"></script>

    

    <style>

        .gridjs-table{
            width: 100%;
        }
        /* .search_output .projet select{
            border-radius: 20px 0 0 20px;
        }

        .search_output .projet input{
            border-radius: 20px 0 0 20px;
        }

        .search_output .region select{
            border-radius: 20px 20px 20px 20px;
        }


        .search_output button {
            border-radius: 0 20px 20px 0;
        } */

        #map {
            height: 600px;
        }
        
        .hidden {
            display: none;
        }

        .rotate {
            transform: rotate(90deg);
            transition: transform 0.3s ease-in-out;
        }

        .majuscules{
            text-transform: uppercase;
        }

        @media screen and (min-width: 769px){
            .header-search .search-widget {
                align-items: center;
                display: flex;
                gap: .5rem;
            }
        }
        .header-search .search-widget {
            margin: 0 auto;
            max-width: 20rem;
            position: relative;
            width: 100%;
        }

        .button.search-button {
            --button-color: var(--icon-secondary);
            --button-height: 1.5rem;
            --button-padding: 0;
            border: none;
            background-color: #ffffff21;
            right: .75rem;
        }

        .button .icon {
            background-color: var(--button-color);
            margin: 0 -1px;
        }


        #scrollToTopBtn {
            display: none; /* Hide the button by default */
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99;
            font-size: 14px;
            padding: 10px;
            border: none;
            outline: none;
            background-color: #5f9ea0;
            color: #fff;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        #scrollToTopBtn:hover {
            opacity: 1;
        }
                
        #totalButton{
            padding-left: 1.125rem;
            padding-right: 1.125rem;
            padding-top: 0.875rem;
            padding-bottom: 0.875rem;
            border-color: white;
            border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
        }


        /* bouton d'export et import_request_variables */
        /* Container styles */
        .d-flex {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        /* Styles for file input and import button */
        .file-upload-form {
            display: flex;
            align-items: center;
        }

        .file-upload-form .input-group {
            display: flex;
            align-items: center;
            border-radius: 5px;
        }

        .file-upload-form .file-input {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            padding: 5px;
            border-color: #ced4da;
        }

        .file-upload-form .input-group-append .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* File export button styling */
        .file-export .btn {
            margin-left: 10px;
        }

        /* Button spacing */
        .mr-2 {
            margin-right: 10px;
        }

        .ml-2 {
            margin-left: 10px;
        }

        /* General button styles */
        .btnform {
            padding: 8px 16px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-outline-teal {
            color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-outline-teal:hover {
            background-color: #17a2b8;
            color: white;
        }

        .btn-outline-yellow {
            color: #ffc107;
            border-color: #ffc107;
        }

        .btn-outline-yellow:hover {
            background-color: #ffc107;
            color: white;
        }

        .btn-outline-cyan {
            color: #00bcd4;
            border-color: #00bcd4;
        }

        .btn-outline-cyan:hover {
            background-color: #00bcd4;
            color: white;
        }
    </style>
</head>