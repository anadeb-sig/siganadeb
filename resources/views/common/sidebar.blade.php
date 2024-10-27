<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Core) -->
                <div class="sidenav-menu-heading">STATISTIQUES</div>
                <!-- Sidenav Accordion (Dashboard) -->
                <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    Accueil
                </a>
                <!-- Sidenav Heading (Custom) -->
                <div class="sidenav-menu-heading">DONNEES</div>

                <!-- Sidenav Accordion (Pages) -->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="nav-link-icon"><i data-feather="map-pin"></i></div>
                    Géo localisation
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ Request::is('villages*') || Request::is('cantons*') || Request::is('communes*') || Request::is('prefectures*') || Request::is('regions*') ? 'show' : '' }}" id="collapsePages" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                        @can('village-index')
                            <a class="nav-link {{ Request::routeIs('villages.index') ? 'active' : '' }}" href="{{ route('villages.index') }}">Villages</a>
                        @endcan
                        @can('canton-index')
                            <a class="nav-link {{ Request::routeIs('cantons.index') ? 'active' : '' }}" href="{{ route('cantons.index') }}">Cantons</a>
                        @endcan
                        @can('commune-index')
                            <a class="nav-link {{ Request::routeIs('communes.index') ? 'active' : '' }}" href="{{ route('communes.index') }}">Communes</a>
                        @endcan
                        @can('prefecture-index')
                            <a class="nav-link {{ Request::routeIs('prefectures.index') ? 'active' : '' }}" href="{{ route('prefectures.index') }}">Préfectures</a>
                        @endcan
                        @can('region-index')
                            <a class="nav-link {{ Request::routeIs('regions.index') ? 'active' : '' }}" href="{{ route('regions.index') }}">Régions</a>
                        @endcan
                    </nav>
                </div>

                @can('financement-index')
                    <a class="nav-link {{ Request::routeIs('financements.index') ? 'active' : '' }}" href="{{ route('financements.index') }}">
                        <div class="nav-link-icon"><i class="fas fa-money-check-alt"></i></div>
                        Financement
                    </a>
                @endcan
                @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie'))
                    <!-- Sidenav Accordion (Pages) -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#FSB_NOVISSI" aria-expanded="false" aria-controls="collapsePages">
                        <div class="nav-link-icon">
                            <i class="fa fa-balance-scale"  aria-hidden="true"></i>
                            <!-- <i data-feather="balanced"></i> -->
                        </div>
                        Filets sociaux
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ Request::is('menages*') || Request::is('beneficiaires*') || Request::is('fsb_syntheses*') ? 'show' : '' }}" id="FSB_NOVISSI" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                            @can('menage-index')
                                <a class="nav-link {{ Request::routeIs('menages.index') ? 'active' : '' }}" href="{{ route('menages.index') }}">Ménages</a>
                            @endcan
                            @can('beneficiaire-index')
                                <a class="nav-link {{ Request::routeIs('beneficiaires.index') ? 'active' : '' }}" href="{{ route('beneficiaires.index') }}">Bénéficiaires</a>
                            @endcan
                            @can('etat_paiements')
                                <a class="nav-link {{ Request::routeIs('beneficiaires.etat_paiements') ? 'active' : '' }}" href="{{ route('beneficiaires.etat_paiements') }}">Etat de paiements</a>
                            @endcan

                            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#appsCollapseKnowledgeBase" aria-expanded="false" aria-controls="appsCollapseKnowledgeBase">
                                    Outils PTF (Synthèse)
                                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse {{ Request::is('fsb_syntheses*') ? 'show' : '' }}" id="appsCollapseKnowledgeBase" data-bs-parent="#accordionSidenavAppsMenu">
                                <nav class="sidenav-menu-nested nav">
                                    @can('synthese_paiement_par_beneficiaire')
                                        <a class="nav-link {{ Request::routeIs('fsb_syntheses.par_beneficiaire') ? 'active' : '' }}" href="{{ route('fsb_syntheses.par_beneficiaire') }}">Par Bénéficiaires</a>
                                    @endcan
                                    @can('synthese_paiement_par_village')
                                        <a class="nav-link {{ Request::routeIs('fsb_syntheses.par_village') ? 'active' : '' }}" href="{{ route('fsb_syntheses.par_village') }}">Par village</a>
                                    @endcan
                                    @can('inscrit-index')
                                        <a class="nav-link" href="#">Par canton</a>
                                    @endcan
                                    @can('repas-index')
                                        <a class="nav-link" href="#">Par commune</a>
                                    @endcan
                                    @can('visite-index')
                                        <a class="nav-link" href="#">Par péfecture</a>
                                    @endcan
                                    @can('visite-index')
                                        <a class="nav-link" href="#">Par région</a>
                                    @endcan
                                </nav>
                            </div>
                        </nav>
                    </div>
                @endif

                <!-- Sidenav Accordion (Applications) -->
                @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Aadb') || Auth::user()->hasRole('Assistant') || Auth::user()->hasRole('Hierachie'))
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseApps" aria-expanded="false" aria-controls="collapseApps">
                        <div class="nav-link-icon"><i data-feather="layers"></i></div>
                        Cantine scolaire
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                
                    <div class="collapse {{ Request::is('ecoles*') || Request::is('classes*') || Request::is('menus*') || Request::is('inscrits*') || Request::is('repas*') || Request::is('syntheses*') || Request::is('visites*') ? 'show' : '' }}" id="collapseApps" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavAppsMenu">
                            <!-- Nested Sidenav Accordion (Apps -> Knowledge Base) -->
                            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#appsCollapseKnowledgeBase" aria-expanded="false" aria-controls="appsCollapseKnowledgeBase">
                                Données de base
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse {{ Request::is('ecoles*') || Request::is('repas*') || Request::is('classes*') || Request::is('inscrits*') || Request::is('visites*') ? 'show' : '' }}" id="appsCollapseKnowledgeBase" data-bs-parent="#accordionSidenavAppsMenu">
                                <nav class="sidenav-menu-nested nav">
                                    @can('ecole-index')
                                        <a class="nav-link {{ Request::routeIs('ecoles.index') ? 'active' : '' }}" href="{{ route('ecoles.index') }}">Cantines</a>
                                    @endcan
                                    @can('classe-index')
                                        <a class="nav-link {{ Request::routeIs('classes.index') ? 'active' : '' }}" href="{{ route('classes.index') }}">Ecoles</a>
                                    @endcan
                                    @can('inscrit-index')
                                        <a class="nav-link {{ Request::routeIs('inscrits.index') ? 'active' : '' }}" href="{{ route('inscrits.index') }}">Inscrits</a>
                                    @endcan
                                    @can('repas-index')
                                        <a class="nav-link {{ Request::routeIs('repas.index') ? 'active' : '' }}" href="{{ route('repas.index') }}">Repas</a>
                                    @endcan
                                    @can('visite-index')
                                        <a class="nav-link {{ Request::routeIs('visites.index') ? 'active' : '' }}" href="{{ route('visites.index') }}">Visites</a>
                                    @endcan
                                </nav>
                            </div>

                            @can('synthese-arf')
                                <a class="nav-link {{ Request::routeIs('repas.synthese_arf') ? 'active' : '' }}" href="{{ route('repas.synthese_arf') }}">
                                    Rapport détaillé
                                </a>
                            @endcan

                            <!-- Nested Sidenav Accordion (Apps -> User Management) -->
                            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#appsCollapseUserManagement" aria-expanded="false" aria-controls="appsCollapseUserManagement">
                                Synthèse detaillée
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse {{ Request::is('syntheses*') ? 'show' : '' }}" id="appsCollapseUserManagement" data-bs-parent="#accordionSidenavAppsMenu">
                                <nav class="sidenav-menu-nested nav">
                                    @can('synthese-ecole')
                                        <a class="nav-link {{ Request::routeIs('syntheses.synthese_ecole') ? 'active' : '' }}" href="{{ route('syntheses.synthese_ecole') }}" data-key="t-synthese_ecole">Par cantine</a>
                                    @endcan
                                    @can('synthese-canton')
                                        <a class="nav-link {{ Request::routeIs('syntheses.synthese_canton') ? 'active' : '' }}" href="{{ route('syntheses.synthese_canton') }}" data-key="t-synthese_canton">Par canton</a>
                                    @endcan
                                    @can('synthese-commune')
                                        <a class="nav-link {{ Request::routeIs('syntheses.synthese_commune') ? 'active' : '' }}" href="{{ route('syntheses.synthese_commune') }}" data-key="t-synthese_commune">Par commune</a>
                                    @endcan
                                    @can('synthese-prefecture')
                                        <a class="nav-link {{ Request::routeIs('syntheses.synthese_prefecture') ? 'active' : '' }}" href="{{ route('syntheses.synthese_prefecture') }}" data-key="t-synthese_prefecture">Par préfecture</a>
                                    @endcan
                                    @can('synthese-region')
                                        <a class="nav-link {{ Request::routeIs('syntheses.synthese_region') ? 'active' : '' }}" href="{{ route('syntheses.synthese_region') }}" data-key="t-synthese_region">Par région</a>
                                    @endcan
                                    @can('synthese-comptabilite')
                                        <a class="nav-link {{ Request::routeIs('syntheses.synthese_comptabilite') ? 'active' : '' }}" href="{{ route('syntheses.synthese_comptabilite') }}" data-key="t-synthese_comptabilite">Comptabilité</a>
                                    @endcan
                                </nav>
                            </div>
                            <!-- Nested Sidenav Accordion (Apps -> Posts Management) -->
                            @can('menu-index')
                                <a class="nav-link {{ Request::routeIs('menus.index') ? 'active' : '' }}" href="{{ route('menus.index') }}" data-key="t-menu">Prix unitaire du plat</a>
                            @endcan
                        </nav>
                    </div>
                @endif

                @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Infra') || Auth::user()->hasRole('Hierachie'))
                    <!-- Sidenav Accordion (Flows) -->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseFlows" aria-expanded="false" aria-controls="collapseFlows">
                        <div class="nav-link-icon"><i data-feather="box"></i></div>
                            Infrastructures
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ Request::is('sites*') || Request::is('entreprises*') || Request::is('locations*') || Request::is('ouvrages*') || Request::is('contrats*') || Request::is('suivis*') || Request::is('typeouvrages*') || Request::is('demandejours*') || Request::is('demandes*') ? 'show' : '' }}" id="collapseFlows" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            @can('entreprise-index')
                                <a class="nav-link {{ Request::routeIs('entreprises.index') ? 'active' : '' }}" href="{{ route('entreprises.index') }}" data-key="t-entreprise">Entreprises</a>
                            @endcan
                            @can('typeouvrage-index')
                                <a class="nav-link {{ Request::routeIs('typeouvrages.index') ? 'active' : '' }}" href="{{ route('typeouvrages.index') }}" data-key="t-typeouvrages">Type d'ouvrages</a>
                            @endcan
                            @can('site-index')
                                <a class="nav-link {{ Request::routeIs('sites.index') ? 'active' : '' }}" href="{{ route('sites.index') }}" data-key="t-sites">Sites</a>
                            @endcan
                            @can('ouvrage-index')
                                <a class="nav-link {{ Request::routeIs('ouvrages.index') ? 'active' : '' }}" href="{{ route('ouvrages.index') }}" data-key="t-ouvrages">Ouvrages</a>
                            @endcan
                            @can('ouvrage-index')    
                                <a class="nav-link {{ Request::routeIs('contrats.index') ? 'active' : '' }}" href="{{ route('contrats.index') }}" data-key="t-contrats">Contrats</a>
                            @endcan
                            @can('suivi-index')
                                <a class="nav-link {{ Request::routeIs('suivis.index') ? 'active' : '' }}" href="{{ route('suivis.index') }}" data-key="t-suivis">Suivis</a>
                            @endcan
                            @can('suivi-galerie')
                                <a class="nav-link {{ Request::routeIs('suivis.galerie') ? 'active' : '' }}" href="{{ route('suivis.galerie') }}" data-key="t-galerie">Galérie photos</a>
                            @endcan
                            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#appsCollapseKnowledgeBasee" aria-expanded="false" aria-controls="appsCollapseKnowledgeBasee">
                                Liste de demandes
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse {{ Request::is('demandes*') || Request::is('demandejours*') ? 'show' : '' }}" id="appsCollapseKnowledgeBasee" data-bs-parent="#accordionSidenavAppsMenu">
                                <nav class="sidenav-menu-nested nav">
                                    <!-- @can('location-index') -->
                                    <a class="nav-link {{ Request::routeIs('demandes.index') ? 'active' : '' }}" href="{{ route('demandes.index') }}">Suspension</a>
                                    <!-- @endcan       -->
                                    <a class="nav-link {{ Request::routeIs('demandejours.index') ? 'active' : '' }}" href="{{ route('demandejours.index') }}">Jours de plus</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#appsCollapseKnowledgeBase" aria-expanded="false" aria-controls="appsCollapseKnowledgeBase">
                                GPS des ouvrages
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse {{ Request::is('locations*') ? 'show' : '' }}" id="appsCollapseKnowledgeBase" data-bs-parent="#accordionSidenavAppsMenu">
                                <nav class="sidenav-menu-nested nav">
                                    @can('location-index')
                                        <a class="nav-link {{ Request::routeIs('locations.index') ? 'active' : '' }}" href="{{ route('locations.index') }}">Liste des GPS</a>
                                    @endcan      
                                    <a class="nav-link {{ Request::is('locations/cartographie_des_points') ? 'active' : '' }}" href="{{ route('locations.cartographie') }}">
                                        Voir sur la carte
                                    </a>
                                </nav>
                            </div>
                        </nav>
                    </div>
                @endif

                @hasrole('Admin')
                    <!-- Sidenav Heading (UI Toolkit) -->
                    <div class="sidenav-menu-heading">Gestion utilisateurs</div>
                    <!-- Sidenav Accordion (Layout) -->
                    <a class="nav-link {{ Request::routeIs('roles.index') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                        <div class="nav-link-icon"><i data-feather="user"></i></div>
                        Liste rôles
                    </a>
                    <a class="nav-link {{ Request::routeIs('permissions.index') ? 'active' : '' }}" href="{{ route('permissions.index') }}">
                        <div class="nav-link-icon"><i data-feather="lock"></i></div>
                        Liste permissions
                    </a>
                    <a class="nav-link {{ Request::routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <div class="nav-link-icon"><i data-feather="users"></i></div>
                        Liste utilisateurs
                    </a>
                @endhasrole

                <div class="sidenav-menu-heading">Déconnexion</div>
                <a class="nav-link" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                    <div class="nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                    Déconnectez-vous ?
                </a>
            </div>
        </div>

        <!-- Sidenav Footer -->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle"></div>
                <div class="sidenav-footer-title"></div>
            </div>
        </div>
    </nav>
</div>