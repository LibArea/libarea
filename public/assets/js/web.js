// Call the form for adding / edit a comment
document.querySelectorAll(".actreply")
  .forEach(el => el.addEventListener("click", function (e) {
    let id = this.dataset.id;
    let item_id = this.dataset.item_id;
    let type = this.dataset.type;
    let reply = document.querySelector('#reply_addentry' + id);

    fetch("/reply/" + type, {
      method: "POST",
      body: "id=" + id + "&item_id=" + item_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(
        response => {
          return response.text();
        }
      ).then(
        text => {
          reply.classList.add("block");
          reply.innerHTML = text;
          
          document.querySelectorAll("#cancel_comment")
            .forEach(el => el.addEventListener("click", function (e) {
              reply.classList.remove("block");
            }));
          
        }
      );
  }));
