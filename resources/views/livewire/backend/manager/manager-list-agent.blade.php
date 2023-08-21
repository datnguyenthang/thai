<div>
    <h1>{{ trans('backend.listagent') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="Search...">
    <a href="/agent/create/0" class="btn btn-info">{{ trans('backend.createagent') }}</a>
    
    <table class="table datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.agentname') }}</th>
                <th wire:click="sortBy('code')"><i class="fas fa-sort"></i>{{ trans('backend.agentcode') }}</th>
                <th wire:click="sortBy('agenttype')"><i class="fas fa-sort"></i>{{ trans('backend.agenttype') }}</th>
                <th wire:click="sortBy('type')"><i class="fas fa-sort"></i>{{ trans('backend.atype') }}</th>
                <th wire:click="sortBy('manager')"><i class="fas fa-sort"></i>{{ trans('backend.agentmanager') }}</th>
                <th wire:click="sortBy('email')"><i class="fas fa-sort"></i>{{ trans('backend.agentemail') }}</th>
                <th wire:click="sortBy('phone')"><i class="fas fa-sort"></i>{{ trans('backend.agentphone') }}</th>
                <th wire:click="sortBy('line')"><i class="fas fa-sort"></i>{{ trans('backend.agentline') }}</th>
                <th wire:click="sortBy('paymentType')"><i class="fas fa-sort"></i>{{ trans('backend.agentpaymenttype') }}</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>{{ trans('backend.agentstatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agents as $agent)
                <tr>
                    <td>{{ $agent->name }}</td>
                    <td>{{ $agent->code }}</td>
                    <td>
                        <ul>
                            @php
                                $agentType = explode(',', $agent->agentType)
                            @endphp
                            @foreach ($agentType as $type)
                                <li>{{ $customerType[$type] }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ ATYPE[$agent->type] }}</td>
                    <td>{{ $agent->manager }}</td>
                    <td>{{ $agent->email }}</td>
                    <td>{{ $agent->phone }}</td>
                    <td>{{ $agent->line }}</td>
                    <td>{{ $agent->paymentType ? AGENTPAYMENTTYPE[$agent->paymentType] : '' }}</td>

                    <td>{{ AGENTSTATUS[$agent->status] }}</td>
                    <td>
                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        wire:click="createAgent({{ $agent->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $agents->links() }}
    </div>
</div>

