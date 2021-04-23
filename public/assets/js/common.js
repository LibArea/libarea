// Call the form for adding a comment
document.querySelectorAll(".addcomm")
  .forEach(el => el.addEventListener("click",  function(e){ 
  
    var comm_id  = this.dataset.id;
    var post_id  = this.dataset.post_id;
    
    var comm = document.querySelector('#cm_addentry'+comm_id);
    var sss = comm.classList.add("active");
     
    fetch("/comments/addform", { 
        method: "POST",
        body: "comm_id=" + comm_id + "&post_id=" + post_id,
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
        
        document.querySelectorAll('#cm_addentry_noauth'+comm_id)
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

// Personal drop-down menu
let hamburger = document.querySelector('.dropbtn');
let menu = document.querySelector('.dropdown-menu');
const toggleMenu = () => {
  menu.classList.toggle('show');
}
hamburger.addEventListener('click', e => {
  e.stopPropagation();
  toggleMenu();
});
document.addEventListener('click', e => {
  let target = e.target;
  let its_menu = target == menu || menu.contains(target);
  let its_hamburger = target == hamburger;
  let menu_is_active = menu.classList.contains('show');
  
  if (!its_menu && !its_hamburger && menu_is_active) {
    toggleMenu();
  }
})
   
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