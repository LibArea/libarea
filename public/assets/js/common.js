// Call the form for adding a comment
document.querySelectorAll(".addcomm")
  .forEach(el => el.addEventListener("click",  function(e){ 
  
    var answ_id  = this.dataset.answ_id;
    var post_id  = this.dataset.post_id;
    
    var comm = document.querySelector('#answ_addentry'+answ_id);
        comm.classList.add("active");
     
    fetch("/comments/addform", { 
        method: "POST",
        body: "answ_id=" + answ_id + "&post_id=" + post_id,
        headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
        })
        .then(
            response => {
                return response.text();
            }
        ).then(
            text => {
                comm.innerHTML = text;
            }
        );
        
        document.querySelectorAll('#cm_addentry_noauth'+answ_id)
            .forEach(el => el.addEventListener("click",  function(e){ 
           // console.log(e);
            if(e.path) {
                e.path[3].classList.remove("active");
            }
        }));
         
})); 

document.querySelectorAll(".addcomm_re")
  .forEach(el => el.addEventListener("click",  function(e){ 
  
    var post_id  = this.dataset.post_id;
    var answ_id  = this.dataset.answ_id;
    var comm_id  = this.dataset.comm_id;
    
    var comm = document.querySelector('#comm_addentry'+comm_id);
        comm.classList.add("active");
     
    fetch("/comments/addform", { 
        method: "POST",
        body: "answ_id=" + answ_id + "&post_id=" + post_id + "&comm_id=" + comm_id,
        headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
        })
        .then(
            response => {
                return response.text();
            }
        ).then(
            text => {
                comm.innerHTML = text;
            }
        );
        
        document.querySelectorAll('#comm_addentry_noauth'+answ_id)
            .forEach(el => el.addEventListener("click",  function(e){ 
           // console.log(e);
            if(e.path) {
                e.path[3].classList.remove("active");
            }
        }));
         
})); 


// We will show a preview of the post on the central page
document.querySelectorAll(".showpost")
  .forEach(el => el.addEventListener("click",  function(e){ 
  
    var post_id  = this.dataset.post_id;
    var post = document.querySelector('.s_'+post_id);   
    post.classList.remove("hide");
    
    if(!e.target.classList.contains('showpost')){
        post.classList.add("hide");
    }
    
    fetch("/post/shown", { 
            method: "POST",
            body: "post_id=" + post_id,
            headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
            })
            .then(
                response => {
                    return response.text();
                }
            ).then(
                text => {
                    post.innerHTML = text;
                }
            );
}));

// Toggle dark mode
var toggledark = document.querySelector('#toggledark');
if(toggledark) {
    toggledark.addEventListener('click', function () {
        var mode = getCookie("dayNight");
        var d = new Date();
        d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); //365 days
        var expires = "expires=" + d.toGMTString();
        if (mode == "dark") {
          document.cookie = "dayNight" + "=" + "light" + "; " + expires + ";path=/";
          document.getElementsByTagName('body')[0].classList.remove('dark');
        } else {
          document.cookie = "dayNight" + "=" + "dark" + "; " + expires + ";path=/";
          document.getElementsByTagName('body')[0].classList.add('dark');
        }
    });
}

// Add Header Post
var header = document.getElementById("stHeader");
if(header) {
    window.onscroll = function() {myFunction()};
    var sticky = header.offsetTop;
    function myFunction() {
      if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
      } else {
        header.classList.remove("sticky");
      }
    }
} 

// Toggle menu mode (To combine)
// Combined with the following, it might be worth changing the css itself 
// to make it easier to change the visible position of the menu 
var togglemenuoff = document.querySelector('.togglemenuoff');
var togglemenu = document.querySelector('.togglemenu');
if(togglemenuoff) {
    togglemenuoff.addEventListener('click', function () {
        var mode = getCookie("menuS");
        var d = new Date();
        d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); //365 days
        var expires = "expires=" + d.toGMTString();
        if (mode == "menuno") {
          document.cookie = "menuS" + "=" + "light" + "; " + expires + ";path=/";
          document.getElementsByTagName('body')[0].classList.remove('menuno');
        } else {
          document.cookie = "menuS" + "=" + "menuno" + "; " + expires + ";path=/";
          document.getElementsByTagName('body')[0].classList.add('menuno');
        }
    });
}
if(togglemenu) {
    togglemenu.addEventListener('click', function () {
        var mode = getCookie("menuS");
        var d = new Date();
        d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); //365 days
        var expires = "expires=" + d.toGMTString();
        if (mode == "menuno") {
          document.cookie = "menuS" + "=" + "light" + "; " + expires + ";path=/";
          document.getElementsByTagName('body')[0].classList.remove('menuno');
        } else {
          document.cookie = "menuS" + "=" + "menuno" + "; " + expires + ";path=/";
          document.getElementsByTagName('body')[0].classList.add('menuno');
        }
    });
}
// TODO: move to util
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
};