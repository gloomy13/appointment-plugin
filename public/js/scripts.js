jQuery(document).ready(function( $ ) {
	console.log('test');

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
                current_year: currentYear
            },
            success: function(response){
                $('.am_calendar_rendered_container').html(response.data);
            }
        });

        console.log($(this).attr('current-month'));
    });

    $('.am_calendar').on('click', '.am_calendar_grid > a:not(.inactive)', function(e){
        e.preventDefault();

        const appointment_date = $(this).attr('date');

        $.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            data:{
                action: 'ajax_get_appointments_from_selected_day',
                appointment_date: appointment_date
            },
            success: function(response){
                $('.am_calendar_day_slots').html(response.data);
            }
        });

        $('.am_calendar_popup').addClass('active');
    });

    $('.am_calendar_popup_close').on('click', function(e){
        $('.am_calendar_popup').removeClass('active');
        step_1();
    });

    $('.am_calendar_popup').on('click', function(e){
        if( e.target !== this) return;

        if($(this).hasClass('active')){
            $(this).removeClass('active');
        }

        // go back to step 1
        step_1();
    });

    $('.am_calendar_slot_selection').on('click', function(e){
        e.preventDefault();

        const selected_slot = $('input[name="am_slot"]:checked');

        $('input[name="appointment_date_time"]').val(selected_slot.attr('day') + ' ' + selected_slot.attr('time-start'));
        $('input[name="appointment_date_time_end"]').val(selected_slot.attr('day') + ' ' + selected_slot.attr('time-end'));

        // proceed to step 2
        step_2();
    });

    // steps for appointment form
    function step_1(){
        $('.am_calendar_day_slots_container').show();
        $('.am_calendar_client_info_container').hide();
    }

    function step_2(){
        $('.am_calendar_day_slots_container').hide();
        $('.am_calendar_client_info_container').show();
    }

    $('form#am_client_info_form').on('submit', function(e){
        e.preventDefault();
        console.log("form");
        $.ajax({
            type: 'post',
            url: myAjax.ajaxurl,
            data:{
                action: 'ajax_make_an_appointment',
                form: $(this).serialize()
            },
            success: function(response){
                console.log(response.data);
            }
        });
    });
});