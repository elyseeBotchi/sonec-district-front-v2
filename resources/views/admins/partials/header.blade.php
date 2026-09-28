<header class="topbar">
    <div class="v2-topbar-inner">
        <div class="v2-topbar-greeting">
            <p class="v2-topbar-greeting__title">
                @hasSection('page-title')
                    @yield('page-title')
                @else
                    Bienvenue, {{ AuthConnect()['firstname'] ?? '' }}
                @endif
            </p>
            <p class="v2-topbar-greeting__subtitle">@yield('page-subtitle', "Ravi de vous revoir.")</p>
        </div>

        <div class="v2-topbar-actions">
            

            {{-- <button type="button" class="v2-topbar-icon-btn" aria-label="Notifications">
                <i data-feather="bell"></i>
                <span class="v2-topbar-icon-btn__dot"></span>
            </button> --}}

            <div class="v2-user-menu-wrap">
                <a href="javascript:void(0)" id="v2-user-menu-toggle" class="v2-topbar-avatar"
                   role="button" aria-haspopup="true" aria-expanded="false">
                    <img
                        @isset(AuthConnect()['avatar'])
                            src="{{ \Illuminate\Support\Facades\Storage::url('/users/avatar/'.AuthConnect()['uuid'].'/'.AuthConnect()['avatar']) }}"
                        @else
                            @isset(AuthConnect()['civility'])
                                src="{{ asset(AuthConnect()['civility'] == 'm' ? 'backoffice/man.png' : 'backoffice/woman.png') }}"
                            @else
                                src="{{ asset('backoffice/man.png') }}"
                            @endisset
                        @endisset
                        alt="user">
                </a>
                <div class="v2-user-menu" id="v2-user-menu">
                    <a class="v2-user-menu__item" href="{{ route('panel.securite.compte') }}">
                        <i data-feather="user" class="svg-icon mr-2 ml-1"></i>
                        Mon profile
                    </a>
                    <div class="v2-user-menu__divider"></div>
                    <a class="v2-user-menu__item" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                        Déconnexion

                        <form id="logout-form" action="{{ route('panel.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
