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

        <a href="{{ route('managerOrder') }}" 
          class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'managerOrder' ? 'active' : '' }}">
          <i class="fas fa-cart-plus fa-fw me-3"></i>
          <span>{{ trans('backend.order') }}</span>
        </a>

        <a href="{{ route('listUser') }}" 
          class="nav-link list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'listUser' ? 'active' : '' }}" 
          aria-current="true">
            <i class="fas fa-chart-area fa-fw me-3"></i>
            <span>{{ trans('backend.user') }}</span>
        </a>

        <a href="{{ route('importOrder') }}" 
          class="nav-link list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'importOrder' ? 'active' : '' }}" 
          aria-current="true">
          <i class="fas fa-upload fa-fw me-3"></i>
            <span>{{ trans('backend.importorder') }}</span>
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
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Brand -->
      <a class="navbar-brand" href="{{ route('dashboard') }}">
        <img src="/img/logo.png" height="25" alt="MDB Logo"
          loading="lazy" />
      </a>
      
      <!-- Left links -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"></li>
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

        <!-- User panel -->
        @include('livewire.component.user.userpanel')

      </div>
      <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
  </nav>
  <!-- Navbar -->
</header>
