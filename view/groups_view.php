
<?php require_once("header.php"); ?>
<div class="groups">
  <div class="groups-validated">
    <h3>My Groups </h3>
    <?php
    if (count($groupfind)) {
      foreach ($groupfind as $g) {
        ?>
        <ul class="group">
          <li><?= $g["name"]; ?> <?php
            if ($g["validated_at"] == null) {
              echo '(not yet validated)';
            }
            ?>
          </li>
        </ul>
        <?php
      }
    } else {
      ?>
      <p>Vous n'appartenez à aucun groupe</p>

    <?php } ?>

  </div>

  <div class="groups-avalaible">
    <h3>Groups available </h3>

    <?php
    if (count($groupAvai) > 0) {
      foreach ($groupAvai as $g) {
        ?>
        <div class="group">

          <form method="POST" action='<?= 'trainee-' . $trainee_id . '_join' ?>'>
            <input type="hidden" name="groupid" value="<?= $g["group_id"]; ?>" >
            <label for="submit"><?= $g["name"]; ?></label><input type='submit' class='submit' value="+">
          </form>


        </div>  
        <?php
      }
    } else {
      ?>
      <p>No groups available</p>

    <?php } ?>


  </div>
</div>
<?php require_once("footer.php"); ?>
