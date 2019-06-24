<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DB {
  // Prefedined MySql integrity exceptions
  //const DUPLICATE_ENTRY = 1062;
  //const ROW_IS_REFERENCED = 1451;
  //const REFERENCED_ROW_NOT_FOUND = 1452;
  // Auction defined exceptions, raised by triggers
//   const BID_BEFORE_ENTRY_DATE = 3000;

  /** Get a connection to the DB, in UTF-8 */
  public static function getConnection() {
    // DB configuration
    $db = "sql_skills_v2";
    $dsn = "mysql:host=localhost;dbname=$db";
    $user = "sql_skills_v2_user";
    $password = "sql_skills_v2_pwd";
    // Get a DB connection with PDO library
    $bdd = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    // Set communication in utf-8
    //$bdd->exec("SET character_set_client = 'utf8'");
    // Throw the SQL exception
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $bdd;
  }

}
