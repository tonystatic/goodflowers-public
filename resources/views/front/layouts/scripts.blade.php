<script src="{{ mix('assets/front/js/app.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    feather.replace();
</script>

<script src="//code-ya.jivosite.com/widget.js" data-jv-id="{{ config('services.jivosite.id') }}" async></script>

@if (app()->environment() === 'production')
    {{-- Ya.Metrika --}}
    <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym("{{ config('services.yandex_metrika.id') }}", "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/{{ config('services.yandex_metrika.id') }}" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
@endif
