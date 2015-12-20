<?php

// Get the feedback
// They are arrays, to make multiple positive/negative messages possible
$feedback_positive = Session::get('feedback_positive');
$feedback_negative = Session::get('feedback_negative');

// Echo out positive messages
if (isset($feedback_positive)) {
    foreach ($feedback_positive as $feedback) {
        echo '<div class="alert alert-success">' . $feedback . '</div>';
    }
}

// Echo out negative messages
if (isset($feedback_negative)) {
    foreach ($feedback_negative as $feedback) {
        echo '<div class="alert alert-warning">' . $feedback . '</div>';
    }
}
