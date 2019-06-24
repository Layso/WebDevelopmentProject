<?php

session_start();
$errors = array();



if (isset($_GET['action']) == false) {

  header("Location: ./");
} else {
  $action = filter_input(INPUT_GET, "action");
  if ($action == "disconnect") {
    $_SESSION = array();
    session_destroy();
    header("Location: ./");
  } elseif (isset($_SESSION["user"]["name"])) {
    // echo 'You are already connected, You are going be redirected automaticaly to the Home page';
    //header("Refresh: 2;URL=./");
    header("Location: ./");
  } else {
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, "pwd", FILTER_SANITIZE_STRING);
    // var_dump($password);
    //var_dump($email);
    if (empty($email) || empty($password)) {

      $errors['empty'] = 'Please fill out all fields completely. ';
      require_once("../view/login_view.php");
    } else {
      require_once '../model/PersonDao.php';
      try {
        $user = PersonDao::get($email, $password);
        //var_dump($user);
        if ($user != null) {

          if ($user['validated_at'] == null) {

            $errors['notValidated'] = 'Your account is not validated yet. You can\'t access to your space';
            require_once("../view/login_view.php");

            //header("Refresh: 2;URL=./login");
          } elseif ($user['validated_at'] > new DateTime("now")) {

            $errors['validatedTime'] = 'there was a probleme with your account\'s validation, contact us for more informations';
            require_once("../view/login_view.php");
          } else {


            // Store the user in the session
            $_SESSION["user"] = $user;
            //var_dump($user);

            $nom = $_SESSION["user"]["name"];
            $trainee_id = $_SESSION["user"]["person_id"];

            if ($_SESSION["user"]["is_trainer"] == 1) {
              //  header('Location: ./trainer-' . $trainee_id . '_account');
              header('Location: ./');
            } else {


              header('Location: ./trainee-' . $trainee_id . '_evaluation');
              //echo "Bienvenue $nom";
              // header("Location: $url");
            }
          }
        } else {
          //var_dump($user);
          $errors['invalidelogin'] = 'Incorrect e-mail or password';
          require_once("../view/login_view.php");
        }
      } catch (PDOException $exc) {
        $errors['PdoExeption'] = 'Error with Database :' . $exc->getMessage();
      }
    }
  }
}
//var_dump($errors);