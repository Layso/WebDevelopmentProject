<?php

session_start();
$errors = array();

if (!isset($_SESSION["user"]["name"])) {

  echo "You aren't connected";
  require_once("../view/login_view.php");
} else {
  //echo 'existant';

  $trainee_id = '3';
  $eval_Id = '5';

  $_SESSION["user"]["eval_id"] = $eval_Id;
  $_SESSION["user"]["trainee_id"] = $trainee_id;
  // $person_id = $_SESSION["user"]["person_id"];
  // 
  // 
  // 
  //$trainee_id = $_SESSION["user"]["person_id"];
  // $eval_Id = $_SESSION["user"]["eval_id"];
  $is_trainer = $_SESSION["user"]["is_trainer"];

  if (!isset($trainee_id) || !isset($eval_Id) || !isset($is_trainer)) {
    $ex = "trainee or eval not define or your are not a trainee";
    $errors['trainee'] = "The server endure the following error: " . $ex;
    include_once '../view/error_view.php';
    //echo 'no';
  }
  //var_dump($_SESSION["user"]);
  $checkid = false;

  if ($_SESSION["user"]["is_trainer"] == 0) {
    $checkid = true;
  }

  try {
    if ($checkid == true) {
      include '../model/SheetDao.php';
      SheetDao::start($trainee_id, $eval_Id);
      //var_dump($trainee_id);
      //var_dump($_SESSION);
      header('location: trainee-' . $trainee_id . '_evaluation-' . $eval_Id);
    } else {

      // can be in bubble/pop-up in homepage
      echo "Sorry, You are not allowed to access this page, You are going be redirected automaticaly to the Home page";
      header("Refresh: 2;URL=./");
    }
  } catch (PDOException $ex) {

    $errors['PDOError'] = "The server endure the following error:" . $ex;
    include_once '../view/error_view.php';
  }
}
?>
