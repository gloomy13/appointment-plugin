<?php
 
$am_calendar_settings = get_option('am_calendar_options');

$start_time = strtotime($am_calendar_settings['appointments_start_time']);
$end_time = strtotime($am_calendar_settings['appointments_end_time']);

$duration_in_minutes = $am_calendar_settings['appointments_duration'];

$number_of_slots = ($end_time-$start_time)/60/$duration_in_minutes;

?>

<?php for($i=0;$i<$number_of_slots;$i++): ?>
    <?php
    $formatted_start_time = gmdate("H:i", $start_time+(60 * $duration_in_minutes * $i));
    $formatted_end_time = gmdate("H:i", $start_time+(60 * $duration_in_minutes * ($i+1)))
    ?>
    <div class="am_calendar_day_slot">
        <label for="slot_<?= $i; ?>"><input type="radio" day="<?= $appointment_selected_day; ?>" time-start="<?= $formatted_start_time; ?>" time-end="<?= $formatted_end_time; ?>" name="am_slot" id="slot_<?= $i; ?>"><?= $formatted_start_time.' - '.$formatted_end_time; ?></label>
    </div>
<?php endfor; ?>