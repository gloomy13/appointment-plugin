<?php 

class AM_Ajax_Controller{
	
	function init(){
		$this->add_ajax_actions();
	}

	function add_ajax_actions(){
        add_action('wp_ajax_ajax_next_month', [$this, 'ajax_next_month']);
        add_action('wp_ajax_nopriv_ajax_next_month', [$this, 'ajax_next_month']);

        add_action('wp_ajax_ajax_get_appointments_from_selected_day', [$this, 'ajax_get_appointments_from_selected_day']);
        add_action('wp_ajax_nopriv_ajax_get_appointments_from_selected_day', [$this, 'ajax_get_appointments_from_selected_day']);

        add_action('wp_ajax_ajax_make_an_appointment', [$this, 'ajax_make_an_appointment']);
        add_action('wp_ajax_nopriv_ajax_make_an_appointment', [$this, 'ajax_make_an_appointment']);

        add_action('wp_ajax_ajax_remove_an_appointment', [$this, 'ajax_remove_an_appointment']);
        // add_action('wp_ajax_nopriv_ajax_remove_an_appointment', [$this, 'ajax_remove_an_appointment']);

        add_action('wp_ajax_ajax_confirm_an_appointment', [$this, 'ajax_confirm_an_appointment']);
        // add_action('wp_ajax_nopriv_ajax_remove_an_appointment', [$this, 'ajax_remove_an_appointment']);

        add_action('wp_ajax_ajax_admin_save_absences', [$this, 'ajax_admin_save_absences']);
    }

	function ajax_next_month(){
        $offset = (int)$_POST['offset'];
        $next_month = $_POST['current_month'] + $offset;

        if($next_month > 12){
            $next_month = 1;
            $next_year = $_POST['current_year'] + 1;
        }
        else if($next_month < 1){
            $next_month = 12;
            $next_year = $_POST['current_year'] - 1;
        }
        else{
            $next_year = (int)$_POST['current_year'];
        }

        ob_start();
        

        require_once(AM_PLUGIN_PATH . 'frontend/renderable/template-main-calendar-render.php');


        wp_send_json_success(ob_get_clean());
    }

    function ajax_get_appointments_from_selected_day(){
        ob_start();

        $appointment_selected_day = $_POST['appointment_date'];

        require_once(AM_PLUGIN_PATH . 'includes/class-am-database-controller.php');

        $reserved_slots_ranges_array = [];

        $appointments_that_day = AM_Database_Controller::get_confirmed_appointments_by_day($appointment_selected_day);
        
        foreach($appointments_that_day as $appointment){
            $reserved_slots_ranges_array []= [$appointment->time_start, $appointment->time_end];
        }

        require_once(AM_PLUGIN_PATH . 'frontend/renderable/template-main-calendar-slots-render.php');

        wp_send_json_success(ob_get_clean());
    }

    function ajax_make_an_appointment(){
        $decodedData = urldecode($_POST['form']);
        parse_str($decodedData, $unserializedData);

        require_once(AM_PLUGIN_PATH . 'includes/class-am-appointment.php');

        $new_appointment = new AM_Appointment(
            (new DateTime($unserializedData['appointment_date_time'])), 
            (new DateTime($unserializedData['appointment_date_time_end'])),
            $unserializedData['name'],
            $unserializedData['phone'],
            $unserializedData['email'],
            $unserializedData['comment'],
        );

        require_once(AM_PLUGIN_PATH . 'includes/class-am-database-controller.php');

        AM_Database_Controller::insert_appointment($new_appointment);

        $response = __('Rezerwacja została złożona szczegóły zostaną przesłane drogą mailową' ,'am_calendar');

        wp_send_json_success($response);
    }

    function ajax_remove_an_appointment(){
        require_once(AM_PLUGIN_PATH . 'includes/class-am-database-controller.php');

        AM_Database_Controller::delete_appointment_by_id($_POST['id']);

        wp_send_json_success(true);
    }

    function ajax_confirm_an_appointment($id){
        require_once(AM_PLUGIN_PATH . 'includes/class-am-database-controller.php');

        AM_Database_Controller::confirm_appointment_by_id($_POST['id']);

        wp_send_json_success(true);
    }

    function ajax_admin_save_absences(){
        $absences = $_POST['absences'];

        $options = get_option('am_calendar_options');

        $options['absences'] = $absences;

        update_option('am_calendar_options', $options);

        wp_send_json_success(true);
    }
}

?>