<?php
include 'header.php';
if (isset($_SESSION['is_logged_in']) && $_SESSION['access_token'] != null) {
    echo 'Logged in';
    header('Location : /');
} else {
    $url = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=a9567c1e-64b7-4a87-8a51-fffefbb9ba95&scope=' . $permission . '&state=12345&response_type=code&response_mode=query';
    echo 'Not logged in';
    //header('Location: ' . $url);
    echo '<script>window.location = "' . $url . '";</script>';
    echo '<a href="' . $url . '">If you are not automatically redirected, click here.</a>';
}
