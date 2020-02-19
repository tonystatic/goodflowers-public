@php
    /* @var \App\Models\Garden $garden */
@endphp
<a href="{{ route('front.auth', [$garden->slug, \App\Models\Social::PROVIDER_INSTAGRAM, 'to' => URL::full()]) }}" class="socials__btn socials__btn--in btn--with-icon btn">
    <div class="btn__icon"><i data-feather="instagram"></i></div>
    <div class="btn__text">Instagram</div>
</a>
