<?php
if (!defined('BASE_URL')) {
    // define('BASE_URL', 'https://dev.thcitsolutions.com/aastha-pms/');
    // define('BASE_URL', 'http://localhost/git/aastha-combined/html project/');
     define('BASE_URL', 'http://localhost/git/aastha-front/');
}

if (!defined('API_URL')) {
    define('API_URL', 'https://aastha-pms.dhokai.co.in/');
}

if (!isset($_SESSION['access_token'])) {
    $_SESSION['access_token'] = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODQxMDY3MywianRpIjoiYjMwOTA2ODMtYjc2My00OThjLWE1NTMtMzM3N2NlMjNmODRkIiwidHlwZSI6ImFjY2VzcyIsInN1YiI6ImplbnNpLmNoYW5nYW5pQHZlZGlraW4uY29tIiwibmJmIjoxNzQ4NDEwNjczLCJjc3JmIjoiMDY5YzNjOTktMTZhMy00MGRiLTk4ZTgtZjZiY2ZiN2U5NjFlIiwiZXhwIjoxNzQ4NDk3MDczfQ.oQn0DJ2l4kZoVbJ1kxWNAOwENWrpt_ERmsrd9o8lq1o';
}

// Notification settings
define('NOTIFICATION_POLLING_INTERVAL', 250000); // 25 seconds in milliseconds
