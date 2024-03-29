<!--Main Navigation-->
<header>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar bg-white">
      <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
          <a href="{{ route('dashboard') }}" 
            class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" 
            aria-current="true">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i>
            <span>{{ trans('backend.dashboard') }}</span>
          </a>

          @include('components.backend.reportnav')

          <a href="{{ route('viewerOrderlist') }}" 
            class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'viewerOrderlist' ? 'active' : '' }}">
            <i class="fas fa-clipboard-list fa-fw me-3"></i>
            <span>{{ trans('backend.orderlist') }}</span>
          </a>

          <a href="{{ route('viewerRide') }}" 
             class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'viewerRide' ? 'active' : '' }}">
             <i class="fas fa-chart-line fa-fw me-3"></i>
             <span>{{ trans('backend.ride') }}</span>
          </a>
          
          <a href="{{ route('viewerAgent') }}" 
            class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'viewerAgent' ? 'active' : '' }}">
            <i class="fas fa-chart-pie fa-fw me-3"></i>
            <span>{{ trans('backend.agent') }}</span>
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
          <img src="/img/logo.png" height="25" alt="MDB Logo"
            loading="lazy" />
        </a>
  
        <!-- Right links -->
        <ul class="navbar-nav ms-auto d-flex flex-row">
          <!-- Notification dropdown
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
           -->
          <!-- Icon dropdown -->
          @include('lang')
  
          <!-- User panel -->
          @include('livewire.component.user.userpanel')
        </ul>
      </div>
      <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->
  </header>
