<div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    {{-- In work, do what you enjoy. --}}
    <ul class="navbar-nav">
        <li class="nav-item {{ Route::currentRouteName() == 'home' ? 'active' : '' }}">
            <a class="nav-link" href="/">{{ trans('messages.home') }}</a>
        </li>

        <li class="nav-item {{ Route::currentRouteName() == 'timetable' ? 'active' : '' }}">
            <a class="nav-link" href="/timetable">{{ trans('messages.timetable') }}</a>
        </li>

        <li class="nav-item  {{ Route::currentRouteName() == 'login' ? 'active' : '' }}">
            <a class="nav-link" href="/login">{{ trans('messages.account') }}</a>
        </li>
        
        @foreach ($menuItems as $key => $menuItem)
            <li class="nav-item">
                @if ($menuItem->subMenus->count() > 0)
                    <a class="nav-link dropdown-toggle" href="{{ $menuItem->url }}" id="navbarDropdown{{ $key }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $menuItem->name }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $key }}">
                        @foreach ($menuItem->subMenus as $subMenu)
                            <li>
                                <a class="dropdown-item" href="{{ $subMenu->url }}">{{ $subMenu->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                <a href="{{ $menuItem->url }}">{{ $menuItem->name }}</a>
                @endif
            </li>
        @endforeach

        <li class="nav-item {{ Route::currentRouteName() == 'contactus' ? 'active' : '' }}">
            <a class="nav-link" href="/contactus">{{ trans('messages.contactus') }}</a>
        </li>

        <li class="nav-item {{ Route::currentRouteName() == 'aboutus' ? 'active' : '' }}">
            <a class="nav-link" href="/aboutus">{{ trans('messages.aboutus') }}</a>
        </li>
    </ul>
</div>