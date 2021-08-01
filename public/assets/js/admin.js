$(function(){
    // Забанить / разбанить участника
    $(document).on('click', '.user-ban', function() {
        let user_id  = $(this).data('id');
        fetch("/admin/user/ban", { 
            method: "POST",
            body: "id=" + user_id,
            headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
            })
            .then((response) => {
                location.reload();                
            })
    });  
   
    // Запишем Favicon
    $(document).on('click', '.add-favicon', function() {
        let link_id  = $(this).data('id');
        fetch("/admin/favicon/add", { 
            method: "POST",
            body: "id=" + link_id,
            headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
            })
            .then((response) => {
                location.reload();                
            }) 
    });
   
    // Удалим стоп-слово
    $(document).on('click', '.delete-word', function() {
        let word_id  = $(this).data('id');
        fetch("/admin/word/del", { 
            method: "POST",
            body: "id=" + word_id,
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
    
    // Восстановление контента
    $(document).on('click', '.audit-status', function() {
        let status_id  = $(this).data('id');
        let status_type = $(this).data('status');
        fetch("/admin/audit/status", { 
            method: "POST",
            body: "status=" + status_id + "@" + status_type,
            headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
            })
            .then((response) => {
                location.reload();                
            }) 
    });
});