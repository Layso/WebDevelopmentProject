<?php

session_start();
$errors = array();
if (!isset($_SESSION["user"]["name"])) {

  echo "You aren't connected";
  require_once("../view/login_view.php");
} else {

  $trainee_id = $_SESSION["user"]["person_id"];
  $eval_Id = $_SESSION["user"]["eval_id"];
  $is_trainer = $_SESSION["user"]["is_trainer"];
  if (!isset($trainee_id) || !isset($eval_Id) || !isset($is_trainer)) {
    $ex = "trainer or eval not define or your aren't a trainer";
    $errors['trainer'] = "The server endure the following error: " . $ex;
    include_once '../view/error_view.php';
    //echo 'no';
  } else {

    //var_dump($_GET);
    //var_dump($trainee_id);
    //var_dump($eval_Id);

    if ($_GET['trainee_id'] !== $trainee_id || $_GET['eval_id'] !== $eval_Id) {
      echo 'ok';
      header('location: trainee-' . $trainee_id . '_evaluation-' . $eval_Id . '.php');
    } else {






      /*
       * 
       * check if persone_id == trainee_id 
       * redirect to trainee_sheet_view if is true
       * 
       */
      if ($_SESSION["user"]["is_trainer"] == 0) {


        try {
          include '../model/SheetDao.php';
          if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            parse_str(file_get_contents("php://input"), $_PUT);

            foreach ($_PUT as $key => $value) {
              unset($_PUT[$key]);

              $_PUT[str_replace('amp;', '', $key)] = $value;
            }

            $_REQUEST = array_merge($_REQUEST, $_PUT);
          }
          if (isset($_PUT["state"])) {
            //   var_dump($_PUT["state"]);
            switch ($_PUT["state"]) {
              case "completed":

                SheetDao::setCompleted($trainee_id, $eval_Id);
                // can be in bubble/pop-up in homepage
                echo "Your evaluation has been validated";
                header("Refresh: 2;URL=../");
                ;
                break;
            }
          } else {
            $sheet = SheetDao::get($trainee_id, $eval_Id);

            include "../view/trainee_sheet_view.php";
          }
        } catch (PDOException $ex) {
          $ex = 'SqlExeption Display' . $ex;
          $errors['SQLexeption'] = "The server endure the following error: " . $ex;
        }
      } elseif ($_SESSION["user"]["is_trainer"] == 1) {

        echo 'You aren\'t a trainee, you can\'t pass a an exam';
      }
      /* } else {
        echo "Sorry, You are not allowed to access this page, You are going be redirected automaticaly to the Home page";
        header("Refresh: 2;URL=../");
        //var_dump($_SERVER["REQUEST_URI"]);
        //var_dump($_SESSION["user"]);
        } */
    }
  }
}
