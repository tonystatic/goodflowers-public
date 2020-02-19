@php
    /* @var \App\Models\Garden $garden */
@endphp
<a href="{{ route('front.auth', [$garden->slug, \App\Models\Social::PROVIDER_FACEBOOK, 'to' => URL::full()]) }}" class="socials__btn socials__btn--fb btn--with-icon btn">
    <div class="btn__icon"><i data-feather="facebook"></i></div>
    <div class="btn__text">Facebook</div>
</a>
