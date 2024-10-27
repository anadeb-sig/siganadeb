<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
    <!-- Sidenav Toggle Button-->
    <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i data-feather="menu"></i></button>
    <!-- Navbar Brand-->
    <a class="navbar-brand pe-3 ps-4 ps-lg-2" href="{{ Route('home') }}">ANADEB</a>
    <!-- Navbar Items-->
    <ul class="navbar-nav align-items-center ms-auto">
        <?php 
            $noti = new App\Models\Demande;
            $notifications = $noti->notifi();
            $notificationsCount = $notifications->count();
 
            $note = new App\Models\Demande_jour;
            $notification_jour = $note->notifi();
            $notification_jourCount = $notification_jour->count();

            use Carbon\Carbon;
            Carbon::setLocale('fr');
        ?>
        @if($notificationsCount > 0)
            <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
                <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="badge bg-danger">{{ $notificationsCount }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownAlerts" data-bs-popper="static">
                    <h6 class="dropdown-header dropdown-notifications-header" style="background-color: #f8f8f8;color: #126a50 !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell me-2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        Demande d'approbation de suspension
                    </h6>
                    @foreach ($notifications as $notification)
                        <a class="dropdown-item dropdown-notifications-item" href="{{ url('demandes/detail/' . $notification->id .'/'. $notification->site_id) }}">
                            <div class="dropdown-notifications-item-icon bg-danger">
                                <svg class="svg-inline--fa fa-triangle-exclamation" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="triangle-exclamation" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"></path>
                                </svg>
                            </div>
                            <div class="dropdown-notifications-item-content">
                                <div class="dropdown-notifications-item-content-details">{{ Carbon::parse($notification->created_at)->translatedFormat('F j, Y') }}</div>
                                <div class="dropdown-notifications-item-content-text">{{ $notification->titre }}</div>
                            </div>
                        </a>
                    @endforeach
                    <a class="dropdown-item dropdown-notifications-footer" href="{{ Route('demandes.index') }}">Voir toutes les alertes</a>
                </div>
            </li>
        @endif

        @if($notification_jourCount > 0)
            <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
                <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="badge bg-danger">{{ $notification_jourCount }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownAlerts" data-bs-popper="static">
                    <h6 class="dropdown-header dropdown-notifications-header" style="background-color: #f8f8f8;color: #126a50 !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell me-2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        Demande d'augmentation de jours
                    </h6>
                    @foreach ($notification_jour as $notification)
                        <a class="dropdown-item dropdown-notifications-item" href="{{ url('demandejours/detail/' . $notification->id) }}">
                        <div class="dropdown-notifications-item-icon bg-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg></div>
                            <div class="dropdown-notifications-item-content">
                                <div class="dropdown-notifications-item-content-details">{{ Carbon::parse($notification->created_at)->translatedFormat('F j, Y') }}</div>
                                <div class="dropdown-notifications-item-content-text">{{ $notification->titre }}</div>
                            </div>
                        </a>
                    @endforeach
                    <a class="dropdown-item dropdown-notifications-footer" href="{{ Route('demandejours.index') }}">Voir toutes les alertes</a>
                </div>
            </li>
        @endif
        <!-- User Dropdown-->
        <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @if(!empty(Auth::user()->avatar))
                    <img class="img-fluid" alt="Avatar" src="/admin/img/{{ Auth::user()->avatar }}">
                @else
                    <img class="img-fluid" alt="Avatar" src="{{ asset('admin/img/undraw_profile.png') }}">
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    @if(!empty(Auth::user()->avatar))
                        <!--<img class="dropdown-user-img" alt="Avatar" src="/admin/img/{{ Auth::user()->avatar }}">-->
                    @else
                        <!--<img class="dropdown-user-img" alt="Avatar" src="{{ asset('admin/img/undraw_profile.png') }}">-->
                    @endif
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name">{{ Auth::user()->last_name." ".Auth::user()->first_name }}</div>
                        <div class="dropdown-user-details-email"><a href="javascript: void(0);" class="__cf_email__" data-cfemail="">{{ Auth::user()->email }}</a></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('profile.detail') }}">
                    <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                    Profile
                </a>

                <hr class="sidebar-divider">

                <a class="dropdown-item"  href="javascript: void(0);" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Déconnectez-vous ?
                </a>
            </div>
        </li>
    </ul>
</nav>



