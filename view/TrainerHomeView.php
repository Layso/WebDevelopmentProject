<?php
echo "This is a sample";
?> 
<h1>QUIZ</h1><a href=""></a>

  <div>
    <form action='<?= 'trainer-' . $trainer_id . '_addq' ?>' method="POST">
      <label for="title"> Title </label>
      <input type="text" name="title" id="title" required>
      <button type="submit">add</button>
    </form>
  </div>


  <script>