document.querySelectorAll(".focus-user")
  .forEach(el => el.addEventListener("click", function(e) {
    let id = el.dataset.id;
    let slug = el.dataset.slug;  
    let content = document.querySelector('.list_' + id);
    fetch('/topic/' + slug + '/followers/' + id, {
        method: "POST",
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      })
      .then(
        response => {
          return response.text();
        }
      ).then(
        text => {
          content.innerHTML = text;
        }
      );
}));