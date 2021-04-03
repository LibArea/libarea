$(function(){
    // Add comment
    $(document).on("click", ".addcomm", function(){
        var comm_id = $(this).data('id'); 
        var post_id = $(this).data('post_id');        
       
            $('.cm_addentry').remove();
            $('.cm_add_link').show();
            $link_span  = $('#cm_add_link'+comm_id);
            old_html    = $link_span.html();
           
            $.post('/comments/addform', {comm_id: comm_id, post_id: post_id}, function(data) {
                
                if(data){
                    
                    $("#cm_addentry"+comm_id).html(data).fadeIn();
                    $('#content').focus();
                    $link_span.html(old_html).hide();
                    $('#submit_cmm').click(function(data) {
                        $('#submit_cmm').prop('disabled', true);
                        $('#cancel_cmm').hide();
                        $('.submit_cmm').append('...');
                    });
                }
            });
    });
    $(document).on("click", "#cancel_cmm", function(){
        $('.cm_addentry').remove();
        $('.cm_add_link').show();
    });
});

// toggle dark mode
$(document).on('click', '#toggledark', function() {

    var mode = getCookie("dayNight");
    var d = new Date();
    d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); //365 days
    var expires = "expires=" + d.toGMTString();
    if (mode == "dark" || mode == "dank") {
      document.cookie = "dayNight" + "=" + "light" + "; " + expires + ";path=/";
      document.getElementsByTagName('body')[0].classList.remove('dark');
      document.getElementsByTagName('body')[0].classList.remove('dank');
      // document.querySelector('#toggledark span').innerHTML = icons.moon;
    } else {
      document.cookie = "dayNight" + "=" + "dark" + "; " + expires + ";path=/";
      document.getElementsByTagName('body')[0].classList.add('dark');
     // document.querySelector('#toggledark span').innerHTML = icons.sun;
    }
});
// Show hide popover
$(document).on("click", ".dropbtn", function(){       
    $('.dropdown-menu').addClass('fast');
    
    $('body, .dropbtn a').click(function () {
        $('.dropdown-menu').removeClass('fast');
    });
});
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
}
// Попробуем диалоговые окна
customElements.define('m-dialog', class extends HTMLElement {
  constructor() {
    super();
    this._boundClose = e => e.key === 'Escape' ? this.close() : null;
  }

  connectedCallback() {
    this.returnValue = null;

    // Close on esc keyup
    document.addEventListener('keydown', this._boundClose);

    // One time render stuff
    const container = document.createElement('div');
    const role = this.getAttribute('role') === 'alertdialog' ? 'alertdialog' : 'dialog';
    container.setAttribute('role', role);
    container.classList.add('pos-relative', 'pad-all-lg');

    const closeBtn = document.createElement('button');
    closeBtn.setAttribute('type', 'remove');
    closeBtn.setAttribute('aria-label', 'Close dialog');
    closeBtn.classList.add('pad-all-sm', 'txt-lg', 'pos-absolute', 'pin-t', 'pin-r');
    closeBtn.addEventListener('click', () => this.close());

    const content1 = document.createElement('div'); // Yes, both are needed
    const content2 = document.createElement('div');
    content2.append(...this.childNodes); // The supplied content
    content1.append(content2);

    container.append(closeBtn, content1);
    this.append(container);
  }

  disconnectedCallback() {
    document.removeEventListener('keydown', this._boundClose);
  }

  static get observedAttributes() { return ['open']; }

  attributeChangedCallback(name, oldVal, newVal) {
    switch (name) {
      case 'open':
        if (newVal === null) this.close();
        if (newVal === '') {
          // Good UX and HTMLDialogElement spec says to do it
          const firstAutofocusField = this.querySelector('[autofocus]');
          if (firstAutofocusField) { firstAutofocusField.focus() }
        }
    }
  }

  get open() {
    return this.hasAttribute('open');
  }

  set open(isOpen) {
    isOpen ? this.setAttribute('open', '') : this.removeAttribute('open');
  }

  // MDN: "Closes the dialog. An optional DOMString may be passed as an argument, updating the returnValue of the the dialog."
  close(returnValue) {
    this.returnValue = returnValue || this.returnValue;
    this.style.pointerEvents = 'auto';
    this.open = false;
    this.dispatchEvent(new CustomEvent('close')); // MDN: "Fired when the dialog is closed."
  }

  // MDN: "Displays the dialog modelessly, i.e. still allowing interaction with content outside of the dialog."
  show() {
    this.style.pointerEvents = 'none'; // To "allow interaction outside dialog"
    this.open = true;
  }

  // MDN: "Displays the dialog as a modal, over the top of any other dialogs that might be present. Interaction outside the dialog is blocked."
  showModal() {
    this.open = true;
  }
}); 
