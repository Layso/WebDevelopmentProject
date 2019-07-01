<?php

session_start();
$errors = array();
//echo 'lol';

if (!isset($_SESSION["user"]["name"])) {
  $errors["connection"] = "You are not connected.";
  require_once("../view/login_view.php");
} else {

  $trainee_id = $_SESSION["user"]["person_id"];
  $is_trainer = $_SESSION["user"]["is_trainer"];
  if (!isset($trainee_id) || !isset($is_trainer)) {
    $ex = "trainee is not define or you aren't a trainee";
    $errors['trainee'] = "The server endure the following error: " . $ex;
    include_once '../view/error_view.php';
    //echo 'no';
  } else {


    if ($_SESSION["user"]["is_trainer"] == 0) {

      //var_dump($_GET);
      //$_GET['trainee_id']='3';

      if ($_GET['trainee_id'] !== $trainee_id) {

        // echo 'ok';
        header('Location: ./trainee-' . $trainee_id . '_group');
      } else {
        //var_dump($ex)

        if (isset($_GET['action']) == false) {
          include '../model/UserGroupDao.php';
          // echo 'false';

          try {

            $groupfind = UserGroupDao::findTraineeGroups($trainee_id);
            $groupAvai = UserGroupDao::findAvalaibleGroups($trainee_id);
            // var_dump($groupAvai);
            if (!isset($groupfind) || !isset($groupAvai)) {
              $ex = " group not find";
              $errors['group'] = "The server endure the following error: " . $ex;
              include_once '../view/error_view.php';
              //echo 'no';
            } else {
              require_once '../view/groups_view.php';
            }
          } catch (PDOException $ex) {
            $errors['PDOException'] = "The server endure the following error: " . $ex;
            include_once '../view/error_view.php';
          }
        } else {
          //$_GET['action']='ok';
          switch ($_GET['action']) {

            case "join":

              $groupid = filter_input(INPUT_POST, "groupid", FILTER_VALIDATE_INT);
              var_dump($groupid);
              if (!isset($groupid)) {

                $ex = 'groupid is not define';
                $errors['group'] = "The server endure the following error: " . $ex;
                include_once '../view/error_view.php';
              } else {
                if (filter_var($groupid, FILTER_VALIDATE_INT)) {
                  try {
                    include '../model/UserGroupDao.php';
                    //   var_dump($groupid);
                    UserGroupDao::join($trainee_id, $groupid);
                    // var_dump($groupid);
                    //var_dump($trainee_id);
                    // echo 'Joined';
                    header('Location: trainee-' . $trainee_id . '_group');
                  } catch (PDOException $ex) {
                    $errors['PDOException'] = "The server endure the following error: " . $ex;
                    include_once '../view/error_view.php';
                  }
                } else {
                  $ex = " There is a problem with the type ";
                  $errors['groupId'] = "The server endure the following error: " . $ex;
                  include_once '../view/error_view.php';
                }
              }

              break;



            default :
              header('Location: ./trainee-' . $trainee_id . '_group');
              //echo 'default';
              break;
          }
        }
      }
    } else {
      $ex = "the user is not a trainee";
      $errors['trainer'] = "The server endure the following error: " . $ex;
      include_once '../view/error_view.php';
    }
  }
}
?>
