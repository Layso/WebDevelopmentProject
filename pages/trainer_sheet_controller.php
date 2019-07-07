<?php
    session_start();
    $errors = array();

    if (!isset($_SESSION["user"]["name"])) {
        $errors['NotConnected'] = "You aren't connected";
        require_once("../view/login_view.php");
    } else {
        $trainer_id = $_GET["trainer_id"];
        $evaluation_id = $_GET["evaluation_id"];
        $trainee_id = $_GET["trainee_id"];


        require_once("../view/trainer_sheet_view.php");
    }

?>