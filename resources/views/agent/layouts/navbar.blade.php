<!--Main Navigation-->
<header>
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
      <a class="navbar-brand" href="{{ route('agentOrderlist') }}">
        <img src="/img/logo.png" height="25" alt="MDB Logo"
          loading="lazy" />
      </a>
      
      <!-- Left links -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          {{--
          <li class="nav-item me-3 me-lg-0">
            <a href="{{ route('dashboard') }}" 
              class="nav-link list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" 
              aria-current="true">
                <span>{{ trans('backend.dashboard') }}</span>
            </a>
          </li>
          --}}
          <li class="nav-item me-3 me-lg-0">
            <a href="{{ route('agentOrderlist') }}" 
              class="nav-link list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'agentOrderlist' ? 'active' : '' }}" 
              aria-current="true">
                <span>{{ trans('backend.orderlist') }}</span>
            </a>
          </li>
          <li class="nav-item me-3 me-lg-0">
            <a href="{{ route('agentOrder') }}" 
            class="nav-link list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'agentOrder' ? 'active' : '' }}" 
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

        <!-- User panel -->
        @include('livewire.component.user.userpanel')
      <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
  </nav>
  <!-- Navbar -->
</header>
