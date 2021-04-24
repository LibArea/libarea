document.addEventListener('DOMContentLoaded', function() {
    setInterval(ping,15000);
    function ping() {
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
    }
    ping();
});
