// Call the form for adding / edit a comment
document.querySelectorAll(".actreply")
  .forEach(el => el.addEventListener("click", function (e) {
    let reply = document.querySelector('#reply_addentry' + el.dataset.id);  
    fetch("/reply/" + el.dataset.type, {
      method: "POST",
      body: "id=" + el.dataset.id  + "&item_id=" + el.dataset.item_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(response => response.text())
    .then( text => { 
        reply.classList.add("block");
        reply.innerHTML = text;
        document.querySelectorAll("#cancel_comment")
          .forEach(el => el.addEventListener("click", function (e) {
            reply.classList.remove("block");
      }));
    });
  }));   
