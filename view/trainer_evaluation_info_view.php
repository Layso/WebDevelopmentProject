<?php require_once("header.php"); ?>


<script>
    function complete() {
        var isAble = document.getElementById("button").value;
        

        
        if (isAble == "ok") {
            document.correct_form.submit();
        } else {
            alert("Please complete all sheets to complete evaluation");
        }
    }
</script>


<div style="text-align: center;">
    <h2> Evaluation Information </h2>
    <div>
        <table style="display: inline-block;">
            <tr>
                <td><h4> ID </h4></td>
                <td> <?= $evalInfo["id"] ?> </td>
            </tr>
            <tr>
                <td><h4> Status </h4></td>
                <td> <?= $evalInfo["status"] ?> </td>
            </tr>
            <tr>
                <td><h4> Group </h4></td>
                <td> <?= $evalInfo["group"] ?> </td>
            </tr>
        </table>
        <table style="display: inline-block;">
            <tr>
                <td><h4> Begin date </h4></td>
                <td> <?= $evalInfo["begin_time"] ?> </td>
            </tr>
            <tr>
                <td><h4> End date </h4></td>
                <td> <?= $evalInfo["end_time"] ?> </td>
            </tr>
            <tr>
                <td><h4> Completion date </h4></td>
                <td> <?= $evalInfo["correction_time"] ?> </td>
            </tr>
        </table>
        <table style="display: inline-block;">
            <tr>
                <td><h4> Average </h4></td>
                <td> <?= $evalInfo["average"] ?> </td>
            </tr>
            <tr>
                <td><h4> Sheets </h4></td>
                <td> <?= $evalInfo["total_sheet"] ?> </td>
            </tr>
            <tr>
                <td><h4> Corrected Sheets </h4></td>
                <td> <?= $evalInfo["corrected_sheet"] ?> </td>
            </tr>
        </table>
    </div>

    <?php
        if ($evalInfo["status"] == "Not Completed") {
    ?>   
        <form name="correct_form"  method="POST">
            <button type="button" id="button" value='<?= $readyToComplete?>'  onclick="complete()"> Complete </button>
        </form>
    <?php
        }
    ?>
</div>

<div>
    <h2> Sheets </h2>
    <?php
        if (count($sheetInfos) > 0) {
            foreach ($sheetInfos as $sheetInfo) {
    ?>
                <p><a href='<?= "trainer-" . $trainer_id . "_evaluation-" . $evalInfo["id"] . "_trainee-" . $sheetInfo["trainee_id"] ?>'>
                    <?php
                        echo ($sheetInfo["first_name"] . " " . $sheetInfo["name"] .  " - From: " . $sheetInfo["started_at"] .  " To: " . $sheetInfo["ended_at"] .  " - " . ($sheetInfo["corrected_at"] == null ? "Not corrected" : "Corrected"));
                    ?>
                </a></p>
    <?php
            }
        }
        else {
    ?>

        <p> No sheets found for evaluation </p>
    <?php
       }
    ?>
</div>

