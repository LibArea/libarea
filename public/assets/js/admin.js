$(function(){
    // Забанить / разбанить участника
    $(document).on('click', '.user-ban', function() {
        let user_id  = $(this).data('id');
        fetch("/admin/ban", { 
            method: "POST",
            body: "id=" + user_id,
            headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
            })
            .then((response) => {
                location.reload();                
            })
    });  
   
    // Восстанавливаем комментарий
    $(document).on('click', '.recover-comm', function() {
        let comm_id  = $(this).data('id');
        fetch("/admin/comment/recover", { 
            method: "POST",
            body: "id=" + comm_id,
            headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
            })
            .then((response) => {
                location.reload();                
            }) 
    });

    // Удаление / восстановление пространства
    $(document).on('click', '.space-ban', function() {
        let space_id  = $(this).data('id');
        fetch("/admin/space/ban", { 
            method: "POST",
            body: "id=" + space_id,
            headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
            })
            .then((response) => {
                location.reload();                
            }) 
    });
});