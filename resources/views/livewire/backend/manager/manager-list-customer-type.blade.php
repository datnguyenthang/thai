<div>
    <h1>{{ trans('backend.listcustomertype') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/customertype/create/0" class="btn btn-info">{{ trans('backend.createcustomertype') }}</a>
    
    <table class="table datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.customertypename') }}</th>
                <th wire:click="sortBy('code')"><i class="fas fa-sort"></i>{{ trans('backend.customertypecode') }}</th>
                <th wire:click="sortBy('price')"><i class="fas fa-sort"></i>{{ trans('backend.customertypeprice') }}</th>
                <th wire:click="sortBy('type')"><i class="fas fa-sort"></i>{{ trans('backend.customertypetype') }}</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>{{ trans('backend.customertypestatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customerTypes as $customertype)
                <tr>
                    <td>{{ $customertype->name }}</td>
                    <td>{{ $customertype->code }}</td>
                    <td>{{ $customertype->price }}</td>
                    <td>{{ AGENTLOCAL[$customertype->type] }}</td>
                    <td>{{ CUSTOMERTYPESTATUS[$customertype->status] }}</td>
                    <td>
                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        wire:click="createCustomerType({{ $customertype->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $customerTypes->links() }}
    </div>
</div>
