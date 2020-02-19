$(document).ready(function() {
    var flowerParam = $.getUrlParameter(window.location, 'flower');

    function drawFlower(flowerInfo, coord, size, parent) {
        let flower = document.createElementNS("http://www.w3.org/2000/svg", "image");

        flower.setAttribute("class", "flower");
        flower.setAttribute("href", flowerInfo["image"]);
        flower.setAttribute("data-id", flowerInfo["hash"]);
        flower.setAttribute("data-mine", flowerInfo["mine"]);
        flower.setAttribute("data-timestamp", flowerInfo["timestamp"]);
        flower.setAttribute("data-link", flowerInfo["link"]);
        flower.setAttribute("data-owner-id", flowerInfo["owner"] !== null ? flowerInfo["owner"]['id'] : null);
        flower.setAttribute("data-owner-name", flowerInfo["owner"] !== null ? flowerInfo["owner"]['name'] : null);
        flower.setAttribute("data-owner-link", flowerInfo["owner"] !== null ? flowerInfo["owner"]['link'] : null);
        flower.setAttribute("data-toggle", "modal");
        flower.setAttribute("x", coord[0]);
        flower.setAttribute("y", coord[1]);
        flower.setAttribute("width", size[0]);
        flower.setAttribute("height", size[1]);

        parent.appendChild(flower);
    }

    //constants
    let pi = 3.1415926535;
    let radius = 50;
    let cos30 = 0.8660254038;
    let h = 2 * radius;// * cos30;
    let hexSize = [2 * radius, h];
    //state
    var currentRow = 0;
    var angleSwitchInCurrentRow = 0;
    var stepsInCurrentDirection = 0;
    var currentCoord = [0, 0];
    var currentAngle = 0;

    var minX = 0;
    var maxX = 0;
    var minY = 0;
    var maxY = 0;

    function nextCoord() {
        if (currentRow === 0 || (angleSwitchInCurrentRow === 6 && stepsInCurrentDirection === currentRow)) {
            currentRow += 1;
            stepsInCurrentDirection = 0;
            angleSwitchInCurrentRow = 1;
            let coord = currentCoord;
            currentCoord = [currentCoord[0], currentCoord[1] - h];
            currentAngle = -pi / 6.0;
            if (currentRow === 1) {
                maxY = 100;
                minX = 100;

                return coord;
            }
        }

        if (stepsInCurrentDirection === currentRow) {
            currentAngle -= pi/3.0;
            stepsInCurrentDirection = 0;
            angleSwitchInCurrentRow +=1;
        }
        stepsInCurrentDirection += 1;
        currentCoord = [currentCoord[0] + h * Math.cos(currentAngle), currentCoord[1] - h * Math.sin(currentAngle)];

        if (currentCoord[0] < minX) {
            minX = currentCoord[0]
        } else if (currentCoord[0] + hexSize[0] > maxX) {
            maxX = currentCoord[0] + hexSize[0]
        }
        if (currentCoord[1] < minY) {
            minY = currentCoord[1]
        } else if (currentCoord[1] + hexSize[1] > maxY) {
            maxY = currentCoord[1] + hexSize[1]
        }
        return currentCoord;
    }

    let svg = document.getElementById("flowersGrid");
    let g = document.getElementById("flowersGridGroup");

    // let width = $(document).width();
    // let height = $(document).height();
    // let cardH = 500;
    // let cardW = 0;

    let dataUrl = $('#flowersGrid').data('data');

    $.get(
        dataUrl,
        function (data) {
            if (data.success === true) {
                if (
                    data.data
                    && data.data.flowers
                ) {
                    // Check if after donation popup is to be shown
                    let socialModal = $('#socialModal'),
                        gardenHash = socialModal.data('garden'),
                        donationMadeCookie = Cookies.get('donation_made_' + gardenHash),
                        afterDonationPopupIsShown = (typeof donationMadeCookie !== 'undefined');

                    for (let i = 0; i < data.data.flowers.length; i++) {
                        drawFlower(data.data.flowers[i], nextCoord(), hexSize, g);
                        if (
                            ! afterDonationPopupIsShown && flowerParam !== null
                            && data.data.flowers[i]['hash'] === flowerParam
                        ) {
                            openFlowerInfo(flowerParam);
                        }
                    }

                    let size = $(window).width();
                    if (size < $(window).height()) {
                        size = $(window).height()
                    }

                    if (Math.sqrt(data.data.flowers.length) > size) {
                        size = Math.sqrt(data.data.flowers.length) * 100;
                    }
                    //$(svg).height(size);
                    //$(svg).width(size);
                    $(svg).css('width', '100%');
                    $(svg).css('height', '100%');
                    svg.setAttribute("viewBox", "" + minX + " " + minY + " " + (maxX - minX) + " " + (maxY - minY));

                    var beforePan;

                    beforePan = function(oldPan, newPan){
                        let stopHorizontal = false,
                            stopVertical = false,
                            gutterWidth = 100,
                            gutterHeight = 100,
                            // Computed variables
                            sizes = this.getSizes(),
                            leftLimit = -((sizes.viewBox.x + sizes.viewBox.width) * sizes.realZoom) + gutterWidth,
                            rightLimit = sizes.width - gutterWidth - (sizes.viewBox.x * sizes.realZoom),
                            topLimit = -((sizes.viewBox.y + sizes.viewBox.height) * sizes.realZoom) + gutterHeight,
                            bottomLimit = sizes.height - gutterHeight - (sizes.viewBox.y * sizes.realZoom);

                        let customPan = {};
                        customPan.x = Math.max(leftLimit, Math.min(rightLimit, newPan.x));
                        customPan.y = Math.max(topLimit, Math.min(bottomLimit, newPan.y));

                        return customPan;
                    };

                    var panzoom = svgPanZoom('#flowersGrid', {
                        panEnabled: true,
                        controlIconsEnabled: false,
                        zoomEnabled: true,
                        dblClickZoomEnabled: false,
                        mouseWheelZoomEnabled: true,
                        preventMouseEventsDefault: true,
                        zoomScaleSensitivity: 0.2,
                        minZoom: 0.5,
                        maxZoom: 1.5,
                        fit: false,
                        contain: true,
                        center: true,
                        refreshRate: 'auto',
                        beforePan: beforePan,
                        customEventsHandler: eventsHandler
                    });
                } else {
                    console.log('datarequest error');
                }
            } else {
                if (data.messages && data.messages.length > 0) {
                    console.log('datarequest error');
                } else {
                    console.log('datarequest error');
                }
            }
        },
        'json'
    );
    $(svg).height("100%");
    $(svg).width("100%");
    svg.setAttribute("viewBox", "" + minX + " " + minY + " " + (maxX - minX) + " " + (maxY - minY));

    var flowerModal = $('#flowerModal');

    function openFlowerInfo(flowerId) {
        let flower = $('.flower[data-id=' + flowerId + ']');

        if (flower.length > 0) {
            let popUp = $('.flower-info'),
                owner = popUp.find('.flower-info__owner'),
                ownerLink = '';

            let h = $(svg).height(),
                w = $(svg).width(),
                ratio = 0;

            if (h < w) {
                ratio = h/(maxY-minY)
            } else {
                ratio = w/(maxX-minX)
            }

            if (flower.data('owner-name') !== null) {
                owner.html(flower.data('owner-name'));
                if (flower.data('owner-link') !== null) {
                    owner.attr('href', flower.data('owner-link'));
                } else {
                    owner.removeAttr('href');
                }
            } else {
                owner.html(owner.data('default')).removeAttr('href');
            }
            //popUp.css('left', $(this).offset().left  - popUp.outerWidth() / 2 + $(this).outerWidth() / 2).css('top', $(this).offset().top - popUp.outerHeight() - 170 );
            //console.log($(this).offset().left + ' - ' + popUp.outerWidth() * ratio / 2 + ' + ' + $(this).width() / 2);

            popUp.find('.flower-info__date').html('Расцвел ' + moment.unix(flower.data('timestamp')).fromNow());
            popUp.find('.flower-info__image').attr('style', 'background-image: url(' + flower.attr('href') + ')');

            popUp.find('.flower-info__socials a').each(function() {
                $(this).attr('href', String($(this).data('link')).replace('{link}', encodeURIComponent(flower.data('link'))));
                $('#copyLinkInput').val(flower.data('link'));
            });

            history.pushState(
                null,
                document.getElementsByTagName("title")[0].innerHTML,
                $.setUrlParameter(window.location, 'flower', flowerId)
            );

            flowerModal.modal('show');
            gardenInfoPos();
        }
    }

    flowerModal.on('hidden.bs.modal', function () {
        history.pushState(
            null,
            document.getElementsByTagName("title")[0].innerHTML,
            $.setUrlParameter(window.location, 'flower', null)
        );
    });

    // Show popup only if not drag
    $('body').on('mousedown', '.flower', function(evt) {
        $('.flower').on('mouseup mousemove', function handler(evt) {
            if (evt.type === 'mouseup') {
                openFlowerInfo($(this).data('id'));
            }
            $('.flower').off('mouseup mousemove', handler);
        });
    });

    $('.flower-info .tooltip__close').click(function () {
        setTimeout(function () {
            $('.flower-info__share-block').slideUp();
            $('.flower-info__arrow').show();
            $('.flower-info__btn-share').show();
        }, 300);
    });

    $('.flower-info__btn-share').click(function () {
        $('.flower-info__share-block').slideDown();
        $('.flower-info__arrow').hide();
        $(this).hide();
    });

    function gardenInfoPos() {
        var card = $('#infoCard');
        var minH = card.outerHeight() - card.find('.garden__name').outerHeight() - card.find('.garden__donate').outerHeight() - 40;
        var maxH = minH + card.find('.garden__donate').outerHeight() + 12;

        if ($(window).width() < 576) {
            //if ($('.flower-info').is(":visible")) {
            //    card.css('bottom', - maxH).css('top', 'inherit');
            //}
            //else {
            card.css('bottom', - minH).css('top', 'inherit');
            //}
        }
        else {
            card.removeAttr('style');
        }
    }

    gardenInfoPos();
    $(window).resize(function() {
        gardenInfoPos();
    });

    //pinch events for panzoom
    var eventsHandler;

    eventsHandler = {
        haltEventListeners: ['touchstart', 'touchend', 'touchmove', 'touchleave', 'touchcancel'],
        init: function(options) {
            var instance = options.instance,
                initialScale = 1,
                pannedX = 0,
                pannedY = 0;

            // Init Hammer
            // Listen only for pointer and touch events
            this.hammer = Hammer(options.svgElement, {
                inputClass: Hammer.SUPPORT_POINTER_EVENTS ? Hammer.PointerEventInput : Hammer.TouchInput
            });

            // Enable pinch
            this.hammer.get('pinch').set({enable: true});

            // Handle double tap
            this.hammer.on('doubletap', function(ev){
                instance.zoomIn()
            });

            // Handle pan
            this.hammer.on('panstart panmove', function(ev){
                // On pan start reset panned variables
                if (ev.type === 'panstart') {
                    pannedX = 0;
                    pannedY = 0;
                }

                // Pan only the difference
                instance.panBy({x: ev.deltaX - pannedX, y: ev.deltaY - pannedY});
                pannedX = ev.deltaX;
                pannedY = ev.deltaY;
            });

            // Handle pinch
            this.hammer.on('pinchstart pinchmove', function(ev){
                // On pinch start remember initial zoom
                if (ev.type === 'pinchstart') {
                    initialScale = instance.getZoom();
                    instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y})
                }

                instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y})
            });

            // Prevent moving the page on some devices when panning over SVG
            options.svgElement.addEventListener('touchmove', function(e){ e.preventDefault(); });
        }, destroy: function(){
            this.hammer.destroy()
        }
    };

    // Social Modal

    let $socialModal = $('#socialModal'),
        gardenHash = $socialModal.data('garden'),
        donationMadeCookie = Cookies.get('donation_made_' + gardenHash);

    if (typeof donationMadeCookie !== 'undefined') {
        $socialModal.modal('show');
    }

    $socialModal.on('hidden.bs.modal', function () {
        if (flowerParam !== null) {
            openFlowerInfo(flowerParam);
        }
    });
});
