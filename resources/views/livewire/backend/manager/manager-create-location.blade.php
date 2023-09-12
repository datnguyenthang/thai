<div>
    <h1>{{ trans('backend.createlocation') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="locationid" value="{{ $locationId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.locationname') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.locationnameoffice') }}</label>
            <input type="text" class="form-control w-50" wire:model="nameOffice">
            @error('nameOffice') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.locationgooglemapurl') }}</label>
            <input type="text" class="form-control w-50" wire:model="googleMapUrl">
            @error('googleMapUrl') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>{{ trans('messages.filename') }}</th>
                    <th>{{ trans('messages.filesize') }}</th>
                    <th>{{ trans('messages.extension') }}</th>
                    <!--<th>{{ trans('messages.action') }}</th>-->
                </tr>
            </thead>
            <tbody>
                @if ($locationFiles)
                    @foreach ($locationFiles as $file)
                        <tr>
                            <th class="filename"><a href='{{ $file['url'] }}' target='_blank' >{{ $file['name'] }}</a></th>
                            <th class="filename">{{ round($file['size'] / (1024 * 1024), 2) }}MB</th>
                            <th class="extension">{{ $file['extension'] }}</th>
                            
                            <!--
                            <th class="action">
                                <button type="button" class="btn bg_own_color text-light" wire:loading.attr="disabled" wire:click="deleteFile('{{ $file['path'] }}')">
                                    {{ trans('messages.delete') }}
                                </button>
                            </th>
                            -->
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ trans('messages.nofile') }}</td>
                    </tr>
                @endif
            <tbody>
        </table>

        
            <div class="row">
                <div class="col-md-4">
                    <input type="file" class="form-control" wire:loading.attr="disabled" wire:model="files" id="{{ $counting }}-locationFile" accept="*" />
                    @error('files') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
            </div>

        
        <div class="form-outline mt-3 mb-4">
            <label class="form-label">{{ trans('backend.locationstatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(LOCATIONSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button wire:loading.attr="disabled" wire:click="save" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')
</div>
