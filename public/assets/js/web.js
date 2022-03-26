// Call the form for adding a comment
document.querySelectorAll(".add-reply")
  .forEach(el => el.addEventListener("click", function (e) {

    let id = this.dataset.id;
    let pid = this.dataset.pid;

    let reply = document.querySelector('#reply_addentry' + pid);
    reply.classList.add("block");

    fetch("/reply/addform", {
      method: "POST",
      body: "pid=" + pid + "&id=" + id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(
        response => {
          return response.text();
        }
      ).then(
        text => {
          reply.innerHTML = text;
        }
      );
  }));
