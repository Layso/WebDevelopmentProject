<?php require_once("header.php"); ?>
<?php
if (isset($_GET['success'])) {
  if ($_GET['success'] == 'success') {

    echo 'You have been successfully registered';
  }
}
?>
<div class="login-container">
  <form method="POST" action='connect' class="form-login">
    <ul class="login-nav">
      <li class="login-nav__item active">
        <a>Sign In</a>

      </li>
    </ul>
    <label for="email" class="login__label">
      E-mail
    </label>
    <input class="login__input" type="email" autofocus name="email" />
    <label for="pwd" class="login__label">
      Password
    </label>
    <input class="login__input" type="password" name="pwd" />


    <button class="login__submit" type="submit">Sign in</button>
    <!--</ul>-->
    <h4 class="errors">
      <?php
//var_dump($errors);
      if (!isset($errors['empty']) && !isset($errors['invalidelogin'])) {
        if (isset($errors) && count($errors) > 0) {
          foreach ($errors as $error) {
            echo "<div class='errors'>" . $error . "</div>";
          }
        }
      }
      ?>
      <?php if (isset($errors["empty"])) echo $errors["empty"]; ?>
      <?php if (isset($errors["invalidelogin"])) echo $errors["invalidelogin"]; ?>

    </h4>
  </form>
  <ul class="login-nav">
    <a href="#" class="login__forgot">Forgot Password?</a>
  </ul>
</div>
<?php require_once("footer.php"); ?>