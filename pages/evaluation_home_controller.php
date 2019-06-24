<?php

session_start();
$errors = array();



//require_once '../model/EvalDao.php';
//$id=array(1);


if (!isset($_SESSION["user"]["name"])) {
    $errors['NotConnected'] = "You aren't connected";
    require_once("../view/login_view.php");
} else {

    $trainee_id = $_SESSION["user"]["person_id"];
    $is_trainer = $_SESSION["user"]["is_trainer"];
    if (!isset($trainee_id) || !isset($is_trainer)) {
        $ex = "trainer or user not define or you aren't a trainee";
        $errors['UserNotdefine'] = $ex;
        include '../view/error_view.php';
        //echo 'no';
    } else {



        if ($_SESSION["user"]["is_trainer"] == 0) {

            if ($_GET['trainee_id'] !== $trainee_id) {

                header('Location: ./trainee-' . $trainee_id . '_account');
            } else {



                include '../model/UserGroupDao.php';
                include '../model/EvalDao.php';
                try {

                    $groups = UserGroupDao::findTraineeGroups($trainee_id);
                    //var_dump($groups);

                    if (!isset($groups) || is_array($groups) == false) {
                        $ex = " Trainee group not define";
                        $errors['GroupNotdefine'] = $ex;

                        //echo 'no';
                    }
                    foreach ($groups as &$aGroup) {

                        $groups_id = $aGroup["group_id"];

                        if (!isset($aGroup)) {
                            $ex = " groupid not define";
                            $errors['GroupeIdNotdefine'] = $ex;
                            //echo 'no';
                        }



                        $aGroup["evaluations"] = EvalDao::getEvaluationsGroup($groups_id);
                        //var_dump($aGroup["evaluations"]);

                        if (!isset($aGroup["evaluations"])) {
                            $ex = " Evaluation not define";
                            $errors['GroupeIdNotdefine'] = $ex;
                            //echo 'no';
                        }
                    }
                    include_once '../view/error_view.php';
                    if (!isset($ex)) {
                        require_once '../view/trainee_homepage_view.php';
                    }
                } catch (PDOException $ex) {
                    $errors['PDOException'] = "there was a problem ... If the problem persists, contact us [$ex]";
                    include_once '../view/error_view.php';
                }
            }
        } elseif ($_SESSION["user"]["is_trainer"] == 1) {

            echo 'in working!';
        } else {
            $ex = "trainer not define ";
            $errors['traineridNotdefine']="$ex";
            include_once '../view/error_view.php';
        }
    }
}

