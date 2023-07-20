<div>
    <h1>{{ trans('backend.listmenu') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/menu/create/0" class="btn btn-info">{{ trans('backend.createmenu') }}</a>
    
    <table class="table datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.menuname') }}</th>
                <th wire:click="sortBy('url')"><i class="fas fa-sort"></i>{{ trans('backend.menuurl') }}</th>
                <th wire:click="sortBy('parent_id')"><i class="fas fa-sort"></i>{{ trans('backend.parentname') }}</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>{{ trans('backend.menustatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $menu)
                <tr>
                    <td>{{ $menu->name }}</td>
                    <td>{{ $menu->url }}</td>
                    <td>{{ $menu->parent_id ? $menuList[$menu->parent_id] : '' }}</td>
                    <td>{{ MENUSTATUS[$menu->status] }}</td>
                    <td>
                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        wire:click="createMenu({{ $menu->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $menus->links() }}
    </div>
</div>