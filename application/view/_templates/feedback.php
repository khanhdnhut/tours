<?php

// Get the feedback
// They are arrays, to make multiple positive/negative messages possible
$fb_success = Session::get('fb_success');
$fb_error = Session::get('fb_error');

// Echo out positive messages
if (isset($fb_success)) {
    foreach ($fb_success as $feedback) {
        echo '<div class="alert alert-success">' . $feedback . '</div>';
    }
}

// Echo out negative messages
if (isset($fb_error)) {
    foreach ($fb_error as $feedback) {
        echo '<div class="alert alert-warning">' . $feedback . '</div>';
    }
}
