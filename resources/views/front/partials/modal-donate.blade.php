@php
    /** @var \App\Models\Garden $garden */
@endphp

<div class="modal fade" id="donateModal" tabindex="-1" role="dialog" aria-labelledby="Donate" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal__content">
            <div class="modal__header">
                <div class="modal__title h1">
                    Посадить цветы
                </div>
                <button type="button" class="modal__close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" data-feather="x"></i>
                </button>
            </div>

            <form id="donateForm" action="{{ route('front.donate', $garden->slug) }}" method="post" class="modal__body payment__form" data-payment-key="{{ config('services.cloudpayments.public') }}" autocomplete="off">
                {{ csrf_field() }}
                <div class="payment__card">
                    <div class="row">
                        <div class="col-12">
                            <div class="form__label" id="paymentCounter">
                                <span class="form__title">Количество цветков</span>
                                <div class="row">
                                    <span class="col-sm-6 text-center">
                                        <span class="payment__number--decrement"><i>–</i></span><input id="quantityInput" name="quantity" class="form__input payment__number" type="number" data-price="{{ (int) config('flowers.price') }}" value="1" min="1" step="1" /><span class="payment__number--increment"><i>+</i></span>
                                    </span>
                                    <span class="col-sm-6">
                                        <h3 class="payment__sum"><i>{{ (int) config('flowers.price') }}</i> руб.</h3>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p>После оплаты в саду появится новый уникальный цветок. Вы сможете подписать его и оставить ссылку на свой профиль.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form__buttons">
                         <p id="donateError" class="text-danger" data-default="Ошибка при создании пожертвования." style="display: none"></p>
                        @if ((bool) config('billing.enabled'))
                            <button id="donateSubmitButton" type="submit" class="btn btn-primary payment__btn">Посадить 1 цветок</button>
                        @else
                            <button type="button" class="btn btn-secondary payment__btn" disabled>Система оплаты недоступна</button>
                        @endif
                        <p class="form__caption text-center">Нажимая на кнопку, вы принимаете <br> <a class="offer-link" target="_blank" href="{{ route('front.offer') }}">Оферту</a> и <a class="privacy-link" target="_blank" href="{{ route('front.privacy') }}">Политику конфиденциальности</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="Payment" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal__content">
            <div class="modal__header">
                <div class="modal__title h1">
                    Оплата
                </div>
                <button type="button" class="modal__close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" data-feather="x"></i>
                </button>
            </div>

            <form id="paymentForm" action="" method="post" class="modal__body payment__form" autocomplete="off">
                {{ csrf_field() }}
                <div class="payment__card">
                    <div class="row">
                        <div class="col-12">
                            <div id="flowersPreview" class="payment__flowers flowers-preview">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form__label">
                                <span class="form__title">Email</span>
                                <input id="emailInput" class="form__input" name="email" type="email" placeholder="email@email.com" required autocomplete="on" autocompletetype="email"/>
                            </label>
                            <div class="form__caption">Может использоваться для квитанции об оплате и информирования о деятельности фонда</div>
                        </div>
                    </div>

                    <div class="form__buttons">
                        <p id="paymentError" class="text-danger" data-default="Ошибка системы оплаты." style="display: none"></p>
                        @if ((bool) config('billing.enabled'))
                            <button id="paymentSubmitButton" type="submit" class="btn btn-primary payment__btn">Оплатить</button>
                        @else
                            <button type="button" class="btn btn-secondary payment__btn" disabled>Система оплаты недоступна</button>
                        @endif
                        <p>и посадить цветы</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('head')
    <script src="https://widget.cloudpayments.ru/bundles/cloudpayments"></script>
@endpush

@push('scripts')
    <script src="{{ mix('assets/front/js/donate.js') }}" type="text/javascript"></script>
@endpush
