(function($) {
    let contactForm = $('#fr-contact-form')
    contactForm.on('submit', (e) => {
        e.preventDefault()
        const formData = {
            name: $('#fr-contact-name').val(),
            email: $('#fr-contact-email').val(),
            message: $('#fr-contact-message').val(),
        }
        $.ajax({
            method: 'POST',
            url: '/xhr/contact',
            data: formData,
            dataType: 'json',
        })
            .done((response) => {
                if (response.status === 'success') {
                    document.querySelector('#fr-contact-form').reset()
                    const successAlert = '<div class="alert alert-success alert-dismissible fade show g-mb-40" role="alert">' +
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
                }
            })
    })
})(jQuery)