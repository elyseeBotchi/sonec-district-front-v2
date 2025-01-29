@php $entiteNav = Entity_Customer(); @endphp
<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                {{-- <li class="sidebar-item {{ request()->is('customer/home') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link" href="{{ route('customer.home') }}" aria-expanded="false">
                        <i data-feather="bar-chart" class="feather-icon"></i>
                        <span class="hide-menu">Tableau de bord</span>
                    </a>
                </li> --}}
               
                @if(!empty($entiteNav))
                    <li class="sidebar-item {{ request()->is('customer/services/taxe/*') ? 'selected' : '' }} {{ request()->is('customer/services/facturation/taxe/*') ? 'selected' : '' }} ">
                        <a href="{{ route('customer.entities.taxe', ['slug' => $entiteNav['slug'], 'target' => $entiteNav['uuid']]) }}" class="sidebar-link">
                            <i data-feather="tag" class="feather-icon"></i>
                            <span class="hide-menu">
                                {{ isset($entiteNav['front_name']) ? $entiteNav['front_name'] : '' }}
                            </span>
                        </a>
                    </li>
                    
                    <li class="sidebar-item {{ request()->is('customer/services/cheque/*') ? 'selected' : '' }} {{ request()->is('customer/services/cheque/detail/*') ? 'selected' : '' }} ">
                        <a href="{{ route('customer.entities.taxe.cheque', ['target' => $entiteNav['uuid']]) }}" class="sidebar-link">
                            <i data-feather="tag" class="feather-icon"></i>
                            <span class="hide-menu">
                                Chèques
                            </span>
                        </a>
                    </li>
                @endif
            
                              
                <li class="sidebar-item {{ request()->is('customer/service/comment/payer/*') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link {{ request()->is('customer/service/comment/payer/*') ? 'active' : '' }}" href="{{ route('customer.service.comment.payer') }}" aria-expanded="false">
                        <i data-feather="info" class="feather-icon"></i>
                        <span class="hide-menu">
                            Comment payer
                        </span>
                    </a>
                </li>


               
                @isset($entitesNav)
                    <li class="list-divider"></li>
                    <li class="nav-small-cap"><span class="hide-menu">Produits</span></li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                            <i data-feather="box" class="feather-icon"></i>
                            <span class="hide-menu">TAXES </span>
                        </a>
                        <ul aria-expanded="false" class="collapse  first-level base-level-line">
                        
                                @if($entitesNav != "")
                                    @forelse($entitesNav as $val)
                                        <li class="sidebar-item">
                                            <a href="{{  $val['name'] ?? '' }}" class="sidebar-link ">
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
                
                @endisset
                
              
                <li class="sidebar-item {{ request()->is('customer/securite/*') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link {{ request()->is('customer/securite/*') ? 'active' : '' }}" href="{{ route('customer.securite.compte') }}" aria-expanded="false">
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


                        <form id="logout-form2" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>

                </li>
            </ul>
        </nav>
    </div>
</aside>
