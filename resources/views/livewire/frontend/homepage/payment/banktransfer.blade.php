<div>
    <div class="payment_box text-dark">
        {!! trans('messages.transferinfomation') !!}
    </div>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>{{ trans('messages.file') }}</th>
                <th>{{ trans('messages.filename') }}</th>
                <th>{{ trans('messages.dimensions') }}</th>
                <th>{{ trans('messages.extension') }}</th>
                <th>{{ trans('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if ($photos)
                @foreach ($photos as $photo)
                    <tr>
                        <th class="file">
                            <a href={{ $photo['url'] }} target="_blank">
                                <img src="{{ $photo['url'] }}" width="75" height="75" alt="Proof image" />
                            </a>
                        </th>
                        <th class="filename">{{ $photo['name'] }}</th>
                        <th class="dimensions">{{ $photo['dimension'] }}</th>
                        <th class="extension">{{ $photo['extension'] }}</th>
                        <th class="action">
                            <button type="button" class="btn bg_own_color text-light" wire:loading.attr="disabled" wire:click="deleteProofs('{{ $photo['path'] }}')">
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
    <div class="card-footer text-center">
        <form wire:submit.prevent="uploadProof" enctype="multipart/form-data">
            <div class="row mt-3">
                <div class="col-md-6">
                    <input type="file" class="form-control" wire:model="proofFiles" id="{{ $counting }}-proofFiles" accept="image/*" multiple />
                    @error('proofFiles.*') <span class="text-danger error">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn bg_own_color text-light" wire:loading.attr="disabled">{{ trans('messages.upload') }}</button>

                    <button wire:loading.attr="disabled" wire:click="payment({{ BANKTRANSFER }})" class="btn bg_own_color text-light" @if (empty($photos)) disabled @endif>
                        {{ trans('messages.submit') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- End -->
</div>
