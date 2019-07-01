<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../model/SheetDao.php';
/* if (!empty($_POST)) {
  $ok = SheetDao::updateAnswer($_POST["trainee_id"], $_POST["evaluation_id"], $_POST["question_id"], $_POST["answer"]);
  if ($ok == '1') {
  http_response_code(204);
  } else {
  http_response_code(500);
  }
  } */
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
  parse_str(file_get_contents("php://input"), $_PUT);

  foreach ($_PUT as $key => $value) {
    unset($_PUT[$key]);

    $_PUT[str_replace('amp;', '', $key)] = $value;
  }

  $_REQUEST = array_merge($_REQUEST, $_PUT);
}
if (!empty($_PUT)) {
  $ok = SheetDao::updateAnswer($_PUT["trainee_id"], $_PUT["evaluation_id"], $_PUT["question_id"], $_PUT["answer"]);
  if ($ok == '1') {
    http_response_code(204);
  } else {
    http_response_code(500);
  }
} else {
  http_response_code(500);
}
