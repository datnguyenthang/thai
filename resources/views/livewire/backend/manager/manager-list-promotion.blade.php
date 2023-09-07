<div>
    <h1>{{ trans('backend.listpromotion') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/promotion/create/0" class="btn btn-info">{{ trans('backend.createpromotion') }}</a>
    
    <table class="table table-striped datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.promotionname') }}</th>
                <th wire:click="sortBy('code')"><i class="fas fa-sort"></i>{{ trans('backend.promotioncode') }}</th>
                <th wire:click="sortBy('discount')"><i class="fas fa-sort"></i>{{ trans('backend.promotiondiscount') }}</th>
                <th wire:click="sortBy('quantity')"><i class="fas fa-sort"></i>{{ trans('backend.promotionquantity') }}</th>
                <th wire:click="sortBy('fromDate')"><i class="fas fa-sort"></i>{{ trans('backend.startdate') }}</th>
                <th wire:click="sortBy('toDate')"><i class="fas fa-sort"></i>{{ trans('backend.enddate') }}</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>{{ trans('backend.promotionstatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotions as $promotion)
                <tr>
                    <td>{{ $promotion->name }}</td>
                    <td>{{ $promotion->code }}</td>
                    <td>{{ $promotion->discount * 100}}%</td>
                    <td>{{ $promotion->quantity }}</td>
                    <td>{{ $promotion->fromDate }}</td>
                    <td>{{ $promotion->toDate }}</td>
                    <td>{{ PROMOTIONSTATUS[$promotion->status] }}</td>
                    <td>
                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        wire:click="createPromotion({{ $promotion->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $promotions->links() }}
    </div>
</div>