// Call the form for adding a comment
document.querySelectorAll(".addreply")
  .forEach(el => el.addEventListener("click", function (e) {
    let id = this.dataset.id;
    let item_id = this.dataset.item_id;
    let reply = document.querySelector('#reply_addentry' + id);

    fetch("/reply/addform", {
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
// Edit reply
document.querySelectorAll(".editreply")
  .forEach(el => el.addEventListener("click", function (e) {
    let id = el.dataset.id;
    let item_id = el.dataset.item_id;
    let reply = document.querySelector('#reply_addentry' + id);

    fetch("/reply/editform", {
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
          document.getElementById("reply_" + id).classList.add("edit");
          reply.classList.add("block");
          reply.innerHTML = text;

          document.querySelectorAll("#cancel_comment")
            .forEach(el => el.addEventListener("click", function (e) {
              reply.classList.remove("block");
            }));
        }
      );
}));  