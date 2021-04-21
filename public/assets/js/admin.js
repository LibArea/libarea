window.onload=function(){
    // Забанить / разбанить участника
 
    var ban = document.querySelector('.user-ban');
    if(ban) {
        ban.addEventListener('click', function (e) {
            var user_id  = ban.dataset.id;
            const request = new XMLHttpRequest();
            
            fetch("/admin/ban/" + user_id, { 
                method: "POST",
                headers:{"content-type": "application/x-www-form-urlencoded"} 
                })
               
                .then((response) => {
                    location.reload();                
                })
        });  
    }
    
    // Восстанавливаем комментарий
    var comm = document.querySelector('.recover-comm');
    if(comm) {
        comm.addEventListener('click', function (e) {
            var comm_id  = comm.dataset.id;
            const request = new XMLHttpRequest();
            
            fetch("/admin/comment/recover/" + comm_id, { 
                method: "POST",
                headers:{"content-type": "application/x-www-form-urlencoded"} 
                })
               
                .then((response) => {
                    location.reload();                
                }) 
        });
    }
    
    // Удаление / восстановление пространства
    var space = document.querySelector('.space-ban');
    if(space) {
        space.addEventListener('click', function (e) {
            var space_id  = space.dataset.id;
            const request = new XMLHttpRequest();
            
            fetch("/admin/space/ban/" + space_id, { 
                method: "POST",
                headers:{"content-type": "application/x-www-form-urlencoded"} 
                })
               
                .then((response) => {
                    location.reload();                
                }) 
        });
    }
    
} 