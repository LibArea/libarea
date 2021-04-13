document.addEventListener('DOMContentLoaded', function() {
    setInterval(ping,15000);
    function ping() 
    {
        $.ajax({  
                url: "/flow/content",  
                cache: false,  
                success: function(html){  
                    $("#content").html(html);  
                }  
            }); 
        }

    ping();
}); 
