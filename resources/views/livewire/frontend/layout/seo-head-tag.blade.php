<div>
    <div id="headtagseo" class="d-none">
        {!! $headTagSeo !!}
    </div>
    <div id="bodyTagSeo" class="d-none">
        {!! $bodyTagSeo !!}
    </div>
    <script type="text/javascript">
        const headtagseoContent = document.querySelector('#headtagseo').innerHTML;
        document.head.insertAdjacentHTML('beforeend', headtagseoContent);
        document.querySelector('#headtagseo').innerHTML = ""; // Clear the content in the original div

        const bodyTagSeoContent = document.querySelector('#bodyTagSeo').innerHTML;
        document.body.insertAdjacentHTML('afterbegin', bodyTagSeoContent);
        document.querySelector('#bodyTagSeo').innerHTML = ""; // Clear the content in the original div
    </script>
</div>
