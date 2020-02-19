<div class="modal fade" id="flowerModal" tabindex="-1" role="dialog" aria-labelledby="Flower" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal__content flower-info">
            <div class="flower-info__image"></div>
            <div class="flower-info__owner-block">
                <p class="flower-info__hint">Цветок от</p>
                <div class="h3">
                    <a class="flower-info__owner" target="_blank" data-default="Anonymous"></a>
                </div>
            </div>
            <p class="flower-info__date">Расцвел 3 дня назад</p>
            <button type="button" class="tooltip__close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" data-feather="x"></i>
            </button>

            <div id="flowerShareBtn" class="btn btn-primary flower-info__btn-share">Поделиться</div>

            <div class="flower-info__share-block">
                @include('front.partials.share-links-flower')
                <input id="copyLinkInput" class="form__input" type="text" value="">
                <div id="copyLinkBtn" class="socials__item--link d-block gray-text text-center">Копировать ссылку</div>
            </div>
            <div class="flower-info__arrow"></div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#copyLinkBtn').click(function () {
            select_all_and_copy(document.getElementById('copyLinkInput'));
        });
    </script>
@endpush
