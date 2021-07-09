(function($) {
    let contactForm = $('#fr-contact-form')
    contactForm.on('submit', (e) => {
        e.preventDefault()
        sanitizeFields()

        const formData = {
            name: $('#fr-contact-name').val(),
            email: $('#fr-contact-email').val(),
            message: $('#fr-contact-message').val(),
            pdm_name: $('#fr-contact-name-pdm').val(),
            pdm_email: $('#fr-contact-email-pdm').val(),
        }

        if (isSpam(formData)) {
            return
        } else {
            submitLoadBtn()
            formData.token = grecaptcha.getResponse()
            $.ajax({
                method: 'POST',
                url: '/xhr/contact',
                data: formData,
                dataType: 'json',
            })
                .done((response) => {
                    if (response.status === 'success') {
                        $('#fr-contact-form').hide()
                        const successAlert = '<div class="alert alert-success alert-dismissible fade show g-mb-40" role="alert" id="contact-alert-success">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">×</span>' +
                            ' </button>' +
                            '<h4 class="h5">' +
                            '<i class="fa fa-check-circle-o"></i> ' +
                            ' Ihre Nachricht wurde erfolgreich gesendet' +
                            '</h4>' +
                            response.message +
                            '</div>'
                        $(successAlert).insertBefore(contactForm)
                        $('#contact-alert-success').on('closed.bs.alert', function() {
                            location.reload()
                        })
                    } else if (response.status === 'error') {
                        submitSendBtn()
                        response.errors.forEach((error) => {
                            const field = error.field
                            if (error.field === 'recaptcha') {
                                $('#fr-contact-feedback-' + field).text(error.message)
                            } else {
                                const input = $('#fr-contact-' + field )
                                input.addClass('form-control-danger').removeClass('g-brd-gray-light-v4')
                                $('#fr-contact-feedback-' + field).text(error.message)
                                input.parent('.form-group').addClass('has-danger')
                            }
                        })
                    }
                })

        }
    })

    const sanitizeFields = () => {
        const fields = $('.form-control')
        fields.each(function () {
            $(this).removeClass('form-control-danger').addClass('g-brd-gray-light-v4')
            $(this).parent('.form-group').removeClass('has-danger')
            $(this).next('.form-control-feedback').empty()
        })
        $('#fr-contact-feedback-recaptcha').empty()
    }

    const isSpam = (fields) => {
        return (fields.pdm_name !== "" || fields.pdm_email !== "")
    }

    const submitLoadBtn = function () {
        $('#btn-contact-submit').empty().html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> LOADING')
    }

    const submitSendBtn = function () {
        $('#btn-contact-submit').empty().text('SENDEN')
    }
})(jQuery)