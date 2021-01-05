<?php
include 'header.php';
if (isset($_GET['error'])) {
    echo 'Error: ' . $_GET['error_description'];
    echo '<a href="authorize.php">Try again</a>';
} else {
    if (isset($_GET['code'])) {
        //echo $_GET['code'];
        $_SESSION['code'] = $_GET['code'];
        $_SESSION['state'] = $_GET['state'];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=authorization_code&client_secret=PwDanV-rzZj.73CKEYt_~nAi94aBy7wT6t&code=' . $_SESSION['code'] . '&client_id=XXXXXXXXXXXXXXXXXXXXXXXXXX&scope=' . $permission,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        $response = (array) $response;
        if (isset($response['access_token'])) {
            $_SESSION['access_token'] = $response['access_token'];
            $_SESSION['refresh_token'] = $response['refresh_token'];
            $_SESSION['is_logged_in'] = true;
            setUserInfo();
            header('Location: /');
        } else {
            echo '<p>';
            echo 'Error: Did not receive access token and refresh token from Microsoft.';
            echo '<br>Message: ' . $response['error_description'];
            echo '</p>';
            echo '<script>console.log(\'' . str_replace("'", "\\'", json_encode($response)) . '\');</script>';
        }
        //echo $_SESSION['access_token'] . '<br>' . $_SESSION['refresh_token'];
    } else {
        echo 'Oops! Something went wrong.';
    }
}

function setUserInfo()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $_SESSION['access_token'],
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response);
    $response = (array) $response;
    $_SESSION['displayName'] = $response['displayName'];
    $_SESSION['email'] = $response['userPrincipalName'];
}
