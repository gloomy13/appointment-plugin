<?php 

$is_admin = true;

require_once(AM_PLUGIN_PATH . "frontend/template-main-calendar.php");

?>

<input class="am_calendar_save_absences" type="submit" value="<?= __('Zapisz','am_calendar') ?>">

<style>
    <?php include(AM_PLUGIN_PATH . 'public/css/main.css'); ?>
</style>

<?php

echo'<code>'.__FILE__.':'.__LINE__.'</code>';
echo '<pre>';
    print_r([get_option('am_calendar_options')]);
echo '</pre>';

?>