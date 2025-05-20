


<?php
if (!isset($_SESSION)) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['user']);
}

