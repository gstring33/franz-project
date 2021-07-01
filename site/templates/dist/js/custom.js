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
            type: 'POST',
            url: '/xhr/contact',
            data: formData,
            dataType: 'application/json'
        }).done((data) => {
           console.log(data)
        })
    })
})(jQuery)