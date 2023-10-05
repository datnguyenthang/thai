<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    <h1>{{ trans('backend.settingceo') }}</h1>
    <form wire:submit.prevent="save" wire:ignore>

        <div class="form-outline mb-4">
            <label class="form-label" for="headTagSeo">Head Tag SEO</label>
            <textarea id="headTagSeo" class="form-control headTagSeo w-50" x-ref="headTagSeo" rows="6" wire:model="headTagSeo"></textarea>
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="bodyTagSeo">Body Tag SEO</label>
            <textarea id="bodyTagSeo" class="form-control bodyTagSeo w-50" x-ref="bodyTagSeo" rows="6" wire:model="bodyTagSeo"></textarea>
        </div>

        <button type="submit" wire:loading.attr="disabled" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.59.1/codemirror.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.59.1/codemirror.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.59.1/mode/javascript/javascript.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.59.1/theme/cobalt.css">

    <script type="module">

            const headTagSeo = document.querySelector('#headTagSeo');
            const bodyTagSeo = document.querySelector('#bodyTagSeo');

            var editorHeadTagSeo = CodeMirror.fromTextArea(headTagSeo, {
                lineNumbers: true,
                mode: 'javascript',
                theme: 'cobalt'
            });

            var editorBodyTagSeo = CodeMirror.fromTextArea(bodyTagSeo, {
                lineNumbers: true,
                mode: 'javascript',
                theme: 'cobalt'
            });

            editorHeadTagSeo.on('change', function (instance) {
                @this.set('headTagSeo', instance.getValue());
            });
            editorBodyTagSeo.on('change', function (instance) {
                @this.set('bodyTagSeo', instance.getValue());
            });

    </script>


</div>
