document.querySelectorAll(".share-btn a")
    .forEach(el => el.addEventListener("click", function (e) {
        var social = el.dataset.id;
        var url_share = location.href;
        share(social, url_share);
    }));

function share(social, url_share) {
    var url_soc = false;
    switch (social) {
        case "vk":
            url_soc = "https://vk.com/share.php?url=" + url_share;
            break;
        case "fb":
            url_soc = "https://www.facebook.com/sharer/sharer.php?u=" + url_share;
            break;
        case "tw":
            url_soc = "https://twitter.com/intent/tweet?url=" + url_share;
            break;
    }

    if (url_soc) {
        var width = 800, height = 500;
        var left = (window.screen.width - width) / 2;
        var top = (window.screen.height - height) / 2;

        social_window = window.open(url_soc, "share_window", "height=" + height + ",width=" + width + ",top=" + top + ",left=" + left);

        social_window.focus();
    }
}