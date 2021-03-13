$(function(){
    // Vote for comments
    $(document).on('click', '.comm-up-id', function() {
        var comm_id = $(this).data('id');
        $.ajax({
            url: '/votes/' + comm_id,
            type: 'POST',
            data: {comm_id: comm_id},
        }).done(function(data) {
            $('#up' + comm_id + '.voters').addClass('active');
            $('#up' + comm_id).find('.score').html('+');
        });
    });
});