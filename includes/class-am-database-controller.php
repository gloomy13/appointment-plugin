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
                'time_start' => $appointment->time_start->format('c'),
                'time_end' => $appointment->time_end->format('c'),
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
}


?>