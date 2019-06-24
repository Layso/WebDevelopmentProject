<?php require_once("header_homepage.php"); ?>
<div class="login-container">
    <form action="registration" method="POST" class="form-login">
        <ul class="login-nav">
            <li class="login-nav__item active">
                <a>Register</a>
                
            </li>
        </ul>
        <h4 class="errors"><?php if (isset($errors["empty"])) echo $errors["empty"]; ?></h4>
        <label for="firstname" class="login__label">
            First Name
        </label>
        <input class="login__input" type="text" autofocus name="firstname" minlength="2" value="<?php if (isset($firstname)) echo $firstname; ?>" maxlength="51"  required/>
        <h4 class="errors"><?php if (isset($errors["firstname"])) echo $errors["firstname"]; ?> </h4>
        <label for="lastname" class="login__label">
            Last Name
        </label>
        <input class="login__input" type="text" name="lastname" minlength="2" maxlength="51" value="<?php if (isset($lastname)) echo $lastname; ?>"  required>
        <h4 class="errors"><?php if (isset($errors["lastname"])) echo $errors["lastname"]; ?></h4>
        <label for="email" class="login__label">
            E-mail
        </label>
        <input class="login__input" type="text" name="email" minlength="7" maxlength="320" value="<?php  if (isset($email)) echo $email; ?>"  required>
        <h4 class="errors"><?php  if (isset($errors["mailexisting"])) echo $errors["mailexisting"]; ?></h4>
        <label for="password" class="login__label">
            Password
        </label>
        <input class="login__input" type="password" name="pwd" minlength="6" maxlength="12"  required>
        <h4 class="errors">
        <?php
        
        if (isset($errors["pwdlength"])) {
            echo $errors["pwdlength"];
        }
        if (isset($errors["pwdsecure"])) {
            echo $errors["pwdsecure"];
        }
         
        ?>
        </h4>
        <label for="confirmPassword" class="login__label">
            Confirm password
        </label>
        <input class="login__input" type="password" name="confirmPwd"  required>
        <h4 class="errors"> <?php if (isset($errors["pwdmatch"])) echo $errors["pwdmatch"]; ?> </h4>
       
        <button type="submit" class="login__submit" >Register</button>
        
    </form>
    
</div>
<?php require_once("footer.php"); ?>