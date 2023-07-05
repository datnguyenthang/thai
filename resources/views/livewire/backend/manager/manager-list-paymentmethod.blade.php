<div>
    <h1>{{ trans('backend.listpaymentmethod') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/paymentmethod/create/0" class="btn btn-info">{{ trans('backend.createpaymentmethod') }}</a>
    
    <table class="table datatable-table">
        <thead>
            <tr>
                <th>{{ trans('backend.paymentmethodname') }}</th>
                <th>{{ trans('backend.paymentmethoddescription') }}</th>
                <th>{{ trans('backend.paymentmethodistransaction') }}</th>
                <th>{{ trans('backend.paymentmethodstatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paymentmethods as $paymentmethod)
                <tr>
                    <td>{{ $paymentmethod->name }}</td>
                    <td>{{ $paymentmethod->description }}</td>
                    <td>{{ $paymentmethod->isTransaction ? 'Yes' : 'No'}}</td>

                    <td>{{ PAYMENTMETHODSTATUS[$paymentmethod->status] }}</td>
                    <td>
                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                            wire:click="createPaymentmethod({{ $paymentmethod->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $paymentmethods->links() }}
    </div>
</div>