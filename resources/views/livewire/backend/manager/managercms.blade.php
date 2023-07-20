<div>
    <h1>{{ trans('backend.listcustomertype') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="{{ $url.'/create-page' }}" class="btn btn-info">{{ trans('backend.createpage') }}</a>
    
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
            @foreach ($pages as $page)
                <tr>
                    <td>{{ $page['id'] }}</td>
                    <td>{{ $page['name'] }}</td>
                    <td>{{ $page['slug'] }}</td>
                    <td>
                        <a class="call-btn btn btn-outline-primary btn-floating btn-sm" href='{{ $url }}'>                     
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
       
    </div>
</div>
