<!--Main Navigation-->
<header>
  <!-- Sidebar -->
  <nav id="sidebarMenu" class="collapse d-lg-block sidebar bg-white">
    <div class="position-sticky">
      <div class="list-group list-group-flush mx-3 mt-4">

        <a href="{{ route('listMenu') }}" 
           class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'listMenu' ? 'active' : '' }}">
           <i class="fas fa-bars fa-fw me-3"></i>
           <span>{{ trans('backend.menu') }}</span>
        </a>
        
        <a href="{{ route('pageList') }}" 
           class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'pageList' ? 'active' : '' }}">
           <i class="fas fa-newspaper fa-fw me-3"></i>
           <span>{{ trans('backend.cms') }}</span>
        </a>

        <a href="{{ route('customizeHomepage') }}" 
           class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'customizeHomepage' ? 'active' : '' }}">
           <i class="fas fa-tv fa-fw me-3"></i>
           <span>{{ trans('backend.appearance') }}</span>
        </a>

        <a href="{{ route('settingSeo') }}" 
           class="list-group-item list-group-item-action py-2 ripple {{ Route::currentRouteName() == 'settingSeo' ? 'active' : '' }}">
           <i class="fas fa-icons fa-fw me-3"></i>
           <span>{{ trans('backend.seo') }}</span>
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
      <a class="navbar-brand" href="{{ route('pageList') }}">
        <img src="/img/logo.png" height="25" alt="MDB Logo"
          loading="lazy" />
      </a>
      

      </div>
  
      <!-- Right links -->
      <div class="d-flex align-items-center">
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
