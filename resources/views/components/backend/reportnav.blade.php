 <!-- put collapse menu here -->
 <a href="#submenu" 
    class="list-group-item list-group-item-action py-2 ripple" 
    data-bs-toggle="collapse" 
    aria-expanded="false">
    <i class="fas fa-registered fa-fw me-3"></i>
    <span>{{ trans('backend.report') }}</span>
    <i class="fas fa-caret-down ms-3"></i>
</a>
<div class="collapse {{ in_array(Route::currentRouteName(), ['dailyReport', 'monthlyReport', 'yearlyReport', 'salePerformance']) ? 'show' : '' }}" id="submenu">
   <ul class="list-unstyled list-group-flush ms-3">
       <li class="list-group-item text-center">
           <a href="{{ route('dailyReport') }}" 
             class="text-decoration-none list-group-item-action ripple {{ Route::currentRouteName() == 'dailyReport' ? 'list-group-item active' : '' }}">
             <span>{{ trans('backend.dailyreport') }}</span>
           </a>
       </li>
       <li class="list-group-item text-center">
         <a href="{{ route('monthlyReport') }}" 
           class="text-decoration-none list-group-item-action ripple {{ Route::currentRouteName() == 'monthlyReport' ? 'list-group-item active' : '' }}">
           <span>{{ trans('backend.monthlyreport') }}</span>
         </a>
       </li>
       <li class="list-group-item text-center">
         <a href="{{ route('yearlyReport') }}" 
           class="text-decoration-none list-group-item-action ripple {{ Route::currentRouteName() == 'yearlyReport' ? 'list-group-item active' : '' }}">
           <span>{{ trans('backend.yearlyreport') }}</span>
         </a>
       </li>
       <li class="list-group-item text-center">
         <a href="{{ route('salePerformance') }}" 
           class="text-decoration-none list-group-item-action ripple {{ Route::currentRouteName() == 'salePerformance' ? 'list-group-item active' : '' }}">
           <span>{{ trans('backend.saleperformance') }}</span>
         </a>
     </li>
   </ul>
</div>
