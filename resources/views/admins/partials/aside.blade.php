@php $entitesNav = Entities(); @endphp
<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="{{ route('panel.home') }}" aria-expanded="false">
                        <i data-feather="bar-chart" class="feather-icon"></i>
                        <span class="hide-menu">Tableau de bord</span>
                    </a>
                </li>

                {{-- <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="{{ route('panel.home') }}" aria-expanded="false">
                        <i data-feather="home" class="feather-icon"></i>
                        <span class="hide-menu">Accueil</span>
                    </a>
                </li> --}}
               
                
                @isset($entitesNav[0]['uuid'])
                    <li class="sidebar-item  {{ request()->is('panel/services/taxes/detail/*') ? 'selected' : '' }}" > 
                        <a class="sidebar-link sidebar-link" href="{{ route('panel.autorisations.services.rdv',['uuid' => $entitesNav[0]['uuid']]) }}" aria-expanded="false">
                            <i data-feather="calendar" class="feather-icon"></i>
                            <span class="hide-menu">Rendez-vous</span>
                        </a>
                    </li>
                @endisset 

        
                
                @if(CanPermission('activites_voir_les_activites'))
                
                    <li class="sidebar-item  {{ request()->is('panel/activity/agents/*') ? 'selected' : '' }}">
                        <a class="sidebar-link" href="{{ route('panel.autorisations.activity.agents.index') }}" aria-expanded="false">
                            <i data-feather="activity" class="feather-icon"></i>
                            <span class="hide-menu">
                                Activités
                            </span>
                        </a>
                    </li>
               @endif

                @if(CanPermission('entites_voir_les_donnees_de_lentite'))
                    @if($entitesNav != "")
                        @forelse($entitesNav as $val)
                            <li class="sidebar-item">
                                <a href="{{ route('panel.autorisations.services.show.data',['uuid' =>$val['uuid']]) }}" class="sidebar-link">
                                    <span class="hide-menu">
                                    {{  $val['name'] ?? '' }}
                                    </span>
                                </a>
                            </li>
                        @empty
                        @endforelse
                    @endif
                @endif 

                @if(CanPermission('statistique_voir_le_module_statistique'))
                    <li class="list-divider"></li>
                    <li class="nav-small-cap"><span class="hide-menu">Statistique</span></li> {{-- --}}
                
                    @if($entitesNav != "")
                        @forelse($entitesNav as $val)
                        @if(CanPermission('statistique_voir_les_statistique_par_paiement'))
                        <li class="sidebar-item">
                            <a href="{{ route('panel.autorisations.statistique.show.data',['uuid' =>$val['uuid'], 'type_stat' => 'paiement']) }}" class="sidebar-link">
                                <span class="hide-menu">
                                PAIEMENT
                                </span>
                            </a>
                        </li>
                        @endif 
                        
                        @if(CanPermission('statistique_voir_les_statistiques_par_operateur'))
                            <li class="sidebar-item">
                                <a href="{{ route('panel.autorisations.statistique.show.data',['uuid' =>$val['uuid'], 'type_stat' => 'operateur']) }}" class="sidebar-link">
                                    <span class="hide-menu">
                                    PAR OPERATEUR
                                    </span>
                                </a>
                            </li>
                        @endif

                        @if(CanPermission('statistique_voir_les_statistiques_par_rubrique'))
                            <li class="sidebar-item">
                                <a href="{{ route('panel.autorisations.statistique.show.data',['uuid' =>$val['uuid'], 'type_stat' => 'rubrique']) }}" class="sidebar-link">
                                    <span class="hide-menu">
                                    PAR RUBRIQUE
                                    </span>
                                </a>
                            </li>

                        @endif

                        @if(CanPermission('statistique_voir_les_statistiques_par_periode'))
                            <li class="sidebar-item">
                                <a href="{{ route('panel.autorisations.statistique.show.data',['uuid' =>$val['uuid'], 'type_stat' => 'periode']) }}" class="sidebar-link">
                                    <span class="hide-menu">
                                    PAR PERIODE
                                    </span>
                                </a>
                            </li>
                        @endif

                        
                    
                            <li class="list-divider">OPERATIONS</li>
                        @if(CanPermission('statistique_voir_les_statistiques_par_rendez_vous'))
                            <li class="sidebar-item">
                                <a href="{{ route('panel.autorisations.statistique.show.data',['uuid' =>$val['uuid'], 'type_stat' => 'rdv']) }}" class="sidebar-link">
                                    <span class="hide-menu">
                                    PAR RENDEZ-VOUS
                                    </span>
                                </a>
                            </li>
                        @endif

                        @if(CanPermission('statistique_voir_les_statistiques_par_agent_validateur'))
                            <li class="sidebar-item">
                                <a href="{{ route('panel.autorisations.statistique.show.data',['uuid' =>$val['uuid'], 'type_stat' => 'agent_validateur']) }}" class="sidebar-link">
                                    <span class="hide-menu">
                                        PAR AGENT VALIDATEUR
                                    </span>
                                </a>
                            </li>
                        @endif

                        @if(CanPermission('statistique_voir_les_statistiques_par_validations_par_jour'))
                            <li class="sidebar-item">
                                <a href="{{ route('panel.autorisations.statistique.show.data',['uuid' =>$val['uuid'], 'type_stat' => 'validation_jour']) }}" class="sidebar-link">
                                    <span class="hide-menu">
                                        VALIDATION PAR JOUR
                                    </span>
                                </a>
                            </li>
                        @endif

                        @empty
                        @endforelse
                    @endif

                @endif


                

                
                @if(CanPermission('configurations_voir_le_bloc_des_configurations'))
                    <li class="list-divider"></li>
                    <li class="nav-small-cap"><span class="hide-menu">Configurations</span></li>
                    @if(CanPermission('entites_configurer_une_entite'))
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <i data-feather="box" class="feather-icon"></i>
                                <span class="hide-menu">Entités </span>
                            </a>
                            <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                <li class="sidebar-item">
                                    <a href="{{ route('panel.autorisations.entite.index') }}" class="sidebar-link">
                                        <span class="hide-menu">
                                            Liste  
                                        </span>
                                    </a>
                                </li>

                                @if($entitesNav != "")
                                    @forelse($entitesNav as $val)
                                        <li class="sidebar-item">
                                            <a href="{{ route('panel.autorisations.entite.show',['uuid' =>$val['uuid']]) }}" class="sidebar-link">
                                                <span class="hide-menu">
                                                {{  $val['name'] ?? '' }}
                                                </span>
                                            </a>
                                        </li>
                                    @empty
                                    @endforelse

                                @endif
                            
                            </ul>
                        </li>
                    @endif

                    @isset($lock)
                        @if(CanPermission('gabaris_voir_longlet_gabaris'))
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <i class="feather-icon fas fa-book"></i>
                                    <span class="hide-menu">Gabaris </span>
                                </a>
                                <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                    @if($entitesNav != "")
                                        @forelse($entitesNav as $val)
                                            <li class="sidebar-item">
                                                <a href="{{ route('panel.autorisations.entite.gabari',['uuid' =>$val['uuid']]) }}" class="sidebar-link">
                                                    <span class="hide-menu">
                                                    {{  $val['name'] ?? '' }}
                                                    </span>
                                                </a>
                                            </li>
                                        @empty
                                        @endforelse

                                    @endif
                                </ul>
                            </li>
                        @endif 
                    @endisset 

                    @if(CanPermission('collaborateurs_voir_longlet_collaborateur'))
                        <li class="list-divider"></li>
                        <li class="nav-small-cap"><span class="hide-menu">Authentication</span></li>
                        
                        <li class="sidebar-item {{ request()->is('panel/collaborateurs/*') ? 'selected' : '' }}">
                            <a class="sidebar-link sidebar-link {{ request()->is('panel/collaborateurs/*') ? 'active' : '' }}" href="{{ route('panel.autorisations.collaborateurs.index') }}" aria-expanded="false">
                                <i class="feather-icon fas fa-users"></i>
                                <span  class="hide-menu">
                                    Collaborateurs
                                </span>
                            </a>
                        </li>
                    @endif

                    @if(CanPermission('agents_voir_longlet_agent'))
                        <li class="sidebar-item {{ request()->is('panel/agents/*') ? 'selected' : '' }}">
                            <a class="sidebar-link sidebar-link {{ request()->is('panel/agents/*') ? 'active' : '' }}" href="{{ route('panel.autorisations.agents.index') }}" aria-expanded="false">
                                <i class="feather-icon fas fa-user-secret"></i>
                                <span  class="hide-menu">
                                    Agent
                                </span>
                            </a>
                        </li>
                    @endif

                    @if(CanPermission('roles_voir_le_module_role'))
                        <li class="sidebar-item {{ request()->is('panel/roles/*') ? 'selected' : '' }}">
                            <a class="sidebar-link sidebar-link {{ request()->is('panel/roles/*') ? 'active' : '' }}" href="{{ route('panel.autorisations.roles.index') }}" aria-expanded="false">
                                <i  class="feather-icon fas fa-tasks"></i>
                                <span class="hide-menu">
                                    Roles
                                </span>
                            </a>
                        </li>
                    @endif


                    @if(CanPermission('module_voir_longlet_module'))
                        <li class="sidebar-item {{ request()->is('panel/modules/*') ? 'selected' : '' }}">
                                <a class="sidebar-link sidebar-link {{ request()->is('panel/autorisations/modules/*') ? 'active' : '' }}" href="{{ route('panel.autorisations.modules.index') }}" aria-expanded="false">
                                <i class="feather-icon fas fa-cogs"></i>
                                <span class="hide-menu">
                                    Modules
                                </span>
                            </a>
                        </li>
                    @endif
                @endif 
                
                <li class="sidebar-item {{ request()->is('panel/securite/*') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link {{ request()->is('panel/securite/*') ? 'active' : '' }}" href="{{ route('panel.securite.compte') }}" aria-expanded="false">
                        <i data-feather="edit-3" class="feather-icon"></i>
                        <span class="hide-menu">
                            Mon compte
                        </span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link"  href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form2').submit();" aria-expanded="false">
                        <i data-feather="log-out" class="feather-icon"></i>
                        <span class="hide-menu">
                            Déconnexion
                        </span>


                        <form id="logout-form2" action="{{ route('panel.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>

                </li>
            </ul>
        </nav>
    </div>
</aside>
