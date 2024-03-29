<div id="front_tabs">
    <div class="container-fluid">
      <div class="tabs_wrapper tabs1_wrapper">
        <div class="tabs tabs1 ui-tabs ui-widget ui-widget-content ui-corner-all">
          <div class="tabs_tabs tabs1_tabs">
  
              <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
                <li class="active flights ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="tabs-1" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true">
                  <a href="#tabs-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">{{ trans('messages.bookingtrip') }}</a>
                </li>
                  <!--
                  <li class="hotels ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-2" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="#tabs-2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">Hotels</a></li>
                  <li class="cars ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-3" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="#tabs-3" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3">Cars</a></li>
                  <li class="cruises ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-4" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false"><a href="#tabs-4" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-4">Cruises</a></li>
                  -->
              </ul>
  
          </div>
          <div class="tabs_content tabs1_content">
  
              <div id="tabs-1" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="false" style="display: block;">
                <form action="{{ route('trip') }}" class="form1">
                  
                  <div class="row">
                    <div class="col-lg-2 col-sm-2">
                      <div class="rdio rdio-primary">
                        <input type="radio" class="" id="roundtrip" value="{{ ROUNDTRIP }}" name="tripType" wire:model="tripType" wire:click="chooseTripType(1)"  wire:init="chooseTripType(1)">
                        <label for="roundtrip" class="">{{ trans('messages.roundtrip') }}</label>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-2">
                      <div class="rdio rdio-primary">
                        <input type="radio" class="" id="one-way" value="{{ ONEWAY }}" name="tripType" wire:model="tripType" wire:click="chooseTripType(0)">
                        <label for="one-way" class="">{{ trans('messages.oneway') }}</label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>{{ trans('messages.departure') }}</label>
                        <div class="select1_inner">
                          <select id="fromLocation" name="fromLocation" class="form-control" wire:model="fromLocation" wire:change="chooseFromLocation($event.target.value)" required placeholder="{{ trans('messages.pickup') }}">
                            {{--<option value=""></option>--}}
                            @foreach($fromLocationList as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                          </select>
                          <!--<span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-vflw-container"><span class="select2-selection__rendered" id="select2-vflw-container" title="City or Airport">City or Port</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>-->
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>{{ trans('messages.destination') }}</label>
                        <div class="select1_inner">
                          <select id="toLocation" name="toLocation" class="form-control" wire:model="toLocation" required placeholder="{{ trans('messages.dropoff') }}">
                            {{--<option value=""></option>--}}
                            @foreach($toLocationList as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                          </select>
                          <!--<span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-65of-container"><span class="select2-selection__rendered" id="select2-65of-container" title="City or Airport">City or Port</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>-->
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="input1_wrapper">
                        <label>{{ trans('messages.departuredate') }}</label>
                        <div class="">
                          <input id="departureDate" name="departureDate" wire:model="departureDate" class="form-control" type="date" wire:change="chooseDepartDate($event.target.value)" min="{{ date('Y-m-d') }}" required>
                        </div>
                      </div>
                    </div>

                    @if ($tripType == ROUNDTRIP)
                    <div class="col-lg-2 col-sm-12">
                      <div class="input1_wrapper">
                        <label>{{ trans('messages.returndate') }}</label>
                        <div class="">
                          <input id="returnDate" name="returnDate" wire:model="returnDate" class="form-control" type="date" min="{{ $returnDate }}" required="">
                        </div>
                      </div>
                    </div>
                    @endif
                    {{--
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>{{ trans('messages.adults') }}</label>
                        <div class="">
                          <input type="number" id="adults" name="adults" class="form-control"  min="1" value="1" required/>
                        </div>
                      </div>
                    </div>
                    --}}
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>{{ trans('messages.adults') }}</label>
                        <div class="input-group" x-data="{ adults: @entangle('adults') }">
                          <span class="input-group-btn">
                              <button type="button" x-on:click="adults > 1 ? adults-- : adults = 1" class="btn bg_own_color btn-number">
                                <i class="fas fa-minus"></i>
                              </button>
                          </span>
                          <input type="number" wire:model.defer="adults" id="adults" name="adults" class="form-control input-number text-center" min="1" max="100" required/>
                          <span class="input-group-btn">
                              <button type="button" x-on:click="adults++" class="btn bg_own_color btn-number">
                                <i class="fas fa-plus"></i>
                              </button>
                          </span>
                        </div>
                      </div>
                    </div>

                    {{--
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>{{ trans('messages.children') }}</label>
                        <div class="">
                          <input type="number" id="children" name="children" class="form-control" min="0" value="0" required/>
                        </div>
                      </div>
                    </div>
                    --}}
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>{{ trans('messages.children') }}</label>
                        <div class="input-group" x-data="{ children: @entangle('children') }">
                          <span class="input-group-btn">
                              <button type="button" x-on:click="children >= 1 ? children-- : children = 0" class="btn bg_own_color btn-number">
                                <i class="fas fa-minus"></i>
                              </button>
                          </span>
                          <input type="number" wire:model.defer ="children" id="children" name="children" class="form-control input-number text-center" min="0" max="100" required/>
                          <span class="input-group-btn">
                              <button type="button" x-on:click="children++" class="btn bg_own_color btn-number">
                                <i class="fas fa-plus"></i>
                              </button>
                          </span>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-lg-12 col-sm-12">
                      <div class="button1_wrapper">
                        <button type="submit" class="btn-default btn-form1-submit">Search</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

              <script>
                var adultsInput = document.getElementById("adults");
                var childrenInput = document.getElementById("children");

                // Add a keyup event listener for adults input
                adultsInput.addEventListener("keyup", function() {
                  var currentValue = this.value.trim();
                  if (currentValue === "") {
                    this.value = "1";
                  }
                });

                // Add a keyup event listener for children input
                childrenInput.addEventListener("keyup", function() {
                  var currentValue = this.value.trim();
                  if (currentValue === "") {
                    this.value = "0";
                  }
                });
              </script>

              <!--
              <div id="tabs-2" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom tab-hidden" role="tabpanel" aria-hidden="true" style="display: none;">
                <form action="javascript:;" class="form1">
                  <div class="row">
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>City or Hotel Name:</label>
                        <div class="select1_inner">
                          <select class="select2 select select2-hidden-accessible" style="width: 100%" tabindex="-1" aria-hidden="true">
                            <option value="1">Enter a destination or hotel name</option>
                            <option value="2">Lorem ipsum dolor sit amet</option>
                            <option value="3">Duis autem vel eum</option>
                            <option value="4">Ut wisi enim ad minim veniam</option>
                            <option value="5">Nam liber tempor cum</option>
                            <option value="6">At vero eos et accusam et</option>
                            <option value="7">Consetetur sadipscing elitr</option>
                            <option value="8">Sed diam nonumy</option>
                          </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-bv5y-container"><span class="select2-selection__rendered" id="select2-bv5y-container" title="Enter a destination or hotel name">Enter a destination or hotel name</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 ccol-sm-12">
                      <div class="input1_wrapper">
                        <label>Check-In:</label>
                        <div class="input1_inner">
                          <input type="text" class="input datepicker hasDatepicker" value="Mm/Dd/Yy" id="dp1684190518805">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="input1_wrapper">
                        <label>Check-Out:</label>
                        <div class="input1_inner">
                          <input type="text" class="input datepicker hasDatepicker" value="Mm/Dd/Yy" id="dp1684190518806">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>Adult:</label>
                        <div class="select1_inner">
                          <select class="select2 select select2-hidden-accessible" style="width: 100%" tabindex="-1" aria-hidden="true">
                            <option value="1">Room  for  1  adult</option>
                            <option value="2">Room  for  2  adult</option>
                            <option value="3">Room  for  3  adult</option>
                            <option value="4">Room  for  4  adult</option>
                            <option value="5">Room  for  5  adult</option>
                            <option value="6">Room  for  6  adult</option>
                            <option value="7">Room  for  7  adult</option>
                            <option value="8">Room  for  8  adult</option>
                          </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-u1jb-container"><span class="select2-selection__rendered" id="select2-u1jb-container" title="Room  for  1  adult">Room  for  1  adult</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="button1_wrapper">
                        <button type="submit" class="btn-default btn-form1-submit">Search</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div id="tabs-3" aria-labelledby="ui-id-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom tab-hidden" role="tabpanel" aria-hidden="true" style="display: none;">
                <form action="javascript:;" class="form1">
                  <div class="row">
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>Country:</label>
                        <div class="select1_inner">
                          <select class="select2 select select2-hidden-accessible" style="width: 100%" tabindex="-1" aria-hidden="true">
                            <option value="1">Please Select</option>
                            <option value="2">Alaska</option>
                            <option value="3">Bahamas</option>
                            <option value="4">Bermuda</option>
                            <option value="5">Canada</option>
                            <option value="6">Caribbean</option>
                            <option value="7">Europe</option>
                            <option value="8">Hawaii</option>
                          </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-i9b5-container"><span class="select2-selection__rendered" id="select2-i9b5-container" title="Please Select">Please Select</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-122">
                      <div class="select1_wrapper">
                        <label>City:</label>
                        <div class="select1_inner">
                          <select class="select2 select select2-hidden-accessible" style="width: 100%" tabindex="-1" aria-hidden="true">
                            <option value="1">Please Select</option>
                            <option value="2">Alaska</option>
                            <option value="3">Bahamas</option>
                            <option value="4">Bermuda</option>
                            <option value="5">Canada</option>
                            <option value="6">Caribbean</option>
                            <option value="7">Europe</option>
                            <option value="8">Hawaii</option>
                          </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-x18m-container"><span class="select2-selection__rendered" id="select2-x18m-container" title="Please Select">Please Select</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>Location:</label>
                        <div class="select1_inner">
                          <select class="select2 select select2-hidden-accessible" style="width: 100%" tabindex="-1" aria-hidden="true">
                            <option value="1">Please Select</option>
                            <option value="2">Alaska</option>
                            <option value="3">Bahamas</option>
                            <option value="4">Bermuda</option>
                            <option value="5">Canada</option>
                            <option value="6">Caribbean</option>
                            <option value="7">Europe</option>
                            <option value="8">Hawaii</option>
                          </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-614e-container"><span class="select2-selection__rendered" id="select2-614e-container" title="Please Select">Please Select</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="input1_wrapper">
                        <label>Pick up Date:</label>
                        <div class="input1_inner">
                          <input type="text" class="input datepicker hasDatepicker" value="Mm/Dd/Yy" id="dp1684190518807">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="input1_wrapper">
                        <label>Drop off Date:</label>
                        <div class="input1_inner">
                          <input type="text" class="input datepicker hasDatepicker" value="Mm/Dd/Yy" id="dp1684190518808">
                        </div>
                      </div>
                    </div>
  
  
                    <div class="col-lg-2 col-sm-12">
                      <div class="button1_wrapper">
                        <button type="submit" class="btn-default btn-form1-submit">Search</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div id="tabs-4" aria-labelledby="ui-id-4" class="ui-tabs-panel ui-widget-content ui-corner-bottom tab-hidden" role="tabpanel" aria-hidden="true" style="display: none;">
                <form action="javascript:;" class="form1">
                  <div class="row">
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>Sail To:</label>
                        <div class="select1_inner">
                          <select class="select2 select select2-hidden-accessible" style="width: 100%" tabindex="-1" aria-hidden="true">
                            <option value="1">All destinations</option>
                            <option value="2">Alaska</option>
                            <option value="3">Bahamas</option>
                            <option value="4">Bermuda</option>
                            <option value="5">Canada</option>
                            <option value="6">Caribbean</option>
                            <option value="7">Europe</option>
                            <option value="8">Hawaii</option>
                          </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-as2k-container"><span class="select2-selection__rendered" id="select2-as2k-container" title="All destinations">All destinations</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>Sail From:</label>
                        <div class="select1_inner">
                          <select class="select2 select select2-hidden-accessible" style="width: 100%" tabindex="-1" aria-hidden="true">
                            <option value="1">All ports</option>
                            <option value="2">Alaska</option>
                            <option value="3">Bahamas</option>
                            <option value="4">Bermuda</option>
                            <option value="5">Canada</option>
                            <option value="6">Caribbean</option>
                            <option value="7">Europe</option>
                            <option value="8">Hawaii</option>
                          </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-kfdx-container"><span class="select2-selection__rendered" id="select2-kfdx-container" title="All ports">All ports</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                      </div>
                    </div>
  
                    <div class="col-lg-2 col-sm-12">
                      <div class="input1_wrapper">
                        <label>Start Date:</label>
                        <div class="input1_inner">
                          <input type="text" class="input datepicker hasDatepicker" value="From any month" id="dp1684190518809">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="input1_wrapper">
                        <label>End of Date:</label>
                        <div class="input1_inner">
                          <input type="text" class="input datepicker hasDatepicker" value="To any month" id="dp1684190518810">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                      <div class="select1_wrapper">
                        <label>Cruise Ship:</label>
                        <div class="select1_inner">
                          <select class="select2 select select2-hidden-accessible" style="width: 100%" tabindex="-1" aria-hidden="true">
                            <option value="1">All Ships</option>
                            <option value="2">Alaska</option>
                            <option value="3">Bahamas</option>
                            <option value="4">Bermuda</option>
                            <option value="5">Canada</option>
                            <option value="6">Caribbean</option>
                            <option value="7">Europe</option>
                            <option value="8">Hawaii</option>
                          </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-ap6i-container"><span class="select2-selection__rendered" id="select2-ap6i-container" title="All Ships">All Ships</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                        </div>
                      </div>
                    </div>
  
  
                    <div class="col-lg-2 col-sm-12">
                      <div class="button1_wrapper">
                        <button type="submit" class="btn-default btn-form1-submit">Search</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            -->
          </div>
        </div>
      </div>
    </div>
  </div>