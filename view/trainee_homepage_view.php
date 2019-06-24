<?php require_once("header_homepage.php"); ?>
<div class="account-content">
    <h2>Your évaluations</h2>
    <div class="">
        <?php
        if (count($groups) > 0) {
            foreach ($groups as &$aGroup) {
                ?>
                <div>
                    <h3><?= "-Groupe " . $aGroup["group_id"]; ?></h3>
                    <?php
                    if (count($aGroup["evaluations"]) > 0) {
                        foreach ($aGroup["evaluations"] as $anEval) {
                            ?>
                            <p><?= $anEval["title"]; ?></p>


                            <?php
                        }
                    }
                    else{
                        echo '<p>No evaluation avalaible</p>';
                    }
                    ?>
                </div>

                <?php
            }
        } else {
            ?>
            <p>No group avalaible</p>

        <?php }
        ?>
    </div>
</div>
<?php require_once("footer.php"); ?>

