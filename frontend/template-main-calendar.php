<div class="am_calendar">
    <div class="am_calendar_popup">
        <div>
            <a class="am_calendar_popup_close">X</a>
            <div class="am_calendar_day_slots_container">
                <div class="am_calendar_day_slots">
                    <?php require_once('renderable/template-main-calendar-slots-render.php'); ?>
                </div>
                <a class="am_calendar_slot_selection" href="#"><?= __( 'Rezerwacja', 'am_calendar') ?></a>
            </div>
            <div class="am_calendar_client_info_container">
                <form action="" id="am_client_info_form">
                    <label for="name"><?= __('Imię i nazwisko', 'am_calendar'); ?><input type="text" name="name" id="am_name"></label>
                    <label for="phone"><?= __('Numer telefonu', 'am_calendar'); ?><input type="tel" name="phone" id="am_phone"></label>
                    <label for="email"><?= __('Adres email', 'am_calendar'); ?><input type="email" name="email" id="am_email"></label>
                    <label for="name"><?= __('Komentarz', 'am_calendar'); ?><textarea rows="4" cols="50" name="comment" id="am_comment"></textarea></label>
                    <input type="hidden" name="appointment_date_time">
                    <input type="hidden" name="appointment_date_time_end">
                    <input type="submit" href="#" value="<?= __('Wyślij', 'am_calendar'); ?>">
                </form>
            </div>
        </div>
    </div>
    <div class="am_calendar_rendered_container">
        <?php require_once('renderable/template-main-calendar-render.php'); ?>
    </div>
</div>