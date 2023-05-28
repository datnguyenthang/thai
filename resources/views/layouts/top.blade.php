<div class="top1_wrapper">
    <div class="container">
      <div class="top1 clearfix">
        <div class="email1"><a href="#">support@travelagency.com</a></div>
        <div class="phone1">+917 3386831</div>
        <div class="social_wrapper">
          <ul class="social clearfix">
            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
            <li><a href="#"><i class="fa fa-vimeo-square"></i></a></li>
          </ul>
        </div>
        <div class="lang1">
          <div class="dropdown">
            <a class="btn btn-default dropdown-toggle {{ app()->getLocale() == 'en' ? 'en' : 'tha' }}" href="{{ route('home', ['lang' => app()->getLocale() == 'en' ? 'tha' : 'en']) }}"
            id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ app()->getLocale() == 'en' ? 'English' : 'Thailand' }}
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
              <li>
                <a 	class="{{ app()->getLocale() == 'en' ? 'tha' : 'en' }}" 
                  href="{{ route('home', ['lang' => app()->getLocale() == 'en' ? 'tha' : 'en']) }}">
                  {{ app()->getLocale() == 'en' ? 'Thailand' : 'English' }}
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>