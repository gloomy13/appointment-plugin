<?php

require_once( ABSPATH . 'wp-load.php' );

class AM_Database_Controller{
    function init(){
        // register_activation_hook( __FILE__, [$this, 'create_appointments_table' ]);
        $this->create_appointments_table();
    }

    function create_appointments_table(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'am_appointments';
    
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time_start datetime NOT NULL,
            time_end datetime NOT NULL,
            name varchar(255) NOT NULL,
            phone_number varchar(20) NOT NULL,
            email_address varchar(255) NOT NULL,
            comment text NOT NULL,
            confirmed tinyint(1) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    static function insert_appointment(AM_Appointment $appointment) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'am_appointments';
    
        $wpdb->insert(
            $table_name,
            array(
                'time_start' => date_format($appointment->time_start, 'Y-m-d H:i:s'),
                'time_end' => date_format($appointment->time_end, 'Y-m-d H:i:s'),
                'name' => $appointment->name,
                'phone_number' => $appointment->phone_number,
                'email_address' => $appointment->email_address,
                'comment' => $appointment->comment,
                'confirmed' => $appointment->confirmed ? 1 : 0,
            )
        );
    }

    static function get_pending_appointments(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'am_appointments';
    
        $results = $wpdb->get_results( 
                $wpdb->prepare( "SELECT * FROM $table_name WHERE confirmed=0")
        );

        return $results;
    }

    static function get_confirmed_appointments(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'am_appointments';
    
        $results = $wpdb->get_results( 
                $wpdb->prepare( "SELECT * FROM $table_name WHERE confirmed=1")
        );

        return $results;
    }

    static function delete_appointment_by_id($id){
        global $wpdb;
        $table_name = $wpdb->prefix . 'am_appointments';

        return $wpdb->delete(
            $table_name, 		// table name with dynamic prefix
            ['id' => $id], 						// which id need to delete
            ['%d'], 							// make sure the id format
        );
    }

    static function confirm_appointment_by_id($id){
        global $wpdb;
        $table_name = $wpdb->prefix . 'am_appointments';

        return $wpdb->update(
            $table_name,
            ['confirmed' => 1],
            ['id' => $id]
        );
    }

    static function get_confirmed_appointments_by_day($day){
        global $wpdb;
        $table_name = $wpdb->prefix . 'am_appointments';

        // $day_dt_object = new DateTime($day);
        $formatted_day = date('Y-m-d', strtotime($day));
    
        $results = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM $table_name 
                WHERE time_start >= %s AND time_start < %s AND confirmed=1", $formatted_day, date('Y-m-d', strtotime($day . ' +1 day'))
        ));

        return $results;
    }
}


?>