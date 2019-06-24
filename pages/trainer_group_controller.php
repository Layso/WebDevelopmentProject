<?php

session_start();
$errors = array();
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
  parse_str(file_get_contents("php://input"), $_PUT);

  foreach ($_PUT as $key => $value) {
    unset($_PUT[$key]);

    $_PUT[str_replace('amp;', '', $key)] = $value;
  }

  $_REQUEST = array_merge($_REQUEST, $_PUT);
}
//var_dump($_GET);
//echo 'lol';

if (!isset($_SESSION["user"]["name"])) {
  echo "You aren't connected";
  require_once("../view/login_view.php");
} else {

  $trainer_id = $_SESSION["user"]["person_id"];
  $is_trainer = $_SESSION["user"]["is_trainer"];

  if (!isset($trainer_id) || !isset($is_trainer)) {
    $ex = "trainer or user not define or your aren't a trainee";
    $errors['trainer'] = "The server endure the following error: " . $ex;
    include_once '../view/error_view.php';
//echo 'no';
  } else {
    if ($_SESSION["user"]["is_trainer"] == 1) {
      if ($_GET['trainer_id'] !== $trainer_id) {

        header('Location: ./trainer-' . $trainer_id . '_group');
      } else {
        include_once '../model/TrainerGroupDao.php';

        if (isset($_GET['action']) == false) {
          try {

            $openGroups = TrainerGroupDao::findOpenTrainerGroups($trainer_id);
            $closedGroups = TrainerGroupDao::findCloseTrainerGroups($trainer_id);
//var_dump($CloseGroups);
          } catch (Exception $ex) {

            $ex = "Error open/close groups";
            $errors['errorgroupe'] = "The server endure the following error: " . $ex;
            include_once '../view/error_view.php';
          }

          foreach ($openGroups as &$groups) {
            $group_id = $groups['group_id'];

            try {
              $groups['ValidatedTrainee'] = TrainerGroupDao::getNumberOfValidatedTrainee($group_id);
              $groups['NotValidatedTrainee'] = TrainerGroupDao::getNumberOfNotValidatedTrainee($group_id);
            } catch (Exception $ex) {

              $ex = 'Error Validate and not validated';
              $errors['errorvalidate'] = "The server endure the following error: " . $ex;
              include_once '../view/error_view.php';
            }
          }
          require_once'../view/trainer_group_view.php';
        } else {
          switch ($_GET['action']) {
            case 'add':

              include_once '../model/TrainerGroupDao.php';
              $name = $_POST['name'];
//var_dump($group_name);
              try {
                $addgroup = TrainerGroupDao::create($trainer_id, $name);
                header('Location: ./trainer-' . $trainer_id . '_group');
              } catch (Exception $ex) {
                $errors['PDOError'] = "Le serveur a subis l'erreur suivante: " . $ex;
                include_once '../view/error_view.php';
              }







              break;

            case 'closeGroups':

              if (isset($_PUT["groupid"]) || !empty($_PUT["groupid"])) {
                try {
                  include_once '../model/TrainerGroupDao.php';
                  TrainerGroupDao::CloseGroup($trainer_id, $_PUT['groupid']);
                  header("location: ./");
                } catch (PDOException $ex) {
                  $errors['PDOError'] = "Le serveur a subis l'erreur suivante: " . $ex;
                  http_response_code(500);
                }
              } else {
                $errors['groupid'] = " The server endure the following error:" . "there is a problem with a PUT request";
                http_response_code(500);
              }




              break;

            case 'reopenGroups':

              if (isset($_PUT["groupid"]) || !empty($_PUT["groupid"])) {
                try {
                  include_once '../model/TrainerGroupDao.php';
                  $ok = TrainerGroupDao::OpenGroup($trainer_id, $_PUT['groupid']);
                  header("location: ./");
                } catch (PDOException $ex) {
                  $errors['PDOError'] = "Le serveur a subis l'erreur suivante: " . $ex;
                  http_response_code(500);
                }
              } else {
                $errors['groupid'] = " The server endure the following error:" . "there is a problem with a PUT request";
                http_response_code(500);
              }

              break;

            case 'consult':
              //echo 'in construction';
              if (isset($_GET["group_id"]) || !empty($_GET["group_id"])) {
                include_once '../model/TrainerGroupDao.php';
                try {
                  $trainees = TrainerGroupDao::getMembersFiltred(TrainerGroupDao::getMembers($_GET["group_id"]));
                  //var_dump($trainees);
                  include_once '../view/trainer_consult_group_view.php';
                } catch (PDOException $ex) {
                  $errors['PDOError'] = "The server endure the following error: " . $ex;
                  include_once '../view/error_view.php';
                }
              } else {
                $errors["group_id"] = "The server endure the following error: group_id is not defined";
              }




              break;

            case 'validate':
              if (isset($_GET["group_id"]) || !empty($_GET["group_id"])) {
                include_once '../model/TrainerGroupDao.php';
                if (isset($_PUT) || empty($_PUT)) {
                  try {
                    include_once '../model/TrainerGroupDao.php';
                    $ok = TrainerGroupDao::validateCandidates($_GET["group_id"], $_PUT["trainees"]);
                    http_response_code(204);
                  } catch (PDOException $ex) {
                    $errors['groupid'] = " The server endure the following error:" . "there is a problem with the sql request";
                    http_response_code(500);
                  }
                } else {
                  $errors['groupid'] = " The server endure the following error:" . " PUT is not defined";
                  http_response_code(500);
                }
              } else {
                $errors["group_id"] = "The server endure the following error: group_id is not defined";
                include_once '../view/error_view.php';
              }

              break;
            default :

              header('Location: ./');
          }
        }
      }
    }
  }
}

