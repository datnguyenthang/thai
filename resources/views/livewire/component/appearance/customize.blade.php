<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <h1>{{ trans('backend.customizehomepage') }}</h1>
    <form wire:submit.prevent="save">
        <h3 class="text-center">Banner</h3>
        <div class="list-group">
            <div class="row">
                <div class="col-md-4">
                    <input type="file" class="form-control" wire:loading.attr="disabled" wire:model="bannerFiles" id="{{ $counting }}-bannerFiles" accept="image/*" multiple />
                    @error('bannerFiles') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>{{ trans('messages.filename') }}</th>
                        <th>{{ trans('messages.filesize') }}</th>
                        <th>{{ trans('messages.extension') }}</th>
                        <th>{{ trans('messages.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($bannerImages)
                        @foreach ($bannerImages as $file)
                            <tr>
                                <th class="filename"><a href='{{ $file['url'] }}' target='_blank' >{{ $file['name'] }}</a></th>
                                <th class="filename">{{ round($file['size'] / (1024 * 1024), 2) }}MB</th>
                                <th class="extension">{{ $file['extension'] }}</th>
                                <th class="action">
                                    <button type="button" class="btn bg_own_color text-light" wire:loading.attr="disabled" wire:click="deleteFile('{{ $file['path'] }}')">
                                        {{ trans('messages.delete') }}
                                    </button>
                                </th>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>{{ trans('messages.nofile') }}</td>
                        </tr>
                    @endif
                <tbody>
            </table>
        </div>

        <button wire:loading.attr="disabled" wire:click="save" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
