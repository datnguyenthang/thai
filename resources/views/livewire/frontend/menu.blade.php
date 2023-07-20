<div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    {{-- In work, do what you enjoy. --}}
    <ul class="navbar-nav">
        <li class="nav-item active">
            <a class="nav-link" href="/">Home</a>
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

        <li class="nav-item">
            <a class="nav-link" href="/aboutus">About</a>
        </li>
    </ul>
</div>