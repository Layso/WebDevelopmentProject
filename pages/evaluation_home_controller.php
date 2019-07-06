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
					if (!isset($groups) || is_array($groups) == false) {
						$ex = " Trainee group not define";
						$errors['GroupNotdefine'] = $ex;
					}

					foreach ($groups as &$aGroup) {
						$groups_id = $aGroup["group_id"];
						if (!isset($aGroup)) {
							$ex = " groupid not define";
							$errors['GroupeIdNotdefine'] = $ex;
						}

						$aGroup["evaluations"] = EvalDao::getEvaluationsGroup($groups_id);
						if (!isset($aGroup["evaluations"])) {
							$ex = " Evaluation not define";
							$errors['GroupeIdNotdefine'] = $ex;
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
			include_once '../model/EvalDao.php';
			include_once '../model/QuizTempDao.php';
			include_once '../model/TrainerGroupDao.php';

			$trainer = $_SESSION["user"];
			$openGroups = TrainerGroupDao::findOpenTrainerGroups($trainer["person_id"]);
			$closedGroups = TrainerGroupDao::findCloseTrainerGroups($trainer["person_id"]);
			$allGroups = array_merge($openGroups, $closedGroups);
			$createEvalInfo["group"] = $allGroups;
			$createEvalInfo["quiz"] = QuizTempDao::getAvailableQuizzes($trainer["person_id"]);

			$getEval["planned"] = EvalDao::getPlannedEvals($allGroups);
			$getEval["finished"] = EvalDao::getFinishedEvals($allGroups);
			


			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$start = $_POST["start_date"] . " " . $_POST["start_time"];
				$duration = explode(":", $_POST["duration"]);
				$extraTime = "+" . $duration[0] . "hour +" . $duration[1] . " minutes";
				$end = date('Y-m-d H:i',strtotime($extraTime ,strtotime($start)));

				EvalDao::createNewEval(TrainerGroupDao::getGroupIdByName($_POST["group"]), QuizTempDao::getQuizIdByName($_POST["quiz"]), $start, $end);
			}

			
			$getEval["planned"] = EvalDao::getPlannedEvals($allGroups);
			$getEval["finished"] = EvalDao::getFinishedEvals($allGroups);

			require_once("../view/trainer_evaluation_view.php");
		} else {
			$ex = "trainer not define ";
			$errors['traineridNotdefine'] = "$ex";
			include_once '../view/error_view.php';
		}
	}
}

