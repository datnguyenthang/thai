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