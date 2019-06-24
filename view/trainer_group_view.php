<?php include_once 'header.php'; ?>


<div clas="groups">

  <h1>Groups</h1><a href=""></a>

  <div>
    <form action='<?= 'trainer-' . $trainer_id . '_add' ?>' method="POST">
      <label for="name"> New Group </label>
      <input type="text" name="name" id="name" required>
      <button type="submit">add</button>
    </form>
  </div>
  <div class="groups-open">
    <?php
    if (count($openGroups) > 0) {
      echo "<h2> Open </h2>";
      foreach ($openGroups as &$group) {
        ?>
        <div class="group">

          <form method="PUT" action="<?= 'trainer-' . $trainer_id . '_closeGroup' ?>">
            <input type="hidden" name="groupid" value="<?= $group["group_id"]; ?>">
            <a href="<?= 'trainer-' . $trainer_id . '_group-' . $group["group_id"]; ?>"><?= $group["name"] . "  trainee validated:  " . $group["ValidatedTrainee"]["ValidateNumber"] . " trainee to validate:  " . $group['NotValidatedTrainee']["NotValidateNumber"]; ?></a>
            <button type="submit"> Close </button>

          </form>
        </div>


        <?php
      }
    } else {
      echo "<p> Aucun groupe n'a été créé</p>";
    }
    ?>

  </div>

  <div class="groups_closed">
    <?php
    if (count($closedGroups) > 0) {
      echo "<h2> Close </h2>";
      foreach ($closedGroups as &$group) {
        ?>
        <div class="group">

          <form method="PUT" action="<?= 'trainer-' . $trainer_id . '_reopenGroup' ?>">
            <input type="hidden" name="groupid" value="<?= $group["group_id"]; ?>">
            <label><?= $group["name"]; ?></label>
            <button type="submit"> Reopen </button>
          </form>
        </div>


        <?php
      }
    }
    ?>

  </div>

</div>
<script> e=<?= $trainer_id ?> </script>
<?php include_once 'footer.php'; ?>
