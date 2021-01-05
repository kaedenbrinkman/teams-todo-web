<?php
session_start();
$permission = 'https%3A%2F%2Fgraph.microsoft.com%2FTeam.ReadBasic.All%20 https%3A%2F%2Fgraph.microsoft.com%2Fuser.read%20 https%3A%2F%2Fgraph.microsoft.com%2Ftasks.readwrite';
$permission .= '%20https%3A%2F%2Fgraph.microsoft.com%2FEduRoster.ReadBasic%20 https%3A%2F%2Fgraph.microsoft.com%2FEduAssignments.ReadBasic%20';
//$permission = 'user.read%20Tasks.ReadWrite%20Teams.ReadBasic.All';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams-To Do Sync</title>
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/stylesheets/main.css" />
</head>

<body>
    <nav class="navbar navbar-default navbar-static-top navbar-inverse">
        <div class="container">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="/"><span class="glyphicon glyphicon-home"></span> Home</a>
                </li>
                <li>
                    <a href="#"><span class="glyphicon glyphicon-book"></span> About</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="navbar-right">
                    <?php
                    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == true) {
                    ?>
                        <a href="log_out.php"><span class="glyphicon glyphicon-user"></span> Log out</a>
                    <?php } else { ?>
                        <a href="authorize.php"><span class="glyphicon glyphicon-user"></span> Log in</a>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </nav>