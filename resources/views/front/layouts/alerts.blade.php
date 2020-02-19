@if (session()->has('success') || session()->has('error') || session()->has('errors') || session()->has('warning'))
    <div class="alerts">
        @if (session()->has('success'))
            @component('front.layouts.alerts.success')
                {!! session('success') !!}
            @endcomponent
        @endif

        @if (session()->has('error'))
            @component('front.layouts.alerts.error')
                {!! session('error') !!}
            @endcomponent
        @endif

        @if (session()->has('errors'))
            @component('front.layouts.alerts.error')
                <ul class="list-unstyled">
                    @foreach (session('errors') as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            @endcomponent
        @endif

        @if (session()->has('warning'))
            @component('front.layouts.alerts.warning')
                {!! session('warning') !!}
            @endcomponent
        @endif
    </div>
@endif


