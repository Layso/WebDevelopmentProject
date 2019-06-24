<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Form_process {

  /**
   * Check names doesn't contains undesirable character  
   * */
  public static function format_name($name) {
    if (preg_match("#^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$#iu", $name)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Verify password is sure  and is equal to $ComfirmPassword
   * */
  /*  public static function check_password($password, $ComfirmPassword) {
    $errors = array();
    if ($password != $ComfirmPassword) {
    $errors["pwdmatch"] = 'The passwords do not match.';
    }
    if (strlen($password) < 6 || strlen($password) > 12) {
    $errors["pwdlength"] = 'Password must contain 6 to 12 characters.';
    }
    if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $password)) {
    $errors["pwdsecure"] = 'Your password is not secure, it must includes at least one lower-case letter, a capital letter, a numeral and one special characte';
    }
    if ($errors == array()) {
    $errors["pwdok"] = "ok";
    }
    return $errors;
    } */

  /* Verify names doesn't contains undesirable character  
   * Verify password is sure  and is equal to $ComfirmPassword
   * 
   * 
   *  */

  public static function register_format($firstname, $lastname, $password, $ComfirmPassword) {

    //$format_name = Form_process::format_name($firstname, $lastname);
    $format_firstname = Form_process::format_name($firstname);
    $format_lastname = Form_process::format_name($lastname);
    $errors = array();

    /* if ($format_name == false) {

      $errors["formatname"]='Your firstname or your lastname contains invalid characters';
      } */
    if ($format_firstname === false) {

      $errors["firstname"] = 'Your firstname contains invalid characters';
    }
    if ($format_lastname === false) {

      $errors["lastname"] = 'Your lastname contains invalid characters';
    }
    if ($password != $ComfirmPassword) {
      $errors["pwdmatch"] = 'The passwords do not match.';
    }
    if (strlen($password) < 6 || strlen($password) > 12) {
      $errors["pwdlength"] = 'Password must contain 6 to 12 characters.';
    }
    if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $password)) {
      $errors["pwdsecure"] = 'Your password is not secure, it must includes at least one lower-case letter, a capital letter, a numeral and one special character';
    }
    /* if ($errors == array()) {
      $errors["Formok"] = "ok";
      } */
    return $errors;
  }

}
