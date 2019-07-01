<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mail_process {

  /**
   * 
   * @param type $length = length of token
   * @return a hashed token
   * 
   */
  public static function str_random($length) {
    $tokentest = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $tokentest2 = substr(str_shuffle(str_repeat($tokentest, $length)), 0, $length);
    return $tokentest2;
  }

}
