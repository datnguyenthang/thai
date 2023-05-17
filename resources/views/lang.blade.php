    <li class="nav-item dropdown">
        <a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdown"
            role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="/img/lang/{{ app()->getLocale() == 'en' ? 'en' : 'tha' }}.png" 
                height="25" alt="Language Logo" loading="lazy" />
        </a>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li>
                <a class="dropdown-item" name="locale"
                    href="{{ route('home', ['lang' => app()->getLocale() == 'en' ? 'tha' : 'en']) }}" >
                        <img src="/img/lang/{{ app()->getLocale() == 'en' ? 'tha' : 'en' }}.png" 
                            height="25" alt="Language Logo" loading="lazy">
                </a>
            </li>
        </ul>
    </li>
