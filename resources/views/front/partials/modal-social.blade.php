@php
    /** @var \App\Models\Garden $garden */
@endphp

<div class="modal fade" id="socialModal" data-garden="{{ $garden->hash_id }}" tabindex="-1" role="dialog" aria-labelledby="Payment" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal__content">
            <div class="modal__header">
                <div class="modal__title h1">
                    Спасибо!
                </div>
                <p>Чтобы подписать только что посаженные цветы, авторизуйтесь через любую из предложенных социальных сетей.</p>
                <button type="button" data-dismiss="modal" class="modal__close" aria-label="Close">
                    <i aria-hidden="true" data-feather="x"></i>
                </button>
            </div>

            <div class="modal__body socials__form">
                <div class="socials__buttons">
                    @foreach (\App\Models\Social::getSupportedProviders() as $provider)
                        @include("front.partials.auth-socials.$provider", ['garden' => $garden])
                    @endforeach
                </div>

                <a class="d-block gray-text text-center" href="{{ route('front.auth.refuse', [$garden->slug, 'to' => URL::full()]) }}">Остаться анонимным</a>
            </div>
        </div>
    </div>
</div>
