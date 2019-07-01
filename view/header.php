<?php
if (isset($_SESSION["user"]["name"])) {
  $trainee_id = $_SESSION["user"]["person_id"];
}
//var_dump($_GET);
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
  <head>
    <title>Sql-Skills</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="../static/style.css">
    <script src="../static/jquery-3.3.1.js"></script>
    <script src="../static/app.js"></script>
  </head>
  <body>
    <header>
      <nav class="topbar">
        <div class="logo">
          <img src="">
        </div>
        <div class="menu">

          <a href="./" class="menu-item">Home</a>
          <!--<a href="www.google.fr" class="menu-item">Training</a>-->
          <?php
          if (isset($_SESSION["user"]["name"])) {
            if ($_SESSION["user"]["is_trainer"] == 1) {
              ?>
                              <!--<a href=<?= 'trainer-' . $trainee_id . '_account' ?> class="menu-item">Account</a>-->
              <a href=<?= 'trainer-' . $trainee_id . '_evaluation' ?> class="menu-item">Evaluation</a>
              <a href=<?= 'trainer-' . $trainee_id . '_group' ?> class="menu-item">Group</a>


              <?php
            } else {
              ?>
                                        <!--<a href=<?= 'trainee-' . $trainee_id . '_account' ?> class="menu-item">Account</a>-->
              <a href=<?= 'trainee-' . $trainee_id . '_evaluation' ?> class="menu-item">Evaluation</a>
              <a href=<?= 'trainee-' . $trainee_id . '_group' ?> class="menu-item">Groups</a>



              <?php
            }
          }
          ?>

          <div class="menu-btn">
            <?php
            if (!isset($_SESSION["user"]["name"])) {
              ?>
              <a href="register" class="menu-btn btn-register" >Register</a>
              <a href="login" class="menu-btn btn-login">Login</a>
              <?php
            }
            ?>
            <?php
            if (isset($_SESSION["user"]["name"])) {
              ?>
              <a href="disconnect" class="menu-btn btn-logout">Log out</a>
              <?php
            }
            ?>

          </div>
        </div>
      </nav>
    </header>


  </nav>

