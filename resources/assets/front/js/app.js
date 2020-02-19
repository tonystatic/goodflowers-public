$(document).ready(function() {

    //--------------------------------------------------------------------------
    // General

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    setTimeout(function() {
        $('.alert').alert('close')
    }, 8000);

    $('.hover').bind('touchstart touchend', function(e) {
        $(this).toggleClass('touch_hover');
    });

    $('[data-toggle="tooltip"]').tooltip();

    $('a[href^="#"]').click(function(){
        var anchor = $(this).attr('href');

        $('html, body').animate({
            scrollTop: $(anchor).offset().top}, 300);

        setTimeout(function(){
            window.location.hash = anchor;
        }, 700);

        if($('.hamburger-icon').hasClass('active')) {
            setTimeout(function(){
                $('.hamburger-icon').trigger('click');
            }, 300);
        }

        return false;
    });

    //--------------------------------------------------------------------------
    // Add POST redirect function to jQuery

    $.extend({
        redirectPost: function (url, data = {}) {
            let form = document.createElement('form');
            document.body.appendChild(form);
            form.method = 'post';
            form.action = url;
            for (let name in data) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = data[name];
                form.appendChild(input);
            }
            form.submit();
        }
    });

    //--------------------------------------------------------------------------
    // Endings (Russian lang)

    $.extend({
        declOfNum: function (number, titles) {
            let cases = [2, 0, 1, 1, 1, 2];
            return titles[ (number%100>4 && number%100<20) ? 2 : cases[ (number%10<5) ? number%10 : 5 ] ];
        }
    });

    //--------------------------------------------------------------------------
    // Garden Info

    $('#gardenExtra').click(function () {
       $('.garden__extra--hidden').slideToggle();
    });

    //--------------------------------------------------------------------------
    // URL hash

    if (window.location.hash === '#_=_') {
        history.replaceState
            ? history.replaceState(null, null, window.location.href.split('#')[0])
            : window.location.hash = '';
    }

    var anchor = window.location.hash;
    if (anchor !== "") {
        try {
            var anchorBlock = $(anchor);
            if (anchorBlock) {
                $('html, body').animate({
                    scrollTop: anchorBlock.offset().top
                }, 300);
            }
        } catch (error) {}
    }

    //--------------------------------------------------------------------------
    // Enable elements with specific class only after full JS loading

    $('.enable-after-js').prop('disabled', false);

    //--------------------------------------------------------------------------
    // Add url params management functions

    $.extend({
        getUrlParameter: function (url, parameter) {
            parameter = parameter.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?|&]' + parameter + '=([^&#]*)');
            var results = regex.exec('?' + String(url).split('?')[1]);

            return results === null
                ? null
                : decodeURIComponent(results[1].replace(/\+/g, ' '));
        },
        setUrlParameter: function (url, parameter, value) {
            var urlQueryString = '';

            parameter = encodeURIComponent(parameter);
            if (value !== null) {
                value = encodeURIComponent(value);
            }

            var baseUrl = String(url).split('?')[0],
                newParam = parameter + '=' + value,
                params = '?' + newParam;

            if (String(url).split('?')[1] !== undefined) { // if there are no query strings, make urlQueryString empty
                urlQueryString = '?' + String(url).split('?')[1];
            }

            // If the "search" string exists, then build params from it
            if (urlQueryString) {
                var updateRegex = new RegExp('([\?&])' + parameter + '[^&]*');
                var removeRegex = new RegExp('([\?&])' + parameter + '=[^&;]+[&;]?');

                if (value === undefined || value === null || value === '') { // Remove param if value is empty
                    params = urlQueryString.replace(removeRegex, "$1");
                    params = params.replace(/[&;]$/, "");

                } else if (urlQueryString.match(updateRegex) !== null) { // If param exists already, update it
                    params = urlQueryString.replace(updateRegex, "$1" + newParam);

                } else if (urlQueryString === '') { // If there are no query strings
                    params = '?' + newParam;
                } else { // Otherwise, add it to end of query string
                    params = urlQueryString + '&' + newParam;
                }
            }

            // no parameter was set so we don't need the question mark
            params = params === '?' ? '' : params;

            return baseUrl + params;
        }
    });
});
