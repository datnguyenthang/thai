<!--Main Navigation-->
<header>
  <!-- Sidebar -->
  {{--
  <nav id="sidebarMenu" class="collapse d-lg-block sidebar bg-white">
    <div class="position-sticky">
      <div class="list-group list-group-flush mx-3 mt-4">
        <a href="{{ route('moderatorDashboard') }}" 
          class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'moderatorDashboard' ? 'active' : '' }}" 
          aria-current="true">
          <i class="fas fa-tachometer-alt fa-fw me-3"></i>
          <span>{{ trans('backend.dashboard') }}</span>
        </a>

        <a href="{{ route('moderatorOrderlist') }}" 
        class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'moderatorOrderlist' ? 'active' : '' }}" 
        aria-current="true">
          <i class="fas fa-chart-bar fa-fw me-3"></i>
          <span>{{ trans('backend.orderlist') }}</span>
        </a>

        <a href="{{ route('moderatorOrder') }}" 
        class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'moderatorOrder' ? 'active' : '' }}" 
        aria-current="true">
          <i class="fas fa-chart-bar fa-fw me-3"></i>
          <span>{{ trans('backend.order') }}</span>
        </a>

        <a href="{{ route('managerListUser') }}" 
          class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'user' ? 'active' : '' }}">
          <i class="fas fa-chart-area fa-fw me-3"></i>
          <span>User</span>
        </a>

        <a href="{{ route('managerLocation') }}" 
           class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'location' ? 'active' : '' }}">
           <i class="fas fa-lock fa-fw me-3"></i>
           <span>Location</span>
        </a>

        <a href="{{ route('managerListRide') }}" 
           class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'ride' ? 'active' : '' }}">
           <i class="fas fa-chart-line fa-fw me-3"></i>
           <span>Ride</span>
        </a>
        
        <a href="{{ route('managerAgent') }}" 
          class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'agent' ? 'active' : '' }}">
          <i class="fas fa-chart-pie fa-fw me-3"></i>
          <span>Agent</span>
        </a>

        <a href="#" class="list-group-item list-group-item-action py-2 ripple">
          <i class="fas fa-chart-bar fa-fw me-3"></i>
          <span>Orders</span>
        </a>

        <a href="#" class="list-group-item list-group-item-action py-2 ripple">
          <i class="fas fa-globe fa-fw me-3"></i>
          <span>International</span>
        </a>

        <a href="#" class="list-group-item list-group-item-action py-2 ripple">
          <i class="fas fa-building fa-fw me-3"></i>
          <span>Partners</span>
        </a>

        <a href="#" class="list-group-item list-group-item-action py-2 ripple">
          <i class="fas fa-calendar fa-fw me-3"></i>
          <span>Calendar</span>
        </a>

        <a href="#" class="list-group-item list-group-item-action py-2 ripple">
          <i class="fas fa-users fa-fw me-3"></i>
          <span>Users</span>
        </a>

        <a href="#" class="list-group-item list-group-item-action py-2 ripple">
          <i class="fas fa-money-bill fa-fw me-3"></i>
          <span>Sales</span>
        </a>
      </div>
    </div>
  </nav>
  --}}
  <!-- Sidebar -->

  <!-- Navbar -->
  <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
    <!-- Container wrapper -->
    <div class="container-fluid">
      <!-- Toggle button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Brand -->
      <a class="navbar-brand" href="{{ route('moderatorDashboard') }}">
        <img src="/img/logo.png" height="25" alt="MDB Logo"
          loading="lazy" />
      </a>

      <!-- Left links -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="{{ route('moderatorDashboard') }}" 
              class="nav-link list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'moderatorDashboard' ? 'active' : '' }}" 
              aria-current="true">
                <span>{{ trans('backend.dashboard') }}</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('moderatorOrderlist') }}" 
              class="nav-link list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'moderatorOrderlist' ? 'active' : '' }}" 
              aria-current="true">
                <span>{{ trans('backend.orderlist') }}</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('moderatorOrder') }}" 
            class="nav-link list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'moderatorOrder' ? 'active' : '' }}" 
            aria-current="true">
              <span>{{ trans('backend.order') }}</span>
            </a>
          </li>
        </ul>
      </div>
  
      <!-- Right links -->
      <div class="d-flex align-items-center">
        <!-- Notification dropdown
        <div class="dropdown">
            <a class="nav-link link-secondary me-3 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink"
              role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-bell"></i>
              <span class="badge rounded-pill badge-notification bg-danger">1</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
              <li>
                <a class="dropdown-item" href="#">Some news</a>
              </li>
              <li>
                <a class="dropdown-item" href="#">Another news</a>
              </li>
            </ul>
        </div>
         -->

        <!-- Icon dropdown -->
        @include('lang')

        <!-- User menu -->
        <div class="dropdown">
          <a class="nav-link me-3 dropdown-toggle hidden-arrow" href="#"
            id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::user()->name }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            <li>
              <a class="dropdown-item" href="/user/profile">Profile</a>
            </li>
            <li>
              <a class="dropdown-item" href="/user/edit">Edit</a>
            </li>
            <li>
              <a class="dropdown-item" href="/logout">Logout</a>
            </li>
          </ul>
        </div>
      </div>
      <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
  </nav>
  <!-- Navbar -->
</header>
