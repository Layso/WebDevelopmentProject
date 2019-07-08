<?php
    session_start();
    $errors = array();

    if (!isset($_SESSION["user"]["name"])) {
        $errors['NotConnected'] = "You aren't connected";
        require_once("../view/login_view.php");
    } else {
        include_once '../model/EvalDao.php';
        $trainer_id = $_SESSION["user"]["person_id"];
        $is_trainer = $_SESSION["user"]["is_trainer"];
        if (!isset($trainer_id) || !isset($is_trainer)) {
            $ex = "trainer or user not defined";
            $errors['UserNotdefine'] = $ex;
            include '../view/error_view.php';
        } else if (!$is_trainer) {
            $ex = "you aren't a trainer";
            $errors['UserNotTrainer'] = $ex;
            include '../view/error_view.php';
        } else if (!isset($_GET['trainer_id']) || !isset($_GET['evaluation_id'])) {
            header('Location: ./trainer-' . $trainer_id . '_evaluation');
        } else if (EvalDao::getEvalByID($_GET["evaluation_id"]) == null) {
            header('Location: ./trainer-' . $trainer_id . '_evaluation');
        } else {
            include_once '../model/EvalDao.php';
            include_once '../model/UserGroupDao.php';
            include_once '../model/SheetDao.php';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                EvalDao::setEvalCompleted($_GET["evaluation_id"]);
            }
            
            $evaluation_id = $_GET["evaluation_id"];
            $evaluation = EvalDao::getEvalByID($evaluation_id);
            $group = UserGroupDao::getGroupByID($evaluation["group_id"]);
            $sheets = SheetDao::getSheetsByEvaluation($evaluation_id);
            $corrected = 0;
            $average = 0;
            
            foreach ($sheets as $sheet) {
                if ($sheet["corrected_at"] != null) {
                    $sheet_answers = SheetDao::getAnswers($sheet["trainee_id"], $sheet["evaluation_id"]);
                    $correct_answers = 0;

                    foreach ($sheet_answers as $sheet_answer) {
                        if ($sheet_answer["gives_correct_result"] == true) {
                            $correct_answers++;
                        }
                    }

                    $average *= $corrected;
                    $average += $correct_answers;
                    $corrected++;
                    $average /= $corrected;
                }
            }

            $evalInfo["id"] = $evaluation_id;
            $evalInfo["status"] = $evaluation["corrected_at"] == null ? "Not Completed" : "Completed";
            $evalInfo["group"] = $group["name"];
            $evalInfo["begin_time"] = $evaluation["scheduled_at"];
            $evalInfo["end_time"] = $evaluation["ending_at"];
            $evalInfo["correction_time"] = $evaluation["corrected_at"] == null ? "-" : $evaluation["corrected_at"];
            $evalInfo["average"] = $corrected == 0 ? "-" : $average;
            $evalInfo["total_sheet"] = count($sheets);
            $evalInfo["corrected_sheet"] = $corrected;
            $sheetInfos = SheetDao::getAllSheetInfo($evaluation_id);
            $readyToComplete = count($sheets) == $corrected ? "ok" : "no";
            
            include '../view/trainer_evaluation_info_view.php';
        
        }
    }
?>