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
               
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Applications</span></li>

                
                <li class="sidebar-item  {{ request()->is('panel/activity/agents/*') ? 'selected' : '' }}">
                    <a class="sidebar-link" href="{{ route('panel.autorisations.activity.agents.index') }}" aria-expanded="false">
                        <i data-feather="activity" class="feather-icon"></i>
                        <span class="hide-menu">
                            Activités
                        </span>
                    </a>
                </li>
               
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

                    {{--
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="#" aria-expanded="false">
                            <i data-feather="tag" class="feather-icon"></i>
                            <span class="hide-menu">
                            Utilisateurs
                            </span>
                        </a>
                    </li> --}}

                @isset($lock)
                   
                    <li class="sidebar-item">
                        <a class="sidebar-link sidebar-link" href="app-calendar.html" aria-expanded="false">
                            <i data-feather="calendar" class="feather-icon"></i>
                            <span class="hide-menu">Reclamation</span>
                        </a>
                    </li>
                @endisset

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Configurations</span></li>
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
                        {{-- <li class="sidebar-item">
                            <a href="form-checkbox-radio.html" class="sidebar-link">
                                <span  class="hide-menu">
                                    Checkboxes & Radios
                                </span>
                            </a>
                        </li> --}}
                    </ul>
                </li>

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

                
                <li class="sidebar-item {{ request()->is('panel/agents/*') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link {{ request()->is('panel/agents/*') ? 'active' : '' }}" href="{{ route('panel.autorisations.agents.index') }}" aria-expanded="false">
                        <i class="feather-icon fas fa-user-secret"></i>
                        <span  class="hide-menu">
                            Agent
                        </span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('panel/roles/*') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link {{ request()->is('panel/roles/*') ? 'active' : '' }}" href="{{ route('panel.autorisations.roles.index') }}" aria-expanded="false">
                        <i  class="feather-icon fas fa-tasks"></i>
                        <span class="hide-menu">
                            Roles
                        </span>
                    </a>
                </li>


                <li class="sidebar-item {{ request()->is('panel/modules/*') ? 'selected' : '' }}">
                        <a class="sidebar-link sidebar-link {{ request()->is('panel/autorisations/modules/*') ? 'active' : '' }}" href="{{ route('panel.autorisations.modules.index') }}" aria-expanded="false">
                        <i class="feather-icon fas fa-cogs"></i>
                        <span class="hide-menu">
                            Modules
                        </span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap">
                    <span class="hide-menu">Extra</span>
                </li>
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
