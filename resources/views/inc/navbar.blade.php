<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-brand pl-5">
        <a href="/"><img src="/img/pcaLogo.png"></a>
    </div>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->


        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
                <li class="nav-item">
                <a class="nav-link" href="/about">About</a>
            </li>
                <li class="nav-item">
                <a class="nav-link" href="/event">Events</a>
            </li>
            <li class="nav-item">
        <a class="nav-link" href="/red-sails">Red Sails Festival</a>
          <!-- <a class="dropdown-item" href="#">Get Involved</a> -->
      </li>
                <li class="nav-item">
                <a class="nav-link" href="/news">News</a>
            </li>
                <li class="nav-item">
                <a class="nav-link" href="/contact-us">Contact Us</a>
            </li>
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="/login">{{ __('Login') }}</a>
                </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/register">{{ __('Register') }}</a>
                    </li>
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        My Account<span class="caret"></span>
                    </a>



                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @can('manage-users')
                        <a class="dropdown-item" href="/admin/dashboard">
                         Admin
                     </a>
                     @endcan

                     @can('manage-events')
                        <a class="dropdown-item" href="/events/index">
                         Event Management
                     </a>
                     @endcan

                     @can('manage-news')
                     <a class="dropdown-item" href="/news/index">
                        Manage News
                    </a>
                    @endcan

                    @can('view-policy')
                    <a class="dropdown-item" href="/user/events">
                        Members Home
                    </a>
                    @endcan

                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>





                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>
