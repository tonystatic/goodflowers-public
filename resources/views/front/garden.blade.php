@extends('front.layouts.default')

@php
    /* @var \App\Models\Garden $garden */
@endphp

@section('title')
    @parent | Благотворительный сад {{ $garden->owner_name }}
@endsection

@section('content')
    <section class="garden">
        <div class="garden__canvas">
            <svg class="garden__svg" id="flowersGrid" data-data="{{ route('front.garden.flowersData', $garden->slug) }}">
                <g id="flowersGridGroup"></g>
            </svg>
        </div>
        <article id="infoCard" class="garden__card card">
            <div class="garden__name">
                <h1 class="h2">Сад
                    @if ($garden->owner_link !== null)
                        <a id="ownerLink" href="{{ $garden->owner_link }}" target="_blank">{{ $garden->owner_name }}</a>
                    @else
                        {{ $garden->owner_name }}
                    @endif
                </h1>
                @if ($garden->total_value->getValue() !== (float) 0)
                <h2 class="h2">
                    отправил в <a class="fund-link" target="_blank" href="https://detinashi.ru/">фонд</a> <span class="h3">{{ $garden->total_value->getFormattedValue() }}</span> {{ $garden->total_value->getCurrencySymbol() }}
                </h2>
                @endif
            </div>
            <div class="garden__donate">
                <p>Внесите вклад, посадите уникальный цветок</p>
                <div id="gardenDonateBtn" data-toggle="modal" data-target="#donateModal" class="garden__donate-btn btn btn-primary">Посадить цветок</div>
                <div class="garden__arrow d-block d-sm-none">
                    <i data-feather="chevron-down"></i>
                </div>
            </div>
            <div class="garden__extra">
                <p>Сад воплощает желание владельца привлечь внимание к благотворительности.</p>
                <p><strong>Цветы в садах — это вклады пользователей.</strong> Они уникальны и соответствуют пожертвованию размером в 100₽.</p>
                <p><strong>Средства идут в фонд <a class="fund-link" target="_blank" href="https://detinashi.ru/">Дети Наши</a>.</strong> Фонд занимается профилактикой социального сиротства, а детям, оставшимся без родителей, помогает успешно интегрироваться в общество. </p>
                <p><strong>Подпишите свой цветок</strong> и навсегда останьтесь в истории этого благотворительного сада.</p>
            </div>
            <div class="garden__share">
                <p>Вы также можете помочь, рассказав друзьям про этот сад</p>
                @include('front.partials.share-links-garden', ['link' => route('front.garden', $garden->slug)])
            </div>
            <p class="garden__docs"><a class="offer-link" target="_blank" href="{{ route('front.offer') }}">Оферта</a> и <a class="privacy-link" target="_blank" href="{{ route('front.privacy') }}">Политика конфиденциальности</a></p>
        </article>
    </section>

    @include('front.partials.flower-info')
    @include('front.partials.modal-donate')
    @include('front.partials.modal-social')
@endsection

@push('scripts')
    <script src="{{ mix('assets/front/js/garden.js') }}" type="text/javascript"></script>

    @if (app()->environment() === 'production')
        {{--DataLayer.push for GTM--}}
        <script>
            dataLayer.push({
                'event': 'GF',
                'eventCategory': 'сад',
                'eventAction': 'просмотр сада',
                'eventLabel': '{{ $garden->slug }}'
            });

            $('#headerLogoLink').click(function () {
                console.log('клик по логотипу в шапке');
                dataLayer.push({
                    'event': 'GF',
                    'eventCategory': 'сад',
                    'eventAction': 'клик по логотипу в шапке',
                    'eventLabel': '{{ $garden->slug }}'
                });
            });

            $('#gardenDonateBtn').click(function () {
                dataLayer.push({
                    'event': 'GF',
                    'eventCategory': 'сад',
                    'eventAction': 'клик по кнопке посадить цветок',
                    'eventLabel': '{{ $garden->slug }}'
                });
            });

            $('#headerCreateGardenBtn').click(function () {
                dataLayer.push({
                    'event': 'GF',
                    'eventCategory': 'сад',
                    'eventAction': 'клик по кнопке в шапке завести свой сад',
                    'eventLabel': '{{ $garden->slug }}'
                });
            });

            $('body').on('mousedown', '.flower', function(evt) {
                $('.flower').on('mouseup mousemove', function handler(evt) {
                    if (evt.type === 'mouseup') {
                        dataLayer.push({
                            'event':'GF',
                            'eventCategory':'цветок',
                            'eventAction':'клик по цветку',
                            'eventLabel':'{{ $garden->slug }}',
                            'flowerId': $(this).data('id'),
                            'flowerOwnerId': $(this).data('owner-id'),
                            'flowerOwnerName': $(this).data('owner-name')
                        });
                    }
                    $('.flower').off('mouseup mousemove', handler);
                });
            });

            $('#gardenExtra').click(function () {
                dataLayer.push({
                    'event': 'GF',
                    'eventCategory': 'сад',
                    'eventAction': 'клик по кнопке о чем это',
                    'eventLabel': '{{ $garden->slug }}'
                });
            });

            $('.garden__socials-item').click(function () {
                dataLayer.push({
                    'event': 'GF',
                    'eventCategory': 'соц.сети',
                    'eventAction': 'поделиться садом',
                    'eventLabel': '{{ $garden->slug }}',
                    'socialType': $(this).data('social')
                });
            });

            $('#ownerLink').click(function () {
                dataLayer.push({
                    'event': 'GF',
                    'eventCategory': 'соц.сети',
                    'eventAction': 'клик по ссылке владельца сада',
                    'eventLabel': '{{ $garden->slug }}'
                });
            });

            {{-- TODO: находить цветок из попапа --}}
            {{--$('#flowerShareBtn').click(function () {--}}
            {{--    let flower = $(this).parents().find('.flower');--}}
            {{--    console.log('поделиться цветком');--}}
            {{--    console.log($(this).data('social'));--}}
            {{--    console.log(flower.data('id'));--}}
            {{--    dataLayer.push({--}}
            {{--        'event': 'GF',--}}
            {{--        'eventCategory': 'цветок',--}}
            {{--        'eventAction': 'клик по кнопке поделиться цветком',--}}
            {{--        'eventLabel': '{{ $garden->slug }}',--}}
            {{--        'flowerId': flower.data('id'),--}}
            {{--        'flowerOwnerId': flower.data('owner-id'),--}}
            {{--        'flowerOwnerName': flower.data('owner-name')--}}
            {{--    });--}}
            {{--});--}}

            {{-- TODO: находить цветок из попапа --}}
            {{--$('.flower-info__socials-item').click(function () {--}}
            {{--    let flower = $(this).parents('.flower');--}}
            {{--    console.log('поделиться цветком');--}}
            {{--    console.log($(this).data('social'));--}}
            {{--    console.log(flower.data('id'));--}}
            {{--    dataLayer.push({--}}
            {{--        'event': 'GF',--}}
            {{--        'eventCategory': 'цветок',--}}
            {{--        'eventAction': 'поделиться цветком',--}}
            {{--        'eventLabel': '{{ $garden->slug }}',--}}
            {{--        'flowerId': flower.data('id'),--}}
            {{--        'flowerOwnerId': flower.data('owner-id'),--}}
            {{--        'flowerOwnerName': flower.data('owner-name'),--}}
            {{--        'socialType': $(this).data('social')--}}
            {{--    });--}}
            {{--});--}}

            {{-- TODO: посадка цветка --}}

            {{-- TODO: подпись цветка своим именем/анонимность --}}

            $('a').click(function () {
                dataLayer.push({
                    'event': 'GF',
                    'eventCategory': 'сад',
                    'eventAction': 'клик по ссылке',
                    'eventLabel': '{{ $garden->slug }}',
                    'link': $(this).attr('href'),
                    'linkName': $(this).html()
                });
            });
        </script>
    @endif
@endpush
