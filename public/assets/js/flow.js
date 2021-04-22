document.addEventListener('DOMContentLoaded', function() {
    
    var content = document.querySelector('#content');
    let promise = fetch('/flow/content');

    promise.then(
        response => {
            return response.text();
        }
    ).then(
        text => {
            content.innerHTML = text;
        }
    );
    setTimeout(window.location.reload.bind(window.location), 12500);
});
