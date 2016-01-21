<?php
$fb_success = Session::get('fb_success');
$fb_error = Session::get('fb_error');

if ($fb_success != NULL) {
    ?>
    <div class="updated notice is-dismissible" id="message-success">
        <?php
        foreach ($fb_success as $feedback) {
            echo "<p>" . $feedback . ".</p>";
        }

        ?>
        <button class="notice-dismiss" type="button" onclick="hideMessageSuccess()">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>
    <?php
}
if ($fb_error != NULL) {

    ?>
    <div class="error notice is-dismissible" id="message-error">
        <?php
        foreach ($fb_error as $feedback) {
            echo "<p>" . $feedback . ".</p>";
        }

        ?>
        <button class="notice-dismiss" type="button" onclick="hideMessageError()">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>
    <?php
}

if ($fb_error != NULL || $fb_success != NULL) {
    ?>
<script>
function hideMessageSuccess(){
    jQuery("#message-success").hide();
}
function hideMessageError(){
    jQuery("#message-error").hide();
}
</script>                
        <?php
}
?>