<div>
    <h1>{{ trans('backend.listmenu') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/menu/create/0" class="btn btn-info">{{ trans('backend.createmenu') }}</a>
    
    <table class="table table-striped datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.menuname') }}</th>
                <th wire:click="sortBy('url')"><i class="fas fa-sort"></i>{{ trans('backend.menuurl') }}</th>
                <th wire:click="sortBy('page_id')"><i class="fas fa-sort"></i>{{ trans('backend.menupageid') }}</th>
                <th wire:click="sortBy('parent_id')"><i class="fas fa-sort"></i>{{ trans('backend.parentname') }}</th>
                <th wire:click="sortBy('menuopennewtab')"><i class="fas fa-sort"></i>{{ trans('backend.menuopennewtab') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.menusortorder') }}</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>{{ trans('backend.menustatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $menu)
                <tr>
                    <td>{{ $menu->name }}</td>
                    <td>{{ $menu->url }}</td>
                    <td>{{ $menu->page_id ? $pageList[$menu->page_id] : '' }}</td>
                    <td>{{ $menu->parent_id ? $menuList[$menu->parent_id] : '' }}</td>
                    <td>{{ $menu->menuopennewtab ? 'Yes' : 'No' }}</td>
                    <td>{{ $menu->sortOrder }}</td>
                    <td>{{ MENUSTATUS[$menu->status] }}</td>
                    <td>
                        <a class="call-btn btn btn-outline-primary btn-floating btn-sm"
                            href="/menu/create/{{ $menu->id }}">
                                <i class="fa fa-edit"></i>
                        </a>
                        <button class="call-btn btn btn-danger btn-floating btn-sm"
                            wire:click="deleteMenu({{ $menu->id }})">
                                <i class="fa fa-trash"></i>
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