<?php require_once("header.php"); ?>
<div class="evaluation-content">

  <h1> My Evaluations </h1>

  <div class="evaluation-coming">

    <h3>Evaluation coming </h3>
    <?php
    if (isset($getEval["coming"]) && count($getEval["coming"]) > 0) {
      foreach ($getEval["coming"] as &$uneEval) {
        ?>
        <div class="evaluation-card">
          <p><?= $uneEval["title"] . " " . $uneEval["scheduled_at"] . " time: " . $uneEval["time_diff"] ?></p>
        </div>

        <?php
      }
    } else {
      echo "Any evaluation is coming";
    }
    ?>
  </div>
  <div class="evaluation-finished">
    <h3>Evaluation finished </h3>
    <?php
    if (isset($getEval["finished"]) && count($getEval["finished"]) > 0) {
      foreach ($getEval["finished"] as &$uneEval) {
        ?>
        <div class="evaluation-card">
          <p><?= $uneEval["title"] . " " . $uneEval["scheduled_at"] . " time: " . $uneEval["time_diff"] ?></p>
        </div>

        <?php
      }
    } else {
      echo "Any evaluation is coming";
    }
    ?>

  </div>
  <h3>Evaluation corrected </h3>
  <div class="evluation-corrected">
    <?php
    if (isset($getEval["corrected"]) && count($getEval["corrected"]) > 0) {
      foreach ($getEval["corrected"] as &$uneEval) {
        ?>
        <div class="evaluation-card">
          <p><?= $uneEval["title"] . " " . $uneEval["scheduled_at"] . " time: " . $uneEval["time_diff"] ?></p>
        </div>

        <?php
      }
    } else {
      echo "Any evaluation is coming";
    }
    ?>

  </div>
</div>

<?php require_once ('footer.php'); ?>
