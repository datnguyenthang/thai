<div>
    @foreach($infos as $info)
        @php
            $detail = "";
            $class = "";
            $booked = 0;

            foreach (ORDERSTATUS as $key => $value) {
                if ($key != CANCELDORDER) $booked += $info->{$value};
                
                $tx_color = '';
                switch ($key) {
                    case NEWORDER:
                        $tx_color= 'text-secondary';
                        break;
                    case UPPLOADTRANSFER:
                        $tx_color= 'text-primary';
                        break;
                    case RESERVATION:
                        $tx_color= 'text-warning';
                        break;
                    case PAIDORDER:
                        $tx_color= 'text-info';
                        break;
                    case CONFIRMEDORDER:
                        $tx_color= 'text-success';
                        break;
                    case CANCELDORDER:
                        $tx_color= 'text-dark';
                        break;
                }

                // Fix here: Removed extra curly brace after $tx_color
                $detail .= '<div class="dropdown-item ' . $tx_color . '">' . $value . '(' . $info->{$value} . ' paxs)</div>';
            }

            $percentage = round(($booked / $info->capacity) * 100);

            // Adjusted the class assignment for the progress bar
            if ($percentage <= 25) $class = "bg-success";
            else if ($percentage <= 50) $class = "bg-info";
            else if ($percentage <= 75) $class = "bg-warning";
            else $class = "bg-danger"; // Removed unnecessary condition since it's the last case
        @endphp

        <div class="font-sans-serif btn-reveal-trigger position-static">
            <a class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs--2" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="true" data-bs-reference="parent">
                <span class="text-800 fs--2 mb-0">{{ $booked }} / {{ $info->capacity }}</span>
                <i class="fas fa-filter"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-2" style="position: absolute; margin: 0px;" data-popper-placement="bottom-end">
                {!! $detail !!}
            </div>
        </div>

        <div class="progress" style="height: 5px;">
            <div
                class="progress-bar {{ $class }}"
                role="progressbar"
                style="width: {{ $percentage }}%;"
                aria-valuenow="{{ $percentage }}"
                aria-valuemin="0"
                aria-valuemax="100"
            ></div>
        </div>
    @endforeach
</div>