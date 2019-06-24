<?php require_once 'header_homepage.php'; ?>


    <h1>  Titre de l'évaluation</h1>
    <div class="sheet">
        <input type="hidden" name="trainee_id" value="<?= $trainee_id; ?>">
        <input type="hidden" name="trainee_id" value="<?= $eval_Id; ?>">
        <?php foreach ($sheet as $uneQuestion) { ?>
            <div class="sheet-card">
                <div class="sheet-card-question">
                    <label for> <?= $uneQuestion["question_text"]; ?></label>
                </div>
                <div class="sheet-card-answer">
                    <form method="POST">
                        <input type="hidden" name="question_id" value="<?= $uneQuestion["question_id"]; ?>">
                        <textarea   required
                         class="<?= $uneQuestion["question_id"]; ?>"><?php if ($uneQuestion["answer"] != null && $uneQuestion["answer"] !== "") echo $uneQuestion["answer"]; ?></textarea>
                        <button type="submit" required >Confirm</button>
                    </form>
                </div>

            </div>

        <?php } ?>
        <a class="btn" href="">Finish</a>
    </div>   
   <script>
        var t =<?= $trainee_id; ?>;
        var e =<?= $eval_Id ?>;
    </script> 

<?php require_once 'footer.php'; ?>