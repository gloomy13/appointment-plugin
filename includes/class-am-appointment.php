<?php

class AM_Appointment{
    public $time_start;
    public $time_end;

    public $name;
    public $phone_number;
    public $email_address;

    public $comment;

    public $confirmed;


    function __construct($time_start, $time_end, $name, $phone_number, $email_address, $comment){
        $this->time_start = $time_start;
        $this->time_end = $time_end;
        $this->name = $name;
        $this->phone_number = $phone_number;
        $this->email_address = $email_address;
        $this->comment = $comment;

        $this->confirmed = false;
    }
}

?>