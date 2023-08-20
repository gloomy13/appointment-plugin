<?php
 
if(!empty(get_option('am_calendar_options'))){
    $am_calendar_settings = get_option('am_calendar_options');
}
else{
    $am_calendar_settings = [
        'appointments_start_time' => '8:00',
        'appointments_end_time' => '16:00',
        'appointments_duration' => '120'
    ];
}

$start_time_setting = strtotime($am_calendar_settings['appointments_start_time']);
$end_time_setting = strtotime($am_calendar_settings['appointments_end_time']);

$duration_in_minutes = $am_calendar_settings['appointments_duration'];

$number_of_slots = ($end_time_setting-$start_time_setting)/60/$duration_in_minutes;

?>

<?php for($i=0;$i<$number_of_slots;$i++): ?>
    <?php
    $skip = false;

    $formatted_start_time = date("H:i", $start_time_setting+(60 * $duration_in_minutes * $i));
    $formatted_end_time = date("H:i", $start_time_setting+(60 * $duration_in_minutes * ($i+1)));

    if(isset($reserved_slots_ranges_array)){
        $start_time_date = date('Y-m-d H:i:s', strtotime($appointment_selected_day.' '.$formatted_start_time));
        $end_time_date = date('Y-m-d H:i:s', strtotime($appointment_selected_day.' '.$formatted_end_time));

        foreach($reserved_slots_ranges_array as $reserved_slots_range){
            $reserved_slot_start_time_date = date('Y-m-d H:i:s', strtotime($reserved_slots_range[0]));
            $reserved_slot_end_time_date = date('Y-m-d H:i:s', strtotime($reserved_slots_range[1]));

            if($start_time_date < $reserved_slot_end_time_date && $end_time_date > $reserved_slot_start_time_date){
                $skip = true;
                break;
            }
        }
    }

    if($formatted_end_time > date("H:i", $end_time_setting)){
        $skip = true;
    }

    if($skip){
        continue;
    }
    ?>
    <div class="am_calendar_day_slot">
        <label for="slot_<?= $i; ?>"><input type="radio" day="<?php if(isset($appointment_selected_day)){ echo $appointment_selected_day; } ; ?>" time-start="<?= $formatted_start_time; ?>" time-end="<?= $formatted_end_time; ?>" name="am_slot" id="slot_<?= $i; ?>"><?= $formatted_start_time.' - '.$formatted_end_time; ?></label>
    </div>
<?php endfor; ?>