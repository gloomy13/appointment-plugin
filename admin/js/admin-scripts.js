jQuery(document).ready(function( $ ) {
    $('.am_calendar').on('click', '.am_calendar_next_month_button', function(e){
        e.preventDefault();
        const currentMonth = parseInt($(this).attr('current-month'), 10);

        const currentYear = $(this).attr('current-year')

        $.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            data:{
                action: 'ajax_next_month',
                current_month: currentMonth,
                current_year: currentYear,
                offset: $(this).attr('offset')
            },
            success: function(response){
                $('.am_calendar_rendered_container').html(response.data);
            }
        });

        console.log($(this).attr('current-month'));
    });
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

    $('.am_calendar.admin').ready(function(){
        let absences = [];

        $('.am_calendar.admin').on('click', '.am_calendar_grid > a', function(e){
            e.preventDefault();
    
            if(!$(this).hasClass('selected'))
            {
                absences.push($(this).attr('date'));
                $(this).addClass('selected');
            }
            else
            {
                absences.splice( $.inArray($(this).attr('date'), absences), 1 );
                $(this).removeClass('selected');
            }

            console.table(absences);
        });

        $('.am_calendar_save_absences').on('click', function(){
            $.ajax({
                type: 'post',
                url: myAjax.ajaxurl,
                data:{
                    action: 'ajax_admin_save_absences',
                    absences: absences
                },
                success: function (response){
                    // location.reload(true);
                }
            });
        })

    });

    

});