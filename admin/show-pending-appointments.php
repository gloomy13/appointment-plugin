<?php

require_once(AM_PLUGIN_PATH . 'includes/class-am-database-controller.php');

$pending_appointments = AM_Database_Controller::get_pending_appointments();

?>

<h2>AYO REZERWACJE</h2>

<div class="am_pending_appointments_container">

    <?php foreach($pending_appointments as $pending_appointment): ?>

    <div class="am_pending_appointment">
        <div><?= $pending_appointment->time_start; ?></div>
        <div><?= $pending_appointment->time_end; ?></div>
        <div><?= $pending_appointment->name; ?></div>
        <div><?= $pending_appointment->phone_number; ?></div>
        <div><?= $pending_appointment->email_address; ?></div>
        <div><?= $pending_appointment->comment; ?></div>
        <div><a class="am_pending_appointment_reject_button" href="#"><?= __('Odrzuć', 'am_calendar') ?></a></div>
        <div><a class="am_pending_appointment_accept_button" href="#"><?= __('Zatwierdź', 'am_calendar') ?></a></div>
    </div>

    <?php endforeach; ?>

</div>