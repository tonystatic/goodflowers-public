@php
    /* @var \App\Models\Garden $garden */
@endphp
<a href="{{ route('front.auth', [$garden->slug, \App\Models\Social::PROVIDER_TWITTER, 'to' => URL::full()]) }}" class="socials__btn socials__btn--tw btn--with-icon btn">
    <div class="btn__icon"><i data-feather="twitter"></i></div>
    <div class="btn__text">Twitter</div>
</a>
