window.onload=function(){
    // Забанить / разбанить участника
    var ban = document.querySelector('.user-ban');
    if(ban) {
        ban.addEventListener('click', function () {
            
            var user_id  = ban.dataset.id;
            fetch("/admin/ban", { 
                method: "POST",
                body: "id=" + user_id,
                headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
                })
                .then((response) => {
                    location.reload();                
                })
        });  
    }
    
    // Восстанавливаем комментарий
    var comm = document.querySelector('.recover-comm');
    if(comm) {
        comm.addEventListener('click', function () {
            
            var comm_id  = comm.dataset.id;
            fetch("/admin/comment/recover", { 
                method: "POST",
                body: "id=" + comm_id,
                headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
                })
                .then((response) => {
                    location.reload();                
                }) 
        });
    }
    
    // Удаление / восстановление пространства
    var space = document.querySelector('.space-ban');
    if(space) {
        space.addEventListener('click', function () {
            
            var space_id  = space.dataset.id;
            fetch("/admin/space/ban", { 
                method: "POST",
                body: "id=" + space_id,
                headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
                })
                .then((response) => {
                    location.reload();                
                }) 
        });
    }
    
} 