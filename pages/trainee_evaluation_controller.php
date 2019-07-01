<?php

session_start();
$errors = array();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//var_dump($_GET);
if (!isset($_SESSION["user"]["name"])) {
  $errors["connection"] = "You aren't connected";
  include '../view/login_view.php';
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
      if ($_GET['trainee_id'] !== $trainee_id) {

        header('Location: trainee-' . $trainee_id . '_evaluation');
        // echo 'ok';
      } else {

        try {
          include '../model/EvalDao.php';
          $getEval = EvalDao::getFilteredTrainee(EvalDao::getEvaluationsTrainee($trainee_id));
          if (!isset($getEval) || is_array($getEval) == false) {
            $ex = " Evaluation is not define";
            $errors['eval'] = "The server endure the following error: " . $ex;
            include_once '../view/error_view.php';
          }

          require_once '../view/trainee_evaluation_view.php';
        } catch (PDOException $ex) {
          $errors['PDOError'] = "The server endure the following error: " . $ex;
          include_once '../view/error_view.php';
        }
      }
    } elseif ($_SESSION["user"]["is_trainer"] == 1) {

      echo 'IN working !';
    } else {
      $ex = "trainer is not define ";
      $errors['trainer'] = "The server endure the following error: " . $ex;
      include_once '../view/error_view.php';
    }
  }
}
