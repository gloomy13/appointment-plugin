jQuery(document).ready(function( $ ) {
    
    $('.am_appointment_remove_button').on('click', function(e){
        $.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            data:{
                action: 'ajax_remove_an_appointment',
                id: $(this).attr('appointment-id')
            },
            success: function (response){
                location.reload(true);
            }
        });
    });

    $('.am_pending_appointment_confirm_button').on('click', function(e){
        $.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            data:{
                action: 'ajax_confirm_an_appointment',
                id: $(this).attr('appointment-id')
            },
            success: function (response){
                location.reload(true);
            }
        });
    });

});