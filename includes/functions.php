<?php
function sanitize($s) {
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}
