@if (app()->environment() === 'production')
    {{-- Google Tag Manager --}}
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('services.google_tag_manager.id') }}"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    {{-- End Google Tag Manager (noscript) --}}

    <script>
        dataLayer = [];
    </script>
@endif

