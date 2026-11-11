
<nav class="navbar header-navbar pcoded-header">
    <div class="navbar-wrapper">
        <div class="navbar-logo">
            <a class="mobile-menu" id="mobile-collapse" href="#!">
                <i class="ti-menu"></i>
            </a>
            <div class="mobile-search">
                <div class="header-search">
                    <div class="main-search morphsearch-search">
                        <div class="input-group">
                            <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                            <input type="text" class="form-control" placeholder="Enter Keyword">
                            <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ url('home') }}">
                <img class="img-fluid" src="{{ asset('template/assets/images/logonhkh.png')}}" alt="Theme-Logo" />
            </a>
            <a class="mobile-options">
                <i class="ti-more"></i>
            </a>
        </div>

        <div class="navbar-container container-fluid">
            <ul class="nav-right">
                <li class="user-profile header-notification">
                    <a href="#!">
                        <img src="{{ asset('template/assets/images/avatar-4.jpg')}}" class="img-radius" alt="User-Profile-Image">
                    <span>{{Auth::user()->name}}</span>
                        <i class="ti-angle-down"></i>
                    </a>
                    <ul class="show-notification profile-notification">
                        @if (Auth::user()->status == 1)
                        <li>
                            <a href="{{asset('user')}}">
                                <i class="ti-settings"></i> Settings
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{asset('logoutuser')}}" >
                            <i class="ti-layout-sidebar-left"></i> Logout
                        </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
