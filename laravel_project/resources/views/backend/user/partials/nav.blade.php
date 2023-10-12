<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow padding-sm-0">
    <div class="detail one padding-0">
        @if(Auth::user()->isCoach() && (Auth::user()->categories()->count() > 0) && isset(Auth::user()->hourly_rate_type) && isset(Auth::user()->experience_year) && isset(Auth::user() ->preferred_pronouns))
            <a class="dropdown-item" href="{{ route('page.profile', encrypt(Auth::user()->id)) }}">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    {{ __('backend.nav.profile') }}
            </a>
        @endif
    </div>
    <div class="detail one padding-0">
        <a class="dropdown-item  web-hide-sm" href="{{ route('page.home') }}">
            <i class="fas fa-columns fa-sm fa-fw mr-2 text-gray-400"></i>
            {{ __('backend.nav.website') }}
        </a>
    </div>

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}<br><span><b>{{ Auth::user()->role->name }}</b></span></span>
                @if(Auth::user()->user_image)
                    <img class="img-profile rounded-circle" src="{{ Storage::disk('public')->url('user/'. Auth::user()->user_image) }}">
                @else
                    {{-- <img class="img-profile rounded-circle" src="{{ asset('backend/images/placeholder/profile-' . intval(Auth::user()->id % 10) . '.webp') }}"> --}}
                    <img class="img-profile rounded-circle" src="{{ asset('backend/images/placeholder/profile_default.webp') }}">
                @endif
            </a>
            
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                @if(Auth::user()->isCoach() && (Auth::user()->categories()->count() > 0) && isset(Auth::user()->hourly_rate_type) && isset(Auth::user()->experience_year) && isset(Auth::user() ->preferred_pronouns))
                    <a class="dropdown-item" href="{{ route('page.profile', encrypt(Auth::user()->id)) }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        {{ __('backend.nav.profile') }}
                    </a>
                @endif
                <a class="dropdown-item" href="{{ route('page.home') }}">
                    <i class="fas fa-columns fa-sm fa-fw mr-2 text-gray-400"></i>
                    {{ __('backend.nav.website') }}
                </a>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    {{ __('auth.logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                </form>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->
