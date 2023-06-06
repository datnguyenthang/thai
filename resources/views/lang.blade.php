<div class="dropdown">
    <a class="nav-link me-3 dropdown-toggle hidden-arrow" href="#" id="navbarDropdown"
        role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="/img/lang/{{ session()->getLocale() == 'en' ? 'en' : 'tha' }}.png" 
            height="25" alt="Language Logo" loading="lazy" />
    </a>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li>
            <a class="dropdown-item" name="locale"
                href="{{ session()->get('locale') == 'en' ? 'tha' : 'en']) }}" >
                    <img src="/img/lang/{{ session()->getLocale() == 'en' ? 'tha' : 'en' }}.png" 
                        height="25" alt="Language Logo" loading="lazy">
            </a>
        </li>
    </ul>
</div>
