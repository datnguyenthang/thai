<div>
    <h1>{{ trans('backend.listcustomertype') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/cms/create/0" class="btn btn-info">{{ trans('backend.createpage') }}</a>
    
    <table class="table datatable-table">
        <thead>
            <tr>
                <th><i class="fas fa-sort"></i>{{ trans('backend.pageid') }}</th>
                <th><i class="fas fa-sort"></i>{{ trans('backend.pagename') }}</th>
                <th><i class="fas fa-sort"></i>{{ trans('backend.pageslug') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($pages))
                @foreach ($pages as $page)
                    <tr>
                        <td>{{ $page['id'] }}</td>
                        <td>{{ $page['name'] }}</td>
                        <td>{{ $page['slug'] }}</td>
                        <td>
                            <a class="call-btn btn btn-outline-primary btn-floating btn-sm" href='cms/front-end-builder/page-content/{{ $page['id'] }}'>
                                <i class="fas fa-pencil-ruler"></i>
                            </a>
                            <a class="call-btn btn btn-outline-primary btn-floating btn-sm" href='/{{ $page['slug'] }}'>                     
                                <i class="fa fa-eye"></i>
                            </a>
                            <a class="call-btn btn btn-outline-primary btn-floating btn-sm" href='/cms/create/{{ $page['id'] }}'>                     
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="call-btn btn btn-outline-primary btn-floating btn-sm" href="#" wire:click="deletePage({{ $page['id'] }})">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div>
       
    </div>
</div>
