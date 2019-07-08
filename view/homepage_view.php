<?php require_once("header.php"); 

  $trainer_id = $_SESSION["user"]["person_id"];
  $is_trainer = $_SESSION["user"]["is_trainer"];


 
    if ($_SESSION["user"]["is_trainer"] == 1) {
        require_once("../model/TrainerHomeDao.php");
    
  }
?>
<div>

  <p> Welcome to Cefisi platform for training and Evaluation</p>

</div>
<?php require_once("footer.php"); ?>
