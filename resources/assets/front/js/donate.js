$(document).ready(function() {

    function displayError(errorBlock, text)
    {
        if (typeof text === 'undefined') {
            text = errorBlock.data('default');
        }
        if (text.endsWith('.') === false) {
            text = text + '.';
        }
        errorBlock.html(text).fadeIn();
    }

    function hideError(errorBlock)
    {
        errorBlock.fadeOut(300, function () {
            $(this).html('');
        });
    }

    $('#donateForm').submit(function (e) {
        e.preventDefault();

        let $form = $(this),
            $donateModal = $('#donateModal'),
            $paymentModal = $('#paymentModal'),
            $donateError = $('#donateError'),
            $submitButton = $form.find('#donateSubmitButton'),
            $paymentForm = $paymentModal.find('#paymentForm'),
            $flowersPreview = $paymentModal.find('#flowersPreview');

        $submitButton.prop('disabled', true);
        hideError($donateError);

        $.post(
            $form.attr('action'),
            $form.serialize(),
            function (response) {
                if (response.success === true) {
                    if (
                        response.data
                        && response.data.pay_url
                        && response.data.paid_url
                        && response.data.flowers_quantity
                        && response.data.flowers
                        && typeof response.data.flowers === 'object'
                    ) {
                        $donateModal.modal('hide');

                        $paymentForm.attr('action', response.data.pay_url);
                        $paymentForm.attr('data-paid-action', response.data.paid_url);

                        response.data.flowers.forEach(function (flower) {
                            $flowersPreview.prepend(`
                                <div class="flowers-preview__flower" style="background-image: url('${flower.image}');"></div>
                            `);
                        });

                        let $diffQuantity = response.data.flowers_quantity - response.data.flowers.length;
                        if ($diffQuantity > 0) {
                            $flowersPreview.append(`
                                <span class="flowers-preview__diff"">+${$diffQuantity}</span>
                            `);
                        }

                        $paymentModal.modal('show');
                    } else {
                        displayError($donateError);
                    }
                } else {
                    if (response.messages && response.messages.length > 0) {
                        displayError($donateError, response.messages[0]);
                    } else {
                        displayError($donateError);
                    }
                }
                $submitButton.prop('disabled', false);
            },
            'json'
        );
    });

    $('#paymentForm').submit(function (e) {
        e.preventDefault();

        let $form = $(this),
            $paymentError = $('#paymentError'),
            $submitButton = $form.find('#paymentSubmitButton');

        $submitButton.prop('disabled', true);
        hideError($paymentError);

        $.post(
            $form.attr('action'),
            $form.serialize(),
            function (response) {
                if (response.success === true) {
                    if (
                        response.data
                        && response.data.payment_data
                        && response.data.payment_data.provider_id
                        && response.data.payment_data.invoice_id
                        && response.data.payment_data.description
                        && response.data.payment_data.amount
                        && response.data.payment_data.currency
                        // && response.data.payment_data.email
                        // && response.data.payment_data.user_id
                        && response.data.payment_data.data
                        && typeof response.data.payment_data.data === 'object'
                    ) {
                        let paymentData = response.data.payment_data;

                        pay(
                            paymentData.provider_id,
                            paymentData.invoice_id,
                            paymentData.description,
                            paymentData.amount,
                            paymentData.currency,
                            paymentData.email,
                            paymentData.user_id,
                            paymentData.data
                        );
                    } else {
                        displayError($paymentError);
                    }
                } else {
                    if (response.messages && response.messages.length > 0) {
                        displayError($paymentError, response.messages[0]);
                    } else {
                        displayError($paymentError);
                    }
                }
                $submitButton.prop('disabled', false);
            },
            'json'
        );
    });

    function pay(providerId, invoiceId, description, amount, currency, email, userId, data = {})
    {
        let widget = new cp.CloudPayments(),
            $paymentForm = $('#paymentForm'),
            $paymentError = $('#paymentError'),
            $submitButton = $paymentForm.find('#paymentSubmitButton');

        widget.charge({
                publicId: providerId,
                invoiceId: invoiceId,
                description: description,
                amount: amount,
                currency: currency,
                accountId: userId,
                email: email,
                skin: 'mini',
                data: data,
                language: 'ru-RU'
            },
            function () { // Success
                $submitButton.prop('disabled', true);
                $.post(
                    $paymentForm.data('paid-action'),
                    {},
                    function (response) {
                        if (
                            response.success === true
                            && response.data.redirect
                        ) {
                            window.location.replace(response.data.redirect);
                        } else {
                            if (response.messages && response.messages.length > 0) {
                                displayError($paymentError, response.messages[0]);
                            } else {
                                displayError($paymentError);
                            }
                            $submitButton.prop('disabled', false);
                        }
                    },
                    'json'
                );
            },
            function (reason) { // Failure
                displayError($paymentError, reason);
            }
        );
    }

    // Clear donate modal on close
    $('#donateModal').on('hidden.bs.modal', function () {
        let $quantityInput = $(this).find('#quantityInput');
        $quantityInput.val($quantityInput.attr('min')).trigger('change');
    });

    // Clear payment modal on close
    $('#paymentModal').on('hidden.bs.modal', function () {
        $(this).find('#flowersPreview').html('');
        $(this).find('#paymentForm').attr('action', '')
            .attr('data-paid-action', '');
    });

    //--------------------------------------------------------------------------
    // Payment Counter

    $('#paymentCounter').each(function() {
        let spinner = $(this),
            input = spinner.find('#quantityInput'),
            pricePerItem = parseInt(input.data('price')),
            btnUp = spinner.find('.payment__number--increment'),
            btnDown = spinner.find('.payment__number--decrement'),
            min = input.attr('min'),
            sum = spinner.find('.payment__sum'),
            btn = $('#donateSubmitButton');

        input.on('keyup change', function () {
            if ($(this).val() <= 0 || $(this).val() == null) {
                $(this).val(1);
            }
            sum.find('i').html($(this).val() * pricePerItem);
            btn.html('Посадить ' + $(this).val() + ' ' + $.declOfNum($(this).val(), ['цветок', 'цветка', 'цветков']));
        });

        btnUp.click(function () {
            let oldValue = parseFloat(input.val()),
                newVal = oldValue + 1;
            spinner.find('input').val(newVal);
            spinner.find('input').trigger('change');
        });

        btnDown.click(function () {
            let oldValue = parseFloat(input.val()),
                newVal = oldValue <= min
                ? oldValue
                : (oldValue - 1);
            spinner.find('input').val(newVal);
            spinner.find('input').trigger('change');
        });
    });
});
