jQuery(document).ready(function( $ ) {
	console.log('test');

    $('.am_calendar').on('click', '.am_calendar_next_month_button', function(e){
        e.preventDefault();
        const currentMonth = parseInt($(this).attr('current-month'), 10);

        $.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            data:{
                action: 'ajax_next_month',
                current_month: currentMonth
            },
            success: function(response){
                console.log(response.data);
                $('.am_calendar').html(response.data);
            }
        });

        console.log($(this).attr('current-month'));
    });
});