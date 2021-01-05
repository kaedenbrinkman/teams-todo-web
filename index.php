<?php
include 'header.php';


if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == true) {
?>
    <div class="jumbotron text-center">
        <div class="container">
            <h1><?php echo $_SESSION['displayName']; ?></h1>
            <p><?php echo $_SESSION['email']; ?></p>
        </div>
    </div>
    <div class="container">
        <div class="col-md-6">
            <div class="row"><?php getClasses(); ?></div>
        </div>
        <div class="col-md-6">
            <div class="row"><?php getToDoLists(); ?></div>
        </div>

    </div>
    <div class="alert alert-info text-center" role="alert">
        Note: This app is still under development, and you will probably find issues with it.
    </div>
<?php
}
function getTeams()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/joinedTeams',
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

    $response = json_decode($response);
    $response = (array) $response;

    curl_close($curl);
    echo '<h3>Your Teams</h3>';
    if (isset($response['value']) && $response['value'] != null) {
        foreach ($response['value'] as $list) {
            $list = (array) $list;
            echo '<p id="' . $list['id'] . '">' . $list['displayName'] . '</p>';
        }
    } else {
        echo 'Could not get the list of Teams you\'re a member of.';
        echo $response['error_description'] . '<br>';
        echo '<script>console.log(\'' . str_replace("\n", "\\n", str_replace("'", "\\'", json_encode($response))) . '\');</script>';
    }
    //echo '<script>console.log(\'' . str_replace("\n", "\\n", str_replace("'", "\\'", $_SESSION['access_token'])) . '\');</script>';
}

function getToDoLists()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/todo/lists',
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
    echo '<h3>Your To-Do Lists</h3>';
    $response = json_decode($response);
    $response = (array) $response;
    if (isset($response['value']) && $response['value'] != null) {
        foreach ($response['value'] as $list) {
            $list = (array) $list;
            echo '<p id="' . $list['id'] . '"><strong>' . $list['displayName'] . '</strong></p>';
            getToDoList($list['id']);
        }
    } else {
        echo 'Error: Couldn\'t access your To-Do lists.';
    }
}

function getToDoList($id)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/todo/lists/' . $id . '/tasks',
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
    if (isset($response['value']) && $response['value'] != null) {
        foreach ($response['value'] as $item) {
            $item = (array) $item;
            echo '<li id="' . $item['id'] . '">' . $item['title'] . '</li>';
        }
    } else if ($response['value'] == null) {
        echo 'This list is empty.';
    } else {
        echo 'Error: Could not access this list.';
    }
}

function getClasses()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.microsoft.com/beta/education/classes',
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
    echo '<h3>Your Classes</h3>';
    $response = json_decode($response);
    $response = (array) $response;
    if (isset($response['value']) && $response['value'] != null) {
        foreach ($response['value'] as $class) {
            $class = (array) $class;
            echo '<p id="' . $class['id'] . '"><strong>' . $class['displayName'] . '</strong></p>';
            getClassAssignments($class['id']);
        }
    } else {
        echo 'Could not access your Class List.';
    }
}

function getClassAssignments($id)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://graph.microsoft.com/beta/education/classes/' . $id . '/assignments',
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
    if (isset($response['value']) && $response['value'] != null) {
        foreach ($response['value'] as $item) {
            $item = (array) $item;
            echo '<li id="' . $item['id'] . '">' . $item['title'] . '</li>';
        }
    } else if ($response['value'] == null) {
        echo 'This class doesn\'t have any assignments.';
    } else {
        echo 'Error: Could not access class assignments.';
    }
}
