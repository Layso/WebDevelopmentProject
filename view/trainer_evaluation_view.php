<?php require_once("header.php"); ?>

<script>
window.onload = function () {
    document.getElementsByName("duration")[0].addEventListener('change', doThing);
	document.getElementsByName("start_time")[0].addEventListener('change', doThing);
    
    /* function */
    function doThing(){
		var duration = document.getElementsByName('duration')[0].value;
		var startTime = document.getElementsByName('start_time')[0].value;
		var endTime = document.getElementsByName('end_time')[0].value;

		if (duration != "" && startTime != "") {
			var minutes = (parseInt(startTime.split(":")[1]) + parseInt(duration.split(":")[1])) % 60;
			var extraHour = parseInt((parseInt(startTime.split(":")[1]) + parseInt(duration.split(":")[1])) / 60);
			var hours = (parseInt(startTime.split(":")[0]) + parseInt(duration.split(":")[0]) + extraHour) % 24;
			
			var hourStr = hours < 10 ? "0" + hours : hours;
			var minStr = minutes < 10 ? "0" + minutes : minutes;
			document.getElementsByName("end_time")[0].innerHTML = hourStr + ":" + minStr;
		}
	}
}

function checkForm(){
	var quiz = document.getElementsByName('quiz')[0].value;
	var group = document.getElementsByName('group')[0].value;
	var startDate = document.getElementsByName('start_date')[0].value;
	var startTime = document.getElementsByName('start_time')[0].value;
	var duration = document.getElementsByName('duration')[0].value;
	var error_msg = "";


	if (quiz == "no_quiz") {
		error_msg += "- Please select a valid Quiz\n";
	}

	if (group == "no_group") {
		error_msg += "- Please select a valid Group\n";
	}

	if (startDate == "" || startTime == "") {
		error_msg += "- Please enter a valid date\n";
	} else {
		var timeDiff = new Date(startDate+'T'+startTime) - Date.now();
		if (timeDiff < 86400000) {
			error_msg += "- Start date must be at least 24h later from now\n";
		}
	}

	if (duration == "" | duration == "00:00") {
		error_msg += "- Please enter a valid duration\n";
	}

	if (error_msg == "") {
		document.create_eval_form.submit();
	} else {
		alert(error_msg);
	}
}
</script>


<div class="evaluation-content">
  <div class="evaluation-planned">
	<h2>Evaluations Planned / Ongoing </h2>
	<?php
	if (isset($getEval["planned"]) && count($getEval["planned"]) > 0) {
	  foreach ($getEval["planned"] as &$uneEval) {
		?>
		<div class="evaluation-card">
		  <p><?= "Evaluation " . $uneEval["evaluation_id"] . " Starting at: " . $uneEval["scheduled_at"] ?></p>
		</div>
		<?php
	  }
	} else {
	  echo "No evaluations planned";
	}
	?>
  </div>

  <div class="evaluation-finished">
	<h2>Evaluations finished </h2>
	<?php
	if (isset($getEval["finished"]) && count($getEval["finished"]) > 0) {
	  foreach ($getEval["finished"] as &$uneEval) {
		?>
		<div class="evaluation-card">
		<p><?= "Evaluation " . $uneEval["evaluation_id"] . " Ended at: " . $uneEval["ending_at"] ?></p>
		</div>

		<?php
	  }
	} else {
	  echo "No evaluations finished";
	}
	?>

  </div>

  <div class="create-evaluation">
	<h2>Create Evaluation </h2>
	<form name="create_eval_form" action='<?= 'trainer-' . $trainer["person_id"] . '_evaluation' ?>' method="POST">
		<p>
			Select quiz:
			<select name="quiz">
				<?php
				if (isset($createEvalInfo["quiz"]) && count($createEvalInfo["quiz"]) > 0) {
					foreach($createEvalInfo["quiz"] as $quiz) {
						?>
						<option><?= $quiz["title"]?></option>
						<?php
					} 
				} else {
					?>
					<option value="no_quiz"> - Create new Quiz - </option>
					<?php
				}
				?>
			</select>
		</p>

		<p>
			Select group:
			<select name="group">
				<?php
				if (isset($createEvalInfo["group"]) && count($createEvalInfo["group"]) > 0) {
					foreach($createEvalInfo["group"] as $group) {
						?>
						<option><?= $group["name"]?></option>
						<?php
					} 
				} else {
					?>
					<option value="no_group"> - Create new Group - </option>
					<?php
				}
				?>
			</select>
		</p>

		<p>
			Start date:
			<input type="date" name="start_date">
			<input type="time" name="start_time"> -> <span name="end_time">  </span>
		</p>

		<p>
			Duration:
			<input type="time" name="duration" value="" />
		</p>

		<button type="button" onclick="checkForm()">Create</button>
	</form>
  </div>
</div>

<?php require_once ('footer.php'); ?>
