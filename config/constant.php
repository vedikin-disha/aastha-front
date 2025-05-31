<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/git/aastha-combined/html project/');
}

if (!defined('API_URL')) {
    define('API_URL', 'https://windev.thcitsolutions.com:10000/');
    // define('API_URL', 'http://127.0.0.1:5000/');
}

if (!isset($_SESSION['access_token'])) {
    $_SESSION['access_token'] = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODUyMzE3MywianRpIjoiMGE3ZDVmNDgtNTBmYS00OTI1LWI4YmItMzQ5NGJjNDg3NGI5IiwidHlwZSI6ImFjY2VzcyIsInN1YiI6ImpvaG4uZG9lQGV4YW1wbGUxZS5jb20iLCJuYmYiOjE3NDg1MjMxNzMsImNzcmYiOiJlZDRmNTAzYS0wZmJjLTQ4ODAtYTg4MS1iNTgzNmE0MWZiYTYiLCJleHAiOjE3NDg2MDk1NzN9.I_jzPENOgXmTzwd5pa8EU1JliSaT9mLqIlQy3Za0n80';
}