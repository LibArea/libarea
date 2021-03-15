$(function(){
    // Выбор тегов
    $(".js-example-placeholder-multiple").select2({
                tags: true,
                tokenSeparators: [',', ' '],
                placeholder: {
                text: 'Выберите теги'
            }
        });
    
});