<!DOCTYPE html>
<html lang="fr">
    {{-- Include Head --}}
    @include('common.head')
    
    <body class="nav-fixed">
        <!-- Chargement de pages -->
        @include('common.loader')
        <!-- L'entête de pages -->
        @include('common.header')
        <div id="layoutSidenav">
            @include('common.sidebar')
            <!-- End of Sidebar -->
            <div id="layoutSidenav_content">
                <main>
                    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                        <div class="container-xl px-4">
                            <div class="page-header-content pt-4">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mt-4">
                                        <h1 class="page-header-title">
                                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                                            <!-- <img class="img-fluid" width="70" alt="ANADEB" src="{{ asset('assets/images/Logo-ANADEB-rogner.png') }}"> -->
                                        </h1>
                                        <!-- <div class="page-header-subtitle">Example dashboard overview and content summary</div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    <!-- Main page content-->
                    <div class="container-xl px-4 mt-n10">
                        @include('common.alert')
                        @yield('content')
                    </div>
                </main>
                @include('common.footer')
            </div>
        </div>
        <!-- Logout Modal-->
        @include('common.logout-modal')

        <button onclick="scrollToTop()" id="scrollToTopBtn"><i class="fas fa-angle-up"></i></button>
        
        @include('common.autocomplete')
        @include('common.scripts')
    </body>

</html>