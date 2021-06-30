(function($) {
    let contactBtn = $('#send-contact-btn')
    contactBtn.on('click', (e) => {
        e.preventDefault()
        fetch('http://franz-atelier.local/xhr/contact')
            .then(response => response.json())
            .then(data => console.log(data));
    })
})(jQuery)