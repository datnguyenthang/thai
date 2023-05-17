<!--Main Navigation-->
<header>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar bg-white">
      <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
          <a href="{{ route('managerDashboard') }}" 
            class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" 
            aria-current="true">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i>
            <span>Main dashboard</span>
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
    <!-- Sidebar -->
  
    <!-- Navbar -->
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
      <!-- Container wrapper -->
      <div class="container-fluid">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
          aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i>
        </button>
  
        <!-- Brand -->
        <a class="navbar-brand" href="#">
          <img src="https://mdbcdn.b-cdn.net/img/logo/mdb-transaprent-noshadows.webp" height="25" alt="MDB Logo"
            loading="lazy" />
        </a>
        <!-- Search form -->
        <form class="d-none d-md-flex input-group w-auto my-auto">
          <input autocomplete="off" type="search" class="form-control rounded"
            placeholder='Search (ctrl + "/" to focus)' style="min-width: 225px;" />
          <span class="input-group-text border-0"><i class="fas fa-search"></i></span>
        </form>
  
        <!-- Right links -->
        <ul class="navbar-nav ms-auto d-flex flex-row">
          <!-- Notification dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink"
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
          </li>
  
          <!-- Icon dropdown -->
          @include('lang')
  
          <!-- Avatar -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#"
              id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
              <li>
                <a class="dropdown-item" href="/logout">Logout</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->
  </header>
