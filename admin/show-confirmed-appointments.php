<?php

require_once(AM_PLUGIN_PATH . 'includes/class-am-database-controller.php');

$pending_appointments = AM_Database_Controller::get_confirmed_appointments();

?>

<h2><?= __('Zatwierdzone rezerwacje', 'am_calendar'); ?></h2>

<div class="am_appointments_container">

    <?php foreach($pending_appointments as $pending_appointment): ?>

    <?php 
    $time_start_dt_object = new DateTime(strval($pending_appointment->time_start));
    $time_end_dt_object = new DateTime(strval($pending_appointment->time_end));
    ?>

    <div class="am_appointment confirmed">
        <div class="appointment_start_date"><?= date_format($time_start_dt_object, 'd-m-Y H:i'); ?></div>
        <div><?= date_format($time_end_dt_object, 'd-m-Y H:i'); ?></div>
        <div><?= $pending_appointment->name; ?></div>
        <div><?= $pending_appointment->phone_number; ?></div>
        <div><?= $pending_appointment->email_address; ?></div>
        <div><?= $pending_appointment->comment; ?></div>
        <div><a appointment-id="<?= $pending_appointment->id ?>" class="am_appointment_remove_button" href="#"><?= __('Usuń', 'am_calendar') ?></a></div>
    </div>

    <?php endforeach; ?>

</div>