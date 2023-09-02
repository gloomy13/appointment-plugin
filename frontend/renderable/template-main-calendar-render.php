<?php 

// $ddate = "2012-10-18";


if(isset($next_month)){
    $date = new DateTime($next_year."-".$next_month."-01");
}
else{
    $date = new DateTime();
}

$day_of_the_week = $date->format("N");
$number_of_days_in_the_month = $date->format("t");
$current_year = $date->format("o");
$current_month = $date->format("m");

$first_day_of_the_month = (new DateTime($current_year . "-" . $current_month . "-01"))->format("N");

// for example if the first day of a month is a sunday then 6 empty boxes are being created in for mon-sat
$number_of_empty_day_boxes = ($first_day_of_the_month + 6) % 7;


?>

<div class="am_calendar_current_month_and_year"><?= $current_month . "-" .$current_year ?></div>
<div class="am_calendar_days_of_the_week">
    <div>pon</div>
    <div>wto</div>
    <div>śro</div>
    <div>czw</div>
    <div>pią</div>
    <div>sob</div>
    <div>nie</div>
</div>
<div class="am_calendar_grid">
    <?php $day_count = 1; ?>
    <?php for($i=0;$i<$number_of_empty_day_boxes;$i++): ?>
    
    <div class="am_calendar_day_box empty"></div>
    
    <?php endfor; ?>

    <?php for($i=1;$i<=$number_of_days_in_the_month;$i++): ?>
    
    <a <?php
        if(
            (!isset($next_month) && $i < (new DateTime())->format('j')) || 
            (isset($next_month) && $next_month < (new DateTime())->format('n')) ||
            (isset($next_month) && $next_month == (new DateTime())->format('n') && $i < (new DateTime())->format('j'))
        )
        { 
            echo 'class="inactive" disabled'; 
        } 
        ?> date="<?= $i.'-'.$current_month.'-'.$current_year ?>" href="#"><div class="am_calendar_day_box"><?= $i ?></div></a>

    <?php endfor; ?>
    
    <!-- 36 boxes for grid to always have a one empty box in the last row for non-changing height of the grid container -->
    <?php for($i=$number_of_empty_day_boxes+$number_of_days_in_the_month;$i<36;$i++): ?>
    
    <div class="am_calendar_day_box empty"></div>
    
    <?php endfor; ?>
</div>
<div class="am_calendar_next_month_container">
    <a current-month="<?= $current_month; ?>" current-year="<?= $current_year; ?>" class="am_calendar_next_month_button" offset="-1" href="#"><?= __('Poprzedni miesiąc', 'am_calendar'); ?></a>
    <a current-month="<?= $current_month; ?>" current-year="<?= $current_year; ?>" class="am_calendar_next_month_button" offset="1" href="#"><?= __('Następny miesiąc', 'am_calendar'); ?></a>
</div>