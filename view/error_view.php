<?php require_once 'header.php'; ?>

<div class="errors">
  <?php
  //  var_dump($errors);
  if (isset($errors) && count($errors) > 0) {
    foreach ($errors as $error) {
      echo "<div>" . $error . "</div>";
    }
  }
  ?>

</div>

<?php require_once 'footer.php'; ?>

