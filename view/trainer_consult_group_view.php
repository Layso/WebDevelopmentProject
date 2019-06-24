<?php include_once 'header_homepage.php'; ?>

<h2> Group </h2>

<div class="trainer-groups">

    <div class="group-candidates">
        <h3>Candidate to validate </h3>
        <form>
            <?php
            if (count($trainees["candidates"]) > 0) {
                echo "<div class='candidates-list'>";
                foreach ($trainees["candidates"] as &$trainee) {
                    ?>
                    <li>
                        <input type="checkbox" name="trainee[]" id="trainee" value="<?= $trainee["trainee_id"] ?>">
                        <label for="trainee"><?= $trainee["name"] . " " . $trainee["first_name"] ?></label>
                    </li>
                    <?php
                }
                echo "</div>";
            }
            ?>
            <button name="validate" type="button">Validate</button>
        </form>
    </div>
    <div class="group-members">
       <h3>Candidate to validate </h3>
        <form>
            <?php
            if (count($trainees["validated"]) > 0) {
                echo "<div class='validated-list'>";
                foreach ($trainees["validated"] as &$trainee) {
                    ?>
                    <li>
                        <input type="checkbox" name="trainee[]" id="trainee" value="<?= $trainee["trainee_id"] ?>">
                        <label for="trainee"><?= $trainee["name"] . " " . $trainee["first_name"] ?></label>
                    </li>
                    <?php
                }
                echo "</div>";
            }
            ?>
            <button name="remove" type="button">Remove</button>
        </form>
    </div>
</div>

<script> e =<?= $trainer_id; ?>;
    g =<?= $_GET["group_id"]; ?>;</script>
<?php include_once 'footer.php'; ?>

